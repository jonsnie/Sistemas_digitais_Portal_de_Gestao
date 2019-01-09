<?
session_start();
require_once("../libs/php/conn.php");
require_once("../libs/php/funcoes.php");

/*
echo "<div class='text-center'>";
  echo "Pagina de upload de foto...<br>POST:<br>";
  print_r($_POST);
  echo "<hr>FILES:<br><pre>";
  print_r($_FILES['userImage']);
  echo "</pre><hr>Envio:<br>";
  echo "</div>";
exit();
*/
if($_POST['acao']=="inserir")
{
  if(!empty($_FILES['userImage']['tmp_name']) && $_FILES['userImage']['tmp_name'] != 'none'){
    $imagem   = $_FILES['userImage']['tmp_name'];
    $tamanho  = $_FILES['userImage']['size'];
    $tipo     = $_FILES['userImage']['type'];
    $nome     = $_FILES['userImage']['name'];
    $fp       = fopen($imagem, "rb");
    $conteudo = fread($fp, $tamanho);
    $conteudo = addslashes($conteudo);
    fclose($fp);

    $sql = "UPDATE usuarios SET foto = '".$conteudo."', foto_header='".$tipo."' WHERE id = '".$_POST['id']."'";
    mysql_query($sql)or die(mysql_error());
  }
}elseif($_POST['acao']=="remover")
{
    $sql = "UPDATE usuarios SET foto = null , foto_header = null WHERE id = '".$_POST['id']."'";
    mysql_query($sql)or die(mysql_error());
}
  header("Location: FORM.php?id=".$_POST['id']);
?>