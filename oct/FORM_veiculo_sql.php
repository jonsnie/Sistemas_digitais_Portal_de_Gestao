<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir")
    {

      $id_vehicle_type = ($tipo_veiculo == "" ? "Null" : "'".$tipo_veiculo."'");


      $sql = "INSERT INTO sepud.oct_vehicles
                          (description,
                           id_events,
                           observation,
                           licence_plate,
                           color,
                           renavam,
                           chassi,
                           id_vehicle_type)
                  VALUES ('".$description."',
                          '".$id."',
                          '".$observation."',
                          '".$licence_plate."',
                          '".$color."',
                          '".$renavam."',
                          '".$chassi."',
                           ".$id_vehicle_type.")";
      pg_query($sql)or die("Erro ".__LINE__);

      logger("Inserção","OCT - Veículo", "Ocorrência n.".$id);

      if($retorno_acao == "continuar"){ header("Location: FORM_veiculo.php?id=".$id); }
      else                            { header("Location: FORM.php?id=".$id);         }
      exit();
    }

    if($acao == "atualizar")
    {
         $id_vehicle_type = ($tipo_veiculo == "" ? "Null" : "'".$tipo_veiculo."'");
         $sql = "UPDATE sepud.oct_vehicles SET
                             description   = '".$description."',
                             observation   = '".$observation."',
                             licence_plate = '".$licence_plate."',
                             color         = '".$color."',
                             renavam       = '".$renavam."',
                             chassi        = '".$chassi."',
                             id_vehicle_type = $id_vehicle_type
                WHERE  id = '".$veic_sel."'";
       pg_query($sql)or die("Erro ".__LINE__);

       logger("Atualização","OCT - Veículo", "Ocorrência n.".$id.", ID: ".$veic_sel);

       if($retorno_acao == "continuar"){ header("Location: FORM_veiculo.php?id=".$id); }
       else                            { header("Location: FORM.php?id=".$id);         }
       exit();
    }


    extract($_GET);
    if($acao == "remover")
    {
      $sql  = "SELECT * FROM sepud.oct_vehicles WHERE id = '".$veic_sel."'";
      $res  = pg_query($sql)or die("Erro ".__LINE__);
      $d    = pg_fetch_assoc($res);
      $veic = print_r($d,true);

      $sql  = "DELETE FROM sepud.oct_vehicles WHERE id = '".$veic_sel."'";
      pg_query($sql)or die("Erro ".__LINE__);

      logger("Remoção","OCT - Veículo", "Ocorrência n.".$id.", Dados: ".$veic);

      header("Location: FORM_veiculo.php?id=".$id);
      exit();
    }

?>
