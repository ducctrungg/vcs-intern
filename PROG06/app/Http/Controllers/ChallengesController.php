<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class ChallengesController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }
  public function index()
  {
    $challenge = Challenge::first();
    return view("challenges.index", ["challenge" => $challenge]);
  }

  public function store(Request $request)
  {
    $formData = $request->validate([
      'hint' => ["required", 'string'],
      'challenge' => ['required', 'mimes:txt', 'mimetypes:text/plain']
    ]);
    if ($request->hasFile('challenge')) {
      $formData['challenge'] = $request->file('challenge')->storeAs('challenge', $request->file('challenge')->getClientOriginalName(), 'public');
    }
    $formData['teacher_id'] = $request->user()->id;
    Challenge::create($formData);
    Session::flash('message', "Tạo challenge thành công");
    Session::flash('alert-class', 'alert-success');
    return redirect()->back();
  }

  public function delete(int $id)  {
    Challenge::find($id)->delete();
    Session::flash('message', "Xóa challenge thành công");
    Session::flash('alert-class', 'alert-danger');
    return redirect()->back();
  }

  public function answer(Request $request)
  {
    $challenge = Challenge::first();
    $formData = $request->validate([
      'answer'=> ['required', 'string']
    ]);
    if($formData['answer'] == pathinfo($challenge->challenge)['filename']){
      Session::flash('result', "Bạn đã trả lời đúng");
      $content = File::get('storage/' . $challenge->challenge);
      return redirect()->back()->with('content', $content);
    }
    Session::flash('result', "Bạn đã trả lời sai");
    return redirect()->back();
  }
}
