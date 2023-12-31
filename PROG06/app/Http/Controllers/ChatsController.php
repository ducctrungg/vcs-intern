<?php

namespace App\Http\Controllers;

use App\Models\Users;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    $users = Users::all()->except(Auth::id());
    return view('chat.index', ['users' => $users]);
  }

  public function fetchMessages()
  {
    return Message::with('user')->get();
  }

  public function sendMessage(Request $request)
  {
    $user = Auth::user();
    $message = $user->messages()->create([
      'message' => $request->input('message')
    ]);
  }
}
