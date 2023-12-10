<?php

require_once 'bootstrap.php';

function login(string $username, string $password): bool
{
  $user = get_detail_user("user", $username);
  // if user found, check the password
  if ($user && password_verify($password, $user['password'])) {
    // prevent session fixation attack
    session_regenerate_id();
    // set username in the session
    $_SESSION['username'] = $user['username'];
    $_SESSION['id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['avatar'] = $user['avatar'];
    return true;
  }
  return false;
}

function is_user_logged_in(): bool
{
  return isset($_SESSION['username']);
}

function require_login(): void
{
  if (!is_user_logged_in()) {
    redirect_to('/public/login.php');
  }
}
?>