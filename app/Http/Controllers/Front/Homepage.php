<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
//Models
use App\Models\Article;
use App\Models\Category;
use App\Models\Page;
use App\Models\Contact;
use App\Models\Config;
use Mail;
use Validator;
use DateTime;
class Homepage extends Controller
{
      //tüm fonksiyonlarda  çalışmasını istediğimiz kısım
    public function __construct(){
      if(Config::find(1)->active==0){
        return redirect()->to('site-bakimda')->send();
      }
      view()->share('pages',Page::where('status',1)->orderBy('order','ASC')->get());
      view()->share('categories',Category::where('status',1)->inRandomOrder()->get());
      view()->share('config',Config::find(1));
    }
    public function index(){
      Carbon::setLocale('tr');
      //with ile relation ı çağırıyoruz.
      $data['articles']=Article::with('getCategory')
      ->whereHas('getCategory',function($query){
        $query->where('status',1);
      })
      ->where('status',1)
      ->orderBy('created_at','DESC')->paginate(10);

      //dd($data['articles']);

      $data['articles']->withPath(url('sayfa'));

      //constructtta tanımladığımız için gerek kalmadı.
      //$data['categories']=Category::inRandomOrder()->get();

      //constructtta tanımladığımız için gerek kalmadı.
      //$data['pages']=Page::orderBy('order','ASC')->get();
      return view('front.homepage',$data);
    }

    public function single($category,$slug){
      $category= Category::whereSlug($category)->first() ?? abort(403, 'Böyle bir kategori bulunamadı.');
      $article=Article::whereSlug($slug)->whereCategoryId($category->id)->first() ?? abort(403, 'Böyle bir yazı bulunamadı.');
      $article->increment('hit');
      $data['article']=$article;
      //constructtta tanımladığımız için gerek kalmadı.
      //$data['categories']=Category::inRandomOrder()->get();
      return view('front.single',$data);
    }

    public function category($slug){
      $category= Category::whereSlug($slug)->first() ?? abort(403, 'Böyle bir kategori bulunamadı.');
      $data['category']=$category;
      $data['articles']=Article::where('category_id', $category->id)
      ->where('status',1)
      ->orderBy('created_at','DESC')->paginate(2);

      //constructtta tanımladığımız için gerek kalmadı.
      //$data['categories']=Category::inRandomOrder()->get();
      return view('front.category',$data);
    }

    public function page($slug){
      $page=Page::whereSlug($slug)->first() ?? abort(403, 'Böyle bir sayfa bulunamadı.');
      $data['page']=$page;
      //constructtta tanımladığımız için gerek kalmadı.
      //$data['pages']=Page::orderBy('order','ASC')->get();
      return view('front.page',$data);
    }

    public function contact(){
      return view('front.contact');
    }

    public function contactpost(Request $request){
      $rules=[
        'name'=>'required|min:5',
        'email'=>'required|email',
        'topic'=>'required',
        'message'=>'required|min:10'
      ];

      $validate=Validator::make($request->all(),$rules);

      if($validate->fails()){
        //print_r($validate->errors());
        return redirect()->route('contact')->withErrors($validate)->withInput();
      }
      //die();
      //Mail::raw([]
      Mail::send([],[]
          ,function($message) use($request){
          $message->from('iletisim@blogsite.com','Blog Sitesi');
          $message->to('burakhayirli1@hotmail.com');
          $message->setBody(' Mesajı gönderen : '.$request->name.'<br/>
                      Mesajı Gönderen Mail : '.$request->email.'<br/>
                      Mesaj Konusu : '.$request->topic.'<br/>
                      Mesaj : '.$request->message.'<br/><br/>
                      Mesaj Gönderilme Tarihi : '.Carbon::now()->toDateString().'','text/html');
          $message->subject($request->name. ' iletişimden mesaj gönderdi');

      });

      $contact=new Contact;
      $contact->name=$request->name;
      $contact->email=$request->email;
      $contact->topic=$request->topic;
      $contact->message=$request->message;
      $contact->save();
      return redirect()->route('contact')->with('success', 'İletişim mesajınız bize iletildi. Teşekkür ederiz.');
      //print_r($request->post());
      //print_r($request->all());

    }

}
