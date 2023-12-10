<?php

global $conn;
function connect_db()
{
  global $conn;
  // Connect database
  $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (mysqli_connect_error()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
  }
}

function status_db()
{
  global $conn;
  if (mysqli_ping($conn)) {
    printf("Connection success\n");
  } else {
    printf("Error: %s\n", mysqli_error($conn));
  }
}

function disconnect_db()
{
  global $conn;
  if ($conn) {
    mysqli_close($conn);
  }
}

function update_db(string $table, array $params, string $id): bool
{
  global $conn;
  connect_db();
  $set = array();
  foreach ($params as $key => $value) {
    $set[] = "{$key} ='" . $value . "'";
  }
  $sql = "UPDATE {$table} SET " . implode(', ', $set) . " WHERE ID = '$id'";
  $result = mysqli_query($conn, $sql);
  return $result;
}

function get_all_table_db(string $table)
{
  global $conn;
  connect_db();
  $sql = "select * from $table";
  $result = mysqli_query($conn, $sql);
  return $result;
}

function get_detail_user(string $table, string $username)
{
  global $conn;
  connect_db();
  $sql = "select * from $table where username = '$username'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  return $row;
}

function get_detail_from_id(string $table, int $id)
{
  global $conn;
  connect_db();
  $sql = "select * from $table where id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
  return $row;
}

function add_data_db(string $table, array $inputs)
{
  global $conn;
  connect_db();
  foreach ($inputs as $key => $value) {
    $col[] = $key;
    $data[] = $value;
  }
  $sql = "INSERT INTO {$table} (" . implode(', ', $col) . ") VALUES ('" . implode("', '", $data) . "')";
  $result = mysqli_query($conn, $sql);
  return $result;
}

function get_detail_column_id(string $table, string $column, int $id)
{
  global $conn;
  connect_db();
  $sql = "select * from $table where $column = '$id'";
  $result = mysqli_query($conn, $sql);
  return $result;
}
function have_row(string $table)
{
  $result = get_all_table_db($table);
  if ($result) {
    if (mysqli_fetch_array($result) > 0) {
      return true;
    }
    return false;
  }
  return false;
}

function delete_row_db(string $table, string $column, int $id)
{
  global $conn;
  connect_db();
  $sql = "DELETE FROM challenges where $column='$id'";
  $result = mysqli_query($conn, $sql);
  if (mysqli_affected_rows($conn) > 0) {
    return true;
  }
  return false;
}

// function getStatusCodeMeeage($status)
// {
//   $codes = array(
//     100 => 'Continue',
//     101 => 'Switching Protocols',
//     200 => 'OK',
//     201 => 'Created',
//     202 => 'Accepted',
//     203 => 'Non-Authoritative Information',
//     204 => 'No Content',
//     205 => 'Reset Content',
//     206 => 'Partial Content',
//     300 => 'Multiple Choices',
//     301 => 'Moved Permanently',
//     302 => 'Found',
//     303 => 'See Other',
//     304 => 'Not Modified',
//     305 => 'Use Proxy',
//     306 => '(Unused)',
//     307 => 'Temporary Redirect',
//     400 => 'Bad Request',
//     401 => 'Unauthorized',
//     402 => 'Payment Required',
//     403 => 'Forbidden',
//     404 => 'Not Found',
//     405 => 'Method Not Allowed',
//     406 => 'Not Acceptable',
//     407 => 'Proxy Authentication Required',
//     408 => 'Request Timeout',
//     409 => 'Conflict',
//     410 => 'Gone',
//     411 => 'Length Required',
//     412 => 'Precondition Failed',
//     413 => 'Request Entity Too Large',
//     414 => 'Request-URI Too Long',
//     415 => 'Unsupported Media Type',
//     416 => 'Requested Range Not Satisfiable',
//     417 => 'Expectation Failed',
//     500 => 'Internal Server Error',
//     501 => 'Not Implemented',
//     502 => 'Bad Gateway',
//     503 => 'Service Unavailable',
//     504 => 'Gateway Timeout',
//     505 => 'HTTP Version Not Supported'
//   );
//   return isset($codes[$status]) ? $codes[$status] : '';
// }

// function sendResponse($status = 200, $body = "", $content_type = 'text/html')
// {
//   $status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMeeage($status);
//   header($status_header);
//   header('Content-type: ' . $content_type);
//   echo $body;
// }