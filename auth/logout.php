<?
error_reporting(0);
//require_once("../libs/php/conn.php");



  session_destroy();
  session_start();
  $msg = array("tipo" => "success",  "titulo" => "Logout", "texto" => "Saída do sistema com sucesso.");
  $_SESSION['system_messages'] = $msg;

  header("Location: ../index.php");
?>
