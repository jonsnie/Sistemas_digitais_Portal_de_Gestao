<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir" && $name != "")
    {
      if($id_vehicle != "") { $id_vehicle  = "'".$id_vehicle."'";  }else{ $id_vehicle  = "Null"; }
      if($description != ""){ $description = "'".$description."'"; }else{ $description = "Null"; }
      if($age != "")        { $age         = "'".$age."'";         }else{ $age         = "Null"; }

      $sql = "INSERT INTO sepud.oct_victim
                          (name,
                           id_events,
                           age,
                           description,
                           genre,
                           state,
                           id_vehicle,
                           position_in_vehicle,
                           refuse_help)
                  VALUES ('".$name."',
                          '".$id."',
                          ".$age.",
                          ".$description.",
                          '".$genre."',
                          '".$state."',
                          ".$id_vehicle.",
                          '".$position_in_vehicle."',
                          '".$refuse_help."')";
      pg_query($sql)or die("Erro ".__LINE__);

      if($retorno_acao == "continuar"){ header("Location: FORM_vitima.php?id=".$id);}
      else                            { header("Location: FORM.php?id=".$id);       }


      exit();
    }

    if($acao == "atualizar" && $name != "")
    {
      if($id_vehicle != "") { $id_vehicle  = "'".$id_vehicle."'";  }else{ $id_vehicle  = "Null"; }
      if($description != ""){ $description = "'".$description."'"; }else{ $description = "Null"; }
      if($age != "")        { $age         = "'".$age."'";         }else{ $age         = "Null"; }

      $sql = "UPDATE sepud.oct_victim SET
                     name                = '".$name."',
                     age                 = ".$age.",
                     description         = ".$description.",
                     genre               = '".$genre."',
                     state               = '".$state."',
                     id_vehicle          = ".$id_vehicle.",
                     position_in_vehicle = '".$position_in_vehicle."',
                     refuse_help         = '".$refuse_help."'
              WHERE id = '".$victim_sel."'";
      pg_query($sql)or die("Erro ".__LINE__);

      if($retorno_acao == "continuar"){ header("Location: FORM_vitima.php?id=".$id);}
      else                            { header("Location: FORM.php?id=".$id);       }
      exit();
    }

    extract($_GET);
    if($acao == "remover")
    {
      echo $sql = "DELETE FROM sepud.oct_victim WHERE id = '".$victim_sel."'";
      pg_query($sql)or die("Erro ".__LINE__);
      header("Location: FORM_vitima.php?id=".$id);
      exit();
    }
?>
