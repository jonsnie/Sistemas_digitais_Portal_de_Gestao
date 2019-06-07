<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
$agora = now();

extract($_POST);

if($active != "" && $type != "" && $street !=  "" && $id != "")
{
  $sql = "UPDATE sepud.eri_parking SET
          active          = ".$active.",
          id_parking_type = '".$type."',
          id_street       = '".$street."',
          description     = '".$description."'
          WHERE id = '".$id."'";
  pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__."<br>".$sql);

}
header("Location: vagas_FORM.php?id=".$id);
?>
