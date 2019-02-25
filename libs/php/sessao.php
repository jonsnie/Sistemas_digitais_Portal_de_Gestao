<?
  session_start();
  if(!isset($_SESSION['auth'])){header("Location: ../../auth/logout.php"); }
?>
