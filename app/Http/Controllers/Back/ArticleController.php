<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Carbon::setLocale('tr');
       $articles=Article::orderBy('created_at','ASC')->get();
        return view('back.articles.index',compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //create view
        $categories=Category::all();
        return view('back.articles.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $article=new Article;

        $article->title=$request->title;
        $article->category_id=$request->category;
        $article->content=$request->content;
        $article->slug=str_slug($request->title);

        if($request->hasFile('image')){
          $imageName=str_slug($request->title).'.'.$request->image->getClientOriginalExtension();
          //dd($imageName);
          $request->image->move(public_path('uploads'),$imageName);
          $article->image='/uploads/'.$imageName;
        //  return "geldi"; die;
        }
        $article->save();

        return redirect()->route('admin.makaleler.index');

        //return "gelmedi"; die;

    }


    public function switch(Request $request){
      $article=Article::findOrFail($request->id);
      $article->status=$request->status=="true" ? 1 : 0;
      $article->save();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //return $id.' edit';
        $article=Article::findOrFail($id);
        $categories=Category::all();
        return view('back.articles.update',compact('categories','article'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        $article=Article::findOrFail($id);

        $article->title=$request->title;
        $article->category_id=$request->category;
        $article->content=$request->content;
        $article->slug=str_slug($request->title);

        if($request->hasFile('image')){
          $imageName=str_slug($request->title).'.'.$request->image->getClientOriginalExtension();
          //dd($imageName);
          $request->image->move(public_path('uploads'),$imageName);
          $article->image='/uploads/'.$imageName;
        //  return "geldi"; die;
        }
        $article->save();

        return redirect()->route('admin.makaleler.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete($id) {
      Article::find($id)->delete();
      return redirect()->route('admin.makaleler.index');
    }

    public function trashed()
    {
      $articles=Article::onlyTrashed()->orderBy('deleted_at','ASC')->get();
      return view('back.articles.trashed',compact('articles'));

    }

    public function recover($id){
      Article::onlyTrashed()->find($id)->restore();
      return redirect()->back();
    }

    public function hardDelete($id) {
      $article=Article::onlyTrashed()->find($id);
      if(File::exists(str_replace('\\','/',str_replace('\/','/',public_path($article->image))))){
        //echo "girdi";
        File::delete(str_replace('\\','/',str_replace('\/','/',public_path($article->image))));
      }
      //return str_replace('\\','/',str_replace('\/','/',public_path($article->image)));
      //die;
      $article->forceDelete();
      return redirect()->route('admin.makaleler.index');
    }

    public function destroy($id)
    {
        return $id;
    }
}
