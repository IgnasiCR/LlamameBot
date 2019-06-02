<?php

define('token', '');

include_once 'funciones.php';
include_once 'conexion.php';
include_once 'variables.php';

// CON EL EXPLODE TOMAMOS EL PRIMER VALOR DEL MENSAJE ASÍ VEMOS SI ESTÁ USANDO EL COMANDO O NO.
$arr = explode(' ',trim($message));
$command = $arr[0];

$message = substr(strstr($message," "), 1);

if($chatType == 'group' || $chatType == 'supergroup'){

switch ($command) {

  case '/llamame': case '/llamame@LlamameBot':

    $consulta = "SELECT * FROM `llamame` WHERE idGrupo='$chatId' AND nombreUsuario='$firstname';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos)==0){

      $consulta = "INSERT INTO `llamame` VALUES ('$chatId', '$firstname', '$chatName');";
      mysqli_query($conexion,$consulta);
      $response = "De acuerdo $firstname, ya has sido insertad@ en la lista de llamamientos.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);

    }else{

      $response = "$firstname ya te encuentras la lista de llamamientos de este grupo.";
      deleteMessage($chatId, $messageId);
      sendMessage($userId, $response, FALSE);

    }

  mysqli_close($conexion);
  exit();
  break;

  case '/nomellames': case '/nomellames@LlamameBot':

    $consulta = "SELECT * FROM `llamame` WHERE idGrupo='$chatId' AND nombreUsuario='$firstname';";
    $datos=mysqli_query($conexion,$consulta);

    if(mysqli_num_rows($datos)>0){

      $consulta = "DELETE FROM `llamame` WHERE idGrupo='$chatId' AND nombreUsuario='$firstname';";
      mysqli_query($conexion,$consulta);
      $response = "De acuerdo $firstname, ya has sido eliminad@ de la lista de llamamientos.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);

    }else{

      $response = "$firstname no te habías registrado todavía en la lista de este grupo.";
      deleteMessage($chatId, $messageId);
      sendMessage($userId, $response, FALSE);

    }

  mysqli_close($conexion);
  exit();
  break;

  case '/llamar': case '/llamar@LlamameBot':

  $consulta="SELECT * FROM `llamame` WHERE idGrupo='$chatId';";
  $datos=mysqli_query($conexion,$consulta);

  if(mysqli_num_rows($datos)>0){

    $contador = 1;

    while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

      $nombre = $fila['nombreUsuario'];
      $response .= "$nombre ";

      if($contador == 10){
        sendMessage($chatId, $response, FALSE);
        $contador = 0;
        $response = "";
      }

      $contador++;

    }

    sendMessage($chatId, $response, FALSE);

  }else{
    $response = "No hay nadie en la lista de llamamientos.";
    sendDeleteMessage($chatId, $messageId, $response, FALSE);
  }

  mysqli_close($conexion);
  exit();
  break;

}

}else{
  switch ($command){

    case '/llamame': case '/llamame@LlamameBot':
      $response = "Esta funcionalidad tan solo está permitida en grupos y supergrupos. Utilízala para apuntarte a la lista de llamamientos.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);
    break;

    case '/llamar': case '/llamar@LlamameBot':
      $response = "Esta funcionalidad tan solo está permitida en grupos y supergrupos. Utilízala para llamar a todas las personas que se han apuntado a la lista de llamamientos.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);
    break;

    case '/nomellames': case '/nomellames@LlamameBot':

      $consulta="SELECT * FROM `llamame` WHERE nombreUsuario='$firstname';";
      $datos=mysqli_query($conexion,$consulta);

      if(mysqli_num_rows($datos)>0){

        while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

          $nombre = $fila['nombreChat'];
          $idGrupo = $fila['idGrupo'];

          $arr = str_split($nombre);
          $len = sizeof($arr);
          for($i=0; $i<$len; $i = $i+1){
            if($arr[$i] == "&"){
              $arr[$i] = "y";
            }
          }

          $nombre = implode("",$arr);

          $but[] = array(array("text" => "$nombre", "callback_data" => "/nomellamesB $idGrupo"),);

        }

        inlineKeyboard($but, $userId, "Elige el grupo del cuál no quieres que te llamen más.");

      }else{

        $response = "$firstname no te has registrado todavía en ninguna lista.";
        deleteMessage($chatId, $messageId);
        sendMessage($userId, $response, FALSE);

      }

    break;

    case '/github': case '/github@LlamameBot':
      	$response = "$firstname mi GitHub es <a href='https://github.com/IgnasiCR'>IgnasiCR</a>";
        sendDeleteMessage($chatId, $messageId, $response, FALSE);
    break;
  }
}

if(callback($update)){

  $arr = explode(' ',trim($callbackData));
  $command = $arr[0];

  $message = substr(strstr($callbackData," "), 1);

  switch($command){

    case '/nomellamesB':

      $consulta = "DELETE FROM `llamame` WHERE idGrupo='$message' AND nombreUsuario='$callbackName';";
      mysqli_query($conexion,$consulta);
      $response = "De acuerdo $firstname, ya has sido eliminad@ de la lista de llamamientos.";
      sendMessage($callbackId, $response, FALSE);

    exit();
    break;

  }
}

?>
