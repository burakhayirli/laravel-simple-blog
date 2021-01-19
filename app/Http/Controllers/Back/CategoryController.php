<?php

namespace App\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Article;

class CategoryController extends Controller
{
    public function index(){
      $categories=Category::all();
      return view('back.categories.index',compact('categories'));
    }

    public function switch(Request $request){
      $category=Category::findOrFail($request->id);
      $category->status=$request->status=="true" ? 1 :0;
      $category->save();

    }

    public function create(Request $request)
    {
      $isSlugExists= Category::whereSlug(str_slug($request->category))->first();
      //$isNameExists= Category::whereName(str_slug($request->category))->first();

      if($isSlugExists)
      {
        //toastr
        return redirect()->back();
      }

      $category=new Category;
      $category->name=$request->category;
      $category->slug=str_slug($request->category);
      $category->save();
      return redirect()->back();
    }

    public function update(Request $request)
    {
      $isSlugExists= Category::whereSlug(str_slug($request->slug))->whereNotIn('id',[$request->id])->first();
      $isNameExists= Category::whereName($request->category)->whereNotIn('id',[$request->id])->first();
      if($isSlugExists or $isNameExists)
      {
        //toastr()->error('kategori mevcut');
        return redirect()->back();
      }
      $category=Category::find($request->id);
      $category->name=$request->category;
      $category->slug=str_slug($request->slug);
      $category->save();
      //toastr()->success("Kategori başarıyla güncellendi");
      return redirect()->back();
    }

      public function delete(Request $request){
        $category = Category::findOrFail($request->id);
        if($category->id==1){
            return redirect()->back();
        }
        $articleCount=$category->articleCount();
        if($articleCount>0){
          Article::where('category_id',$category->id)->update(['category_id'=>1]);
          $defultCategory=Category::find(1);
        }
        $category->delete();

        return redirect()->back();
      }


    public function getData(Request $request){
        $category=Category::findOrFail($request->id);
        return response()->json($category);
    }



}
