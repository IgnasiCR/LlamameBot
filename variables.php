<?php

$website = "https://api.telegram.org/bot".token;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);
$modo = 0;

$chatId = $update["message"]["chat"]["id"];
$messageId = $update["message"]["message_id"];
$chatType = $update["message"]["chat"]["type"];
$chatName = $update["message"]["chat"]["title"];
$userId = $update["message"]['from']['id'];
$firstname = $update["message"]['from']['username'];

if ($firstname=="") {
    $modo=1;
    $firstname = $update["message"]['from']['first_name'];
}else{
    $firstname = "@".$firstname;
}

$message = $update["message"]["text"];

 ?>
