<?php
require_once __DIR__ . "/bootstrap.php";

$inputs = [];
$errors = [];

if (is_post_request()) {

  [$inputs, $errors] = filter($_POST, [
    'username' => 'string | required',
    'password' => 'string | required'
  ]);

  if ($errors) {
    redirect_with('/../public/login.php', ['errors' => $errors, 'inputs' => $inputs]);
  }
  // if login fails
  if (!login($inputs['username'], $inputs['password'])) {
    $errors['login'] = 'Invalid username or password';
    redirect_with('/../public/login.php', [
      'errors' => $errors,
      'inputs' => $inputs
    ]);
  }
  // login successfully
  redirect_to('/../index.php');
} else if (is_get_request()) {
  [$errors, $inputs] = session_flash('errors', 'inputs');
}