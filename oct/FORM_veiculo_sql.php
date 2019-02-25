<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir")
    {
      $sql = "INSERT INTO sepud.oct_vehicles
                          (description,
                           id_events,
                           observation,
                           licence_plate,
                           color,
                           renavam,
                           chassi)
                  VALUES ('".$description."',
                          '".$id."',
                          '".$observation."',
                          '".$licence_plate."',
                          '".$color."',
                          '".$renavam."',
                          '".$chassi."')";
      pg_query($sql)or die("Erro ".__LINE__);

      if($retorno_acao == "continuar"){ header("Location: FORM_veiculo.php?id=".$id); }
      else                            { header("Location: FORM.php?id=".$id);         }
      exit();
    }

    if($acao == "atualizar")
    {
         $sql = "UPDATE sepud.oct_vehicles SET
                             description   = '".$description."',
                             observation   = '".$observation."',
                             licence_plate = '".$licence_plate."',
                             color         = '".$color."',
                             renavam       = '".$renavam."',
                             chassi        = '".$chassi."'
                WHERE  id = '".$veic_sel."'";
       pg_query($sql)or die("Erro ".__LINE__);
       if($retorno_acao == "continuar"){ header("Location: FORM_veiculo.php?id=".$id); }
       else                            { header("Location: FORM.php?id=".$id);         }
       exit();
    }


    extract($_GET);
    if($acao == "remover")
    {
      echo $sql = "DELETE FROM sepud.oct_vehicles WHERE id = '".$veic_sel."'";
      pg_query($sql)or die("Erro ".__LINE__);
      header("Location: FORM_veiculo.php?id=".$id);
      exit();
    }

?>
