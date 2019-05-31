<?php

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

      $consulta = "INSERT INTO `llamame` VALUES ('$chatId', '$firstname');";
      mysqli_query($conexion,$consulta);
      $response = "De acuerdo $firstname, ya has sido insertad@ en la lista de llamamientos.";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);

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

    }

  mysqli_close($conexion);
  exit();
  break;

  case '/llamar': case '/llamar@LlamameBot':

  $consulta="SELECT * FROM `llamame` WHERE idGrupo='$chatId';";
  $datos=mysqli_query($conexion,$consulta);

  if(mysqli_num_rows($datos)>0){

    $contador = 0;

    while($fila=mysqli_fetch_array($datos,MYSQLI_ASSOC)){

      $nombre = $fila['nombreUsuario'];
      $response .= "$nombre ";

      if($contador == 10){
        sendMessage($chatId, $response, FALSE);
        $contador = 0;
      }

    }

    sendMessage($chatId, $response, FALSE);

  }else{
    $response = "No hay nadie en la lista de llamamientos.";
    sendDeleteMessage($chatId, $messageId, $response, FALSE);
  }

  mysqli_close($conexion);
  exit();
  break;

  case '/github': case '/github@Ignasi_Bot':
    	$response = "$firstname mi GitHub es <a href='https://github.com/IgnasiCR'>IgnasiCR</a>";
      sendDeleteMessage($chatId, $messageId, $response, FALSE);
    break;

}

}

?>
