<?php
session_start();
require_once("../libs/php/conn.php");
$sql = "SELECT foto, foto_header FROM usuarios WHERE id = '".$_GET['id']."'";
$res = mysql_query($sql)or die(mysql_error());
$img = mysql_fetch_object($res);
Header( "Content-type: ".$img->foto_header);
echo $img->foto;

?>
