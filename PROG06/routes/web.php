<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ChallengesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get("/", function () {
  if (Auth::check()) {
    return Redirect::to("dashboard");
  }
  return view("auth.login");
});

Route::get("dashboard", function () {
  return view('index');
})->middleware(['auth']);

Route::get("/login", [UserController::class, "login"])->name('login');

Route::prefix("users")->group(function () {
  Route::get("/", [UserController::class, "list"]);
  Route::get("/{user}", [UserController::class, "show"]);
  Route::get("/edit/{user}", [UserController::class, "edit"]);
  Route::put("/{user}", [UserController::class, "update"]);
  Route::post("/auth", [UserController::class, "auth"]);
});

Route::prefix("assignment")->group(function () {
  Route::get("/", [AssignmentController::class, "index"]);
  Route::get("/{id}/submission", [AssignmentController::class, "show"]);
  Route::post("/", [AssignmentController::class, "store"]);
  Route::post("/{id}/submission", [AssignmentController::class, "submit"]);
  Route::post("/{id}/submission/grade", [AssignmentController::class, "grade"]);
});

Route::prefix("challenges")->group(function () {
  Route::get("/", [ChallengesController::class, "index"]);
  Route::post("/", [ChallengesController::class, "store"]);
  Route::post("/answer", [ChallengesController::class, "answer"]);
  Route::delete("/{id}", [ChallengesController::class, "delete"]);
});

Route::prefix("teacher")->group(function () {
  Route::get("/create", [TeacherController::class, "create"])->middleware('ensure.teacher');
  Route::post("/create", [TeacherController::class, "store"])->name('students')->middleware('ensure.teacher');
});

Route::get('/chat', [ChatsController::class, 'index']);
Route::get('/messages', [ChatsController::class, 'fetchMessages']);
Route::post('/messages', [ChatsController::class, 'sendMessage']);


