<?php
session_start();
require_once("../libs/php/configs.php");
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

extract($_POST);
$_SESSION['auth'] = "false";

if(isset($username) && isset($password)){
	$res = pg_prepare($conn_neogrid, "qry1", "SELECT U.id, U.name, U.area, U.job, U.active, U.in_ativaction, U.phone, U.cpf, U.date_of_birth, C.name as company_name, C.acron as company_acron, C.id as id_company FROM sepud.users U JOIN sepud.company C ON C.id = U.id_company WHERE U.email = $1 AND U.password = md5($2)");
	$res = pg_execute($conn_neogrid, "qry1", array($username,$password));
	if(pg_num_rows($res)==1)
	{
		$d = pg_fetch_assoc($res);
		if($d['active'] == 't' && $d['in_ativaction'] == 'f')
		{
			$_SESSION 				  = $d;
			$_SESSION['auth']   = "true";
			$_SESSION['origem'] = $_POST['modulo'];
			logger("Login",$_POST['modulo']);

		}
		if($d['active'] == 'f')				{ $_SESSION['error'] = "Este usuário não esta ativo no sistema.";}
		if($d['in_ativaction'] == 't'){ $_SESSION['error'] = "Aguardando liberação de acesso.";}
	}else{ $_SESSION['error'] = "E-mail ou senha podem estar errados.";}
}else{   $_SESSION['error'] = "Usuário ou senha não podem estar em branco.";}


if($_SESSION['auth']=="true"){
		if($modulo == "ERG"){
			header("Location: ../index_erg.php?modulo=".$modulo);
		}else{
			header("Location: ../index_sistema.php?modulo=".$modulo);
		}
}else{
		header("Location: ../index.php");
}

?>
