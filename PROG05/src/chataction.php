<?php

require_once __DIR__ . '\bootstrap.php';
require_once __DIR__ . '\Chat\PrivateChat.php';

if(isset($_POST["action"]) && $_POST["action"] == 'fetch_chat')
{
	$data = get_all_chat($_POST['to_user_id'], $_POST['from_user_id']);
  header('Content-Type: application/json');
	echo json_encode($data);
}
?>  