<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir")
    {
      if($id_vehicle != "") { $id_vehicle  = "'".$id_vehicle."'";  }else{ $id_vehicle  = "Null"; }
      if($description != ""){ $description = "'".$description."'"; }else{ $description = "Null"; }

      $sql = "INSERT INTO sepud.oct_victim
                          (name,
                           id_events,
                           age,
                           description,
                           genre,
                           state,
                           id_vehicle)
                  VALUES ('".$name."',
                          '".$id."',
                          '".$age."',
                          ".$description.",
                          '".$genre."',
                          '".$state."',
                          ".$id_vehicle.")";
      pg_query($sql)or die("Erro ".__LINE__);
      header("Location: FORM.php?id=".$id);
      exit();
    }
?>
