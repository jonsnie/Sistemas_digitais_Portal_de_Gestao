<?
error_reporting(0);
session_start();

require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

logger("Logout",$_SESSION['origem']);

session_destroy();
session_start();
$msg = array("tipo" => "success",  "titulo" => "Logout", "texto" => "Saída do sistema com sucesso.");
$_SESSION['system_messages'] = $msg;

header("Location: ../index.php");
?>
