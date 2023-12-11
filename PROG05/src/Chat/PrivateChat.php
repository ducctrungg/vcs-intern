<?php


function get_all_chat($from_id, $to_id)
{
  global $conn;
  connect_db();
  $sql = "SELECT * FROM messages WHERE (messages.message_from_id = '$from_id' AND messages.message_to_id = '$to_id') OR (messages.message_to_id = '$from_id' AND messages.message_from_id = '$to_id')";
  $status = mysqli_query($conn, $sql);
  if ($status) {
    while ($row = mysqli_fetch_assoc($status)) {
      $result[] = $row;
    }
  }
  return $result;
}

function save_chat($from_id, $to_id, $message)
{
  global $conn;
  connect_db();
  $sql = "INSERT INTO messages (message_from_id, message_to_id, message_text) VALUES ('$from_id', '$to_id', '$message')";
  $status = mysqli_query($conn, $sql);
  return $status;
}
?>