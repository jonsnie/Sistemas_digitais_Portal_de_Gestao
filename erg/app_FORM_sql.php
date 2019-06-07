<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);


    if($acao == "inserir" && (($license_plate_letters && $license_plate_numbers != "") || $license_plate_numbers_mercosul != ""))
    {


      $agora     = now();

      //////////////////////////////////////////////////////////
      //Bloqueios de inserção após o horario final de operação//
      //Dia de semana após as 18:30
      if(($agora['dia_semana']>=1 && $agora['dia_semana']<=5) && (($agora['hora']>=18 && $agora['min']>=30) || $agora['hora']>=19))
      {
        header("Location: app_FORM.php?erro=fora_do_horario");
        exit();
      }
      //Sabado após as 13h ou domingo o dia todo
      if(($agora['dia_semana']==6 && $agora['hora']>13) || $agora['dia_semana']==7)
      {
        header("Location: app_FORM.php?erro=fora_do_horario");
        exit();
      }
      //////////////////////////////////////////////////////////

      //Registro dia de semana antes das 08:30 (Seg a Sex - 8:30 a 18:30)
      if(($agora['dia_semana']>=1 && $agora['dia_semana']<=5) && (($agora['hora']<=8 && $agora['min']<=29) || $agora['hora']<=7))
      {
        $agora['datatimesrv'] = $agora['datasrv']." 08:30:00";
      }

      //Registro SÁBADO antes das 08:00 (Sab - 8:00 a 13:00)
      if($agora['dia_semana']==6 && $agora['hora']<=7)
      {
        $agora['datatimesrv'] = $agora['datasrv']." 08:00:00";
      }




      $timestamp = $agora['datatimesrv'];
      if($license_plate_numbers != ""){ $placa = $license_plate_letters.$license_plate_numbers;         }
      else                            { $placa = $license_plate_numbers_mercosul;}

      //checagem se a vaga não esta ocupada//
      if($multi_parking == "false") //Se a vaga não permite multiplos veiculos, baixa o anterior (caso exista)
      {
          $sql = "UPDATE sepud.eri_schedule_parking
                  SET closed = true, closed_timestamp = '".$agora['datatimesrv']."'
                  WHERE id_parking = '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
          pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      }

      //Se a placa estiver ativa em outra vaga, também realiza a baixa
      $sql = "UPDATE sepud.eri_schedule_parking
              SET closed = true, closed_timestamp = '".$agora['datatimesrv']."'
              WHERE licence_plate = '".$placa."' AND id_parking != '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);


      $sql = "INSERT INTO sepud.eri_schedule_parking
                        (id_parking,
                         timestamp,
                         id_user,
                         licence_plate,
                          obs)
             VALUES
      	               (".$id_parking.",
                        '".$timestamp."',
                        ".$id_user.",
                        '".$placa."',
                        '".$obs."') RETURNING id";
      $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
      $aux = pg_fetch_assoc($res);

      logger("Inserção","ERG - Registro","Novo registro, ID: ".$aux['id'].", Placa do veículo: ".$placa);

      header("Location: app_FORM.php?id=".$aux['id']);
      exit();
    }

    extract($_GET);

    if($acao == "notificar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET notified = true, notified_timestamp = '".$agora['datatimesrv']."', id_user_notified = '".$_SESSION['id']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Notificação","ERG - Registro","Registro ID: ".$id);

        header("Location: app_FORM.php?id=".$id);
        exit();
    }

    if($acao == "guinchar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET winch_timestamp = '".$agora['datatimesrv']."', id_user_winch = '".$_SESSION['id']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Guinchamento","ERG - Registro","Registro ID: ".$id);

        header("Location: app_FORM.php?id=".$id);
        exit();
    }

    if($acao == "baixar" && $id != "")
    {
        $agora = now();
        $sql = "UPDATE sepud.eri_schedule_parking
                SET closed = true, closed_timestamp = '".$agora['datatimesrv']."', id_user_closed = '".$_SESSION['id']."'
                WHERE id = '".$id."'";
        pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

        logger("Baixa","ERG - Registro","Registro ID: ".$id);

        header("Location: app_FORM.php?id=".$id);
        exit();
    }

      header("Location: app_FORM.php");
      exit();
?>
