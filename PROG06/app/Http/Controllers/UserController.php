<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
  public function __construct()
  {
  }
  public function login()
  {
    return view("auth.login");
  }

  public function show(Users $user)
  {
    // $user = Users::where("id", $id)->first();
    return view("user.profile", ["user" => $user]);
  }
  public function edit(Users $user)
  {
    return view("user.edit", ["user" => $user]);
  }

  public function list()
  {
    $listUser = Users::all();
    return view("user.list", ["users" => $listUser]);
  }

  public function update(Request $request, Users $user)
  {
    try {
      $formData = $request->validate([
        'phone' => ['nullable', 'numeric', 'digits:10'],
        'email' => ['nullable', 'email'],
        'website' => ['nullable', 'url'],
        'description' => ['nullable', 'string'],
        'avatar' => ['nullable', 'image']
      ]);
      if ($request->hasFile('avatar')) {
        $formData['avatar'] = $request->file('avatar')->store('avatars', 'public');
      }
      $user->update($formData);
    } catch (\Exception $e) {
      Session::flash('message', $e->getMessage());
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();
    }
    Session::flash('message', "Update successfully");
    Session::flash('alert-class', 'alert-success');
    return redirect()->back();
  }
  public function auth(Request $request)
  {
    $formData = $request->validate([
      'username' => ['required', 'string', 'max:255'],
      'password' => ['required', 'min:8', 'max:255'],
    ]);

    if (Auth::attempt($formData)) {
      $request->session()->regenerate();
      return redirect()->intended('/');
    }
    return back()->withErrors(['username' => 'Invalid username', 'password' => 'Invalid password']);
  }

  public function test()
  {
    return view('list');
  }
}
