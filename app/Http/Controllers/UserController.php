<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return view ('admin.user.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User();

        return view ('admin.user.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = User::create($data);

        return Response::json(['user' => $user], 201)->header('Location', route('user.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($userid)
    {
        $user= User::find(intval($userid));
        return Response::json(['user'=>$user], 200)->header('Location', route('user.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view ('admin.user.edit', compact('user'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userid)
    {
        
        $user= User::find(intval($userid));
        //dd($user);
        $data = $request->all();
        //dd($data);
        $user->update($data);

        return Response::json(['user'=>$user], 201)->header('Location', route('user.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($userid)
    {
        $user = User::find(intval($userid));

        $user->delete();
        
        return Response::json([], 204)->header('Location', route('user.index'));
    }
}
