<?php

namespace App\Http\Controllers;

use App\Profile;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->first();
        return view('admin.users.profile')->with('user', $user)->with('profile', $profile);
    }

    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();


        if ($request->hasfile('avatar')) {


            $avatar = $request->avatar;
            $avatar_new = time() . $avatar->getClientOriginalName();
            $avatar->move('uploads/avatars', $avatar_new);
            $user->profile->avatar = 'uploads/avatars/' . $avatar_new;
            $user->profile->save();
        }

        return response()->json([
            'status' => 200,
            'message' => 'OK',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
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
    public function update(Request $request)
    {


        $this->validate($request, [

            'name' => 'required',
            'email' => 'required|email',
            'facebook' => 'required|url',
            'youtube' => 'required|url'


        ]);

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->profile->facebook = $request->facebook;
        $user->profile->youtube = $request->youtube;
        $user->profile->about = $request->about;


        $user->profile->save();

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        Session::flash('success', 'Profile Updated!');
        return redirect()->back();


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
