<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

require_once __DIR__ . '/bootstrap.php';
class Chat implements MessageComponentInterface
{
  protected $clients;

  public function __construct()
  {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn)
  {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);
    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    $numRecv = count($this->clients) - 1;
    echo sprintf(
      'Connection %d sending message "%s" to %d other connection%s' . "\n",
      $from->resourceId,
      $msg,
      $numRecv,
      $numRecv == 1 ? '' : 's'
    );
    $data = json_decode($msg, true);
    echo sprintf(
      "%s", var_dump($data),
    );
    $status = save_chat($data['from_id'], $data['to_id'], $data['message']);
    $receive_user_data = get_detail_from_id('user', $data['to_id']);
    foreach ($this->clients as $client) {
      if ($from == $client) {
        $data['from'] = 'Me';
      } else {
        $receive_user_data = get_detail_from_id('user', $data['to_id']);
        $data['from'] = $receive_user_data['full_name'];
      }
      $client->send(json_encode($data));
    }
  }

  public function onClose(ConnectionInterface $conn)
  {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);
    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
  }
}

function get_all_chat($from_id, $to_id)
{
  global $conn;
  connect_db();
  $sql = "SELECT * FROM messages WHERE (messages.message_from_id == $from_id AND messages.message_to_id == $to_id) OR (messages.message_to_id == $from_id AND messages.message_from_id == $to_id)";
  $status = mysqli_query($conn, $sql);
  return $status;
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