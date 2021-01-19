<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Support\Facades\File;
class PageController extends Controller
{
    public function index(){
      $pages=Page::all();
      return view('back.pages.index',compact('pages'));
    }

    public function orders(Request $request){
      foreach ($request->get('page')  as $key=>$order) {
        Page::where('id',$order)->update(['order'=>$key]);



      }


    }
    public function create(){
      return view('back.pages.create');
    }

    public function update($id){
      $page=Page::findOrFail($id);
      return view('back.pages.update',compact('page'));
    }

    public function updatePost(Request $request, $id)
    {
        //return "geldi";
        $this->validate($request, [
        'title'=>'min:3',
        'image'=>'image|mimes:jpeg,png,jpg|max:2048',
      ]);
        /* Çalışmadı. eski versiyon
        $request->validate([
          'title'=>'min:3',
          'image'=>'required|image|mimes:jpeg,png,jpg|max:100',

        ]);
*/
        $page=Page::findOrFail($id);

        $page->title=$request->title;
        $page->content=$request->content;
        $page->slug=str_slug($request->title);

        if($request->hasFile('image')){
          $imageName=str_slug($request->title).'.'.$request->image->getClientOriginalExtension();
          //dd($imageName);
          $request->image->move(public_path('uploads'),$imageName);
          $page->image='/uploads/'.$imageName;
        //  return "geldi"; die;
        }
        $page->save();

        return redirect()->route('admin.pages.index');
    }


    public function switch(Request $request){
      $page=Page::findOrFail($request->id);
      $page->status=$request->status=="true" ? 1 : 0;
      $page->save();
    }

    public function delete($id) {
      $page=Page::find($id);
      if(File::exists(str_replace('\\','/',str_replace('\/','/',public_path($page->image))))){
        //echo "girdi";
        File::delete(str_replace('\\','/',str_replace('\/','/',public_path($page->image))));
      }
      //return str_replace('\\','/',str_replace('\/','/',public_path($article->image)));
      //die;
      $page->delete();
      return redirect()->route('admin.pages.index');
    }

    public function post(Request $request)
    {
        //insert
        //dd($request->all());
        $this->validate($request, [
        'title'=>'min:3',
        'image'=>'required|image|mimes:jpeg,png,jpg|max:2048', ]);
        /* Çalışmadı. eski versiyon
        $request->validate([
          'title'=>'min:3',
          'image'=>'required|image|mimes:jpeg,png,jpg|max:100',

        ]);
*/
        $lastPage=Page::orderBy('order','desc')->first();
        $page=new Page;

        $page->title=$request->title;
        $page->content=$request->content;
        $page->order=$lastPage->order+1;
        $page->slug=str_slug($request->title);

        if($request->hasFile('image')){
          $imageName=str_slug($request->title).'.'.$request->image->getClientOriginalExtension();
          //dd($imageName);
          $request->image->move(public_path('uploads'),$imageName);
          $page->image='/uploads/'.$imageName;
        //  return "geldi"; die;
        }
        $page->save();
        return redirect()->route('admin.pages.index');
        //return "gelmedi"; die;
    }



}
