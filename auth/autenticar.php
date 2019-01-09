<?php
session_start();
require_once("../libs/php/configs.php");
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

extract($_POST);
$_SESSION['auth'] = "false";

if(isset($username) && isset($password)){
	$res = pg_prepare($conn_neogrid, "qry1", "SELECT * FROM sepud.users WHERE email = $1 AND password = md5($2)");
	$res = pg_execute($conn_neogrid, "qry1", array($username,$password));
	if(pg_num_rows($res)==1)
	{
		$d = pg_fetch_assoc($res);
		if($d['active'] == 't' && $d['in_ativaction'] == 'f')
		{
			$_SESSION = $d;	
			$_SESSION['auth'] = "true";

		}
		if($d['active'] == 'f')				{ $_SESSION['error'] = "Este usuário não esta ativo no sistema.";}
		if($d['in_ativaction'] == 't'){ $_SESSION['error'] = "Aguardando liberação de acesso.";}
	}else{ $_SESSION['error'] = "E-mail ou senha podem estar errados.";}
}else{   $_SESSION['error'] = "Usuário ou senha não podem estar em branco.";}

/*
print_r_pre($_SESSION);
print_r_pre($d);
exit();
*/

if($_SESSION['auth']=="true"){ header("Location: ../index_sistema.php"); }
else 								 			   { header("Location: ../index.php");         }





?>
