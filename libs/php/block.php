<?

//Segurança - Bloqueia se não for carregada via AJAX
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
      $_SESSION['erro'] = 3;
	  header("location: http://".$_SERVER['HTTP_HOST']."/index.php");
	  exit();
}

?>