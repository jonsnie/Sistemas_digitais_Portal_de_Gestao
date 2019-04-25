<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");


$agora = now();

if($_GET['acao'] == "fechar" && $_GET['id'] != "")
{
  $sqlU = "UPDATE sepud.oct_workshift SET closed = '".$agora['datatimesrv']."' WHERE id = '".$_GET['id']."'";
  pg_query($sqlU)or die("Erro ".__LINE__."<br>SQL: ".$sqlU);
  header("location: index.php");
  exit();
}



if($_POST['acao'] == "inserir")
{
  extract($_POST);
  $dt_abertura = $opened." ".$opened_hour.":00";
  $dt_abertura = formataData($dt_abertura,4);

  $sql = "INSERT INTO sepud.oct_workshift(
                      opened,
                      id_company,
                      observation,
                      period)
          VALUES ('".$dt_abertura."',
                     $id_company,
                  '".$observation."',
                  '".$period."')RETURNING id";
   $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
   $id  = pg_fetch_assoc($res);
   header("location: turno.php?id=".$id['id']);
   exit();
}


if($_POST['acao'] == "associar")
{
  extract($_POST);
  echo "<pre>";
  print_r($_POST);
  $sql = "";
}

?>
