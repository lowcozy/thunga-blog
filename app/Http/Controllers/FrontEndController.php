<?php

namespace App\Http\Controllers;
use App\Setting;
use App\Tag;
use App\Post;
use App\User;
use App\Category;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at','desc')->with('category')->get();
        $setting = Setting::first();
        return view('index')->with('title', $setting->site_name)
            ->with('categories', Category::all())
            ->with('posts', $posts)
            ->with('settings', $setting);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

      public function singlePost($slug)
      {
          $setting = Setting::first();
          $post = Post::where('slug', $slug)->first();

          $next_id = Post::where('id', '>', $post->id)->min('id');
          $prev_id = Post::where('id', '<', $post->id)->max('id');

          $post->count++;
          $post->save();
          return view('single')->with('post', $post)
                               ->with('title',$post->title)
                               ->with('settings',$setting)
                               ->with('categories',Category::take(7)->get())
                               ->with('next',Post::find($next_id))
                               ->with('prev',Post::find($prev_id))
                               ->with('site', $setting->site_name);




      }



  public function category($slug)
  {

      $setting = Setting::first();
      $category = Category::where('slug', $slug)->first();
      if (!$category) abort(404);
       return view('category')->with('category', $category)
                              ->with('title',$category->name)
                              ->with('settings',$setting)
                              ->with('site', $setting->site_name)
                              ->with('categories', Category::take(5)->get());
  }


    public function tag($slug)
    {
      $setting = Setting::first();
      $tag = Tag::where('slug', $slug)->first();
      if (!$tag) abort(404);
      return view('tag')->with('tag',$tag)
                        ->with('title', $tag->tag)
                        ->with('settings', $setting)
                        ->with('site', $setting->site_name)
                        ->with('categories', Category::take(5)->get());
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
