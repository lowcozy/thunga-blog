<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Setting;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class FrontEndController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function getSetting()
    {
        if (Cache::has('settings')) {
            return Cache::get('settings');
        } else {
            Cache::put('settings', Setting::first(), 2628000);
            return Cache::get('settings');
        }
    }

    public function getCate()
    {
        if (!Cache::has('cate')) {
            Cache::put('cate', Category::all(), 2628000);
        }
        return Cache::get('cate');
    }

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->with('category')->get();
        $setting = $this->getSetting();
        return view('index')->with('title', $setting->site_name)
            ->with('categories', $this->getCate())
            ->with('posts', $posts)
            ->with('settings', $setting);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function singlePost($slug)
    {
        $setting = $this->getSetting();
        $post = Post::where('slug', $slug)->first();

        if(!$post) abort('404');

        $next_id = Post::where('id', '>', $post->id)->min('id');
        $prev_id = Post::where('id', '<', $post->id)->max('id');

        $post->count++;
        $post->save();
        return view('single')->with('post', $post)
            ->with('title', $post->title)
            ->with('settings', $setting)
            ->with('categories', $this->getCate())
            ->with('next', Post::find($next_id))
            ->with('prev', Post::find($prev_id))
            ->with('site', $setting->site_name);


    }


    public function category($slug)
    {

        $setting = $this->getSetting();
        $category = Category::where('slug', $slug)->first();
        if (!$category) abort(404);
        return view('category')->with('category', $category)
            ->with('title', $category->name)
            ->with('settings', $setting)
            ->with('site', $setting->site_name)
            ->with('categories', $this->getCate());
    }


    public function tag($slug)
    {
        $setting = $this->getSetting();
        $tag = Tag::where('slug', $slug)->first();
        if (!$tag) abort(404);
        return view('tag')->with('tag', $tag)
            ->with('title', $tag->tag)
            ->with('settings', $setting)
            ->with('site', $setting->site_name)
            ->with('categories', $this->getCate());
    }


    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
