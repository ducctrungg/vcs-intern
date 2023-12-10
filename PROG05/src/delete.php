<?php

require_once __DIR__ . "/bootstrap.php";

global $conn;
connect_db();
if (is_post_request()) {
  $result = delete_row_db('challenges', 'id', $_POST['delete']);
  if ($result) {
    redirect_to ('challenges.php');
  }
}

?>