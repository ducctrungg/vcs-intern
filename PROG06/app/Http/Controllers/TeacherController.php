<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TeacherController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }
  public function create()
  {
    return view("teacher.create");
  }

  public function store(Request $request)
  {
    $formData = $request->validate([
      'username' => ['bail','required','string','unique:users,username', 'max:255'],
      'fullname' => ['required' ,'string', 'max:255'],
      'phone' => ['numeric', 'digits:10'],
      'email'=> ['email'],
    ]);
    Users::create($formData);
    Session::flash('message', "Thêm học sinh thành công");
    Session::flash('alert-class', 'alert-success');
    return redirect()->back();
  }
}
