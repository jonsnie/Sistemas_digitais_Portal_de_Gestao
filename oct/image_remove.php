<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  extract($_GET);

  echo "Imagem ID: ".$id."<br>Arquivo: ".$arq;
  $arq = str_replace("oct/","",$arq);

  logger("Remoção","OCT - imagens", "Ocorrência n.".$id_oc.", Imagem: ".$arq);

  if(file_exists($arq))
  {
    echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Arquivo encontrado.</span>";
    if(unlink($arq))
    {
      echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Arquivo físico removido com sucesso.</span>";
      $sql = "DELETE FROM sepud.oct_rel_events_images WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__);
      echo "<br><span class='text-success'><b><i class='fa fa-check'></i></b> Registro no banco de dados removido com sucesso.</span>";
    }else {
      echo "<br><span class='text-danger'><b><i class='fa fa-times'></i></b> Arquivo físico NÃO removido.</span>";
    }
  }else
  {
    echo "<br><span class='text-danger'><b><i class='fa fa-times'></i></b> Arquivo não encontrado.</span>";
  }

?>
