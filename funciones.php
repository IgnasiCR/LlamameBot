<?php

define('api', 'https://api.telegram.org/bot'.token.'/');

$website = "https://api.telegram.org/bot".$botToken;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$callbackId = $update["callback_query"]["from"]["id"];
$callbackName = $update["callback_query"]["from"]["username"];
$callbackData = $update["callback_query"]["data"];

if ($callbackName=="") {
    $modo=1;
    $callbackName = $update["callback_query"]["message"]["from"]["first_name"];
}else{
    $callbackName = "@".$callbackName;
}

function callback($up){
  return $up["callback_query"];
}

function sendDeleteMessage($chatId, $messageId, $response, $links){
  sendMessage($chatId, $response, $links);
  deleteMessage($chatId, $messageId);
}

function sendMessage($chatId, $response, $links){
    if($links){
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true&disable_web_page_preview=true';
    }else{
        $url = $GLOBALS[website].'/sendMessage?chat_id='.$chatId.'&parse_mode=HTML&text='.urlencode($response).'&disable_notification=true';
    }
    file_get_contents($url);
}

function deleteMessage($chatId, $messageId){
   $url = $GLOBALS[website].'/deleteMessage?chat_id='.$chatId.'&message_id='.$messageId;
   file_get_contents($url);
}

function sendPhoto($chatId,$urlphoto,$response){
  if($response == ""){
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&disable_notification=true';
  }else{
    $url = $GLOBALS[website].'/sendPhoto?chat_id='.$chatId.'&photo='.$urlphoto.'&caption='.$response.'&disable_notification=true';
  }
  file_get_contents($url);
}

function sendSticker($chatId, $urlsticker){
  $url = $GLOBALS[website].'/sendSticker?chat_id='.$chatId.'&sticker='.$urlsticker.'&disable_notification=true';
  file_get_contents($url);
}

function apiRequest($metodo){
    $req = file_get_contents(api.$metodo);
    return $req;
}

function inlineKeyboard($menud, $chat, $text){

  $menu = $menud;

  if(strpos($text, "\n")){
    $text = urlencode($text);
  }

  $d2 = array("inline_keyboard" => $menu, );

  $d2 = json_encode($d2);

  return apiRequest("sendMessage?chat_id=$chat&parse_mode=Markdown&text=$text&reply_markup=$d2");

}

 ?>
