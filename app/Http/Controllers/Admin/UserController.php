<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchQuery = null;
        if($request->has('query')) {
            $searchQuery = $request->get('query');
        }
        $users = User::withCount('properties')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('email', 'like', '%' . $searchQuery . '%');
            })
            ->orderBy("id","DESC")->paginate(50);
        return view('admin.users.index',compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
//        $user->fill($request->only('credits'));
        $user->credits += (int) $request->credits;
        $pass = $request->password;
        if(!is_null($pass) && !empty($pass))
            $user->password = \Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.users.index')->with('success','Usuario editado');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(auth()->user()->type === 'admin') {
             $user->delete();
             return redirect()->back()->with('success', 'El usuario fue eliminado correctamente');
        }
        return redirect()->back()->withErrors('No se pudo borrar el usuario');
    }
}
