<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Query\JoinClause;

class AssignmentController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }
  public function index()
  {
    $listAssignment = Assignment::all();
    return view("assignment.index", ["lists" => $listAssignment]);
  }

  public function store(Request $request)
  {
    $formData = $request->validate([
      'title' => ['required', 'string'],
      'assignment' => ['required', 'mimes:pdf,doc,docx', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
      'description' => ['nullable', 'string'],
    ]);
    if ($request->hasFile('assignment')) {
      $formData['assignment'] = $request->file('assignment')->store('assignment', 'public');
    }
    Assignment::create($formData);
    Session::flash('message', "Tạo bài tập thành công");
    Session::flash('alert-class', 'alert-success');
    return redirect()->back();
  }

  public function show(int $id)
  {
    $assignment = Assignment::find($id);
    if (Auth::user()->role == 'teacher') {
      $list_submission = DB::table('assignment')
        ->join('submission', function (JoinClause $join) use ($id) {
          $join->on('submission.assignment_id', '=', 'assignment.id')
            ->where('assignment.id', '=', $id);
        })
        ->join('users', 'users.id', '=', 'submission.user_id')
        ->select('submission.*', 'users.fullname')
        ->get();
      return view('assignment.view', ['list_submission' => $list_submission]);
    } else {
      $status = DB::table('submission')->where('assignment_id', '=', $id)->where('user_id', '=', Auth::user()->id)->get();
      return view('assignment.view', [
        'assignment' => $assignment,
        'status' => $status
      ]);
    }
  }

  public function submit(Request $request, int $id)
  {
    $formData = $request->validate([
      'submission' => ['required', 'mimes:pdf,doc,docx', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
      'description' => ['nullable', 'string'],
    ]);
    if ($request->hasFile('submission')) {
      $formData['submission'] = $request->file('submission')->store('submission', 'public');
    }
    Submission::updateOrCreate(
      ['assignment_id' => $request->route('id'), 'user_id' => $request->user()->id],
      $formData
    );
    Session::flash('message', "Nộp bài thành công");
    Session::flash('alert-class', 'alert-success');
    return redirect()->back();
  }

  public function grade(Request $request)
  {
    dd($request);
    return;
  }
}
