<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);


    if($acao == "inserir" && $licence_plate != "")
    {
      $agora     = now();
      $timestamp = $agora['datatimesrv'];
      //$timestamp = formataData($data,4)." ".$hora;

      $placa = strtoupper(str_replace("-","",$licence_plate));

      $sql = "INSERT INTO sepud.eri_schedule_parking
                        (id_parking,
                         timestamp,
                         id_user,
                         licence_plate)
             VALUES
      	               (".$id_parking.",
                        '".$timestamp."',
                        ".$id_user.",
                        '".$placa."') RETURNING id";
      $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      $aux = pg_fetch_assoc($res);

      logger("Inserção","ERG - Registro","Novo registro, ID: ".$aux['id'].", Placa do veículo: ".$placa);

      header("Location: FORM.php?id=".$aux['id']);
      exit();
    }

    extract($_GET);

    if($acao == "notificar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET notified = true, notified_timestamp = '".$agora['datatimesrv']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Notificação","ERG - Registro","Registro ID: ".$id);

        header("Location: FORM.php?id=".$id);
        exit();
    }

    if($acao == "baixar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET closed = true, closed_timestamp = '".$agora['datatimesrv']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Baixa","ERG - Registro","Registro ID: ".$id);

        header("Location: FORM.php?id=".$id);
        exit();
    }

      header("Location: FORM.php");
      exit();
?>
