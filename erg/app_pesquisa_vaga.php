<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");


  extract($_GET);
  $agora = now();


  if($origem=="pesquisa_vaga_form")
  {

      if($query != "")
      {

              $query = str_pad($query,4,"0",STR_PAD_LEFT);
                $sql = "SELECT SP.licence_plate as is_parking, SP.id as is_parking_id, SP.timestamp, closed, notified,
                               S.name as street_name,
                               P.id, P.name, P.description,
                               T.type, T.time, T.time_warning, T.multi_parking, T.observation
                        FROM sepud.eri_parking P
                        JOIN sepud.eri_parking_type T ON T.id = P.id_parking_type
                        JOIN sepud.streets S ON S.id = P.id_street
                        LEFT JOIN sepud.eri_schedule_parking SP ON SP.id_parking = P.id AND SP.closed_timestamp is null AND SP.timestamp >= '".$agora['datasrv']." 00:00:00'
                        WHERE P.active is true
                        AND P.name = '".$query."'
                        ORDER BY P.name ASC
                        LIMIT 1";
                $res = pg_query($sql)or die($sql);
                if(pg_num_rows($res))
                {
                    while($d = pg_fetch_assoc($res))
                    {
                        if($d['is_parking'] != "")
                        {
/*
                          $is_parking = "<b><span class='text-danger'>(Ocupada por ".$d['is_parking'].")</span></b>
                                         <br><a href='erg/app_FORM.php?id=".$d['is_parking_id']."' class='btn btn-sm btn-info'>Ver registro</a>";
*/

                        $diff     = floor((strtotime($agora['datatimesrv']) - strtotime($d['timestamp']))/60);
                        $btnclass = "btn-default";
                        if($d['closed']!="t" && $d['notified']!="t")
                        {
                          if($diff >= 0                  && $diff < $d['time_warning']){ $btnclass = "btn-success"; }
                          if($diff >= $d['time_warning'] && $diff < $d['time']        ){ $btnclass = "btn-warning"; }
                          if($diff >= $d['time']                                      ){ $btnclass = "btn-danger";  }
                        }else{
                          if($d['closed']   =="t"){ $btnclass = "btn-info"; }
                          if($d['notified'] =="t"){ $btnclass = "btn-dark"; }
                        }

                         $is_parking = "<br><a href='erg/app_FORM.php?id=".$d['is_parking_id']."' class='btn ".$btnclass."'>
                                            Vaga ocupada por ".$d['is_parking']." a ".$diff." min<small style='color:white'><br>(Tolerância: ".$d['time']." min)<br>Clique para ir para o registro</small>
                                        </a>";
                        }else
                        {
                          $is_parking = "<small class='text-success'>Status: <b>VAGA LIVRE</b></small>";
                        }
                        echo "<div id='ret_pesq'>
                              <h4>
                                <span class='text-success'>Vaga nº <b>".$d['name']."</b></span><br>
                                <small class='text-muted'>".$d['type']." - ".$d['time']."min
                                <br>".$d['description']."</small>";
                                //<br>".$d['observation']."</small>";
                                if($d['street_name']=="CHINA")
                                {
                                  echo "<br><b class='text-warning'>Rua genérica para esta vaga,<br>favor informar o nome da rua no campo observações</b>";
                                }else {
                                    echo "<br><b>RUA ".$d['street_name']."</b>";
                                }
                                echo "<br>".$is_parking;
                                echo "</h4>";

                        echo "<input type='hidden' name='id_parking'     id='id_parking'     value='".$d['id']."' />";
                        echo "<input type='hidden' name='placa_anterior' id='placa_anterior' value='".$d['is_parking']."' />";

                        if($d['multi_parking']=='t'){
                          echo "<input type='hidden' name='multi_parking' value='true' />";
                        }else {
                          echo "<input type='hidden' name='multi_parking' value='false' />";
                        }
                        echo "</div>";

                    }
                }else {
                  echo "<h4 class='text-danger'><i>Vaga não encontrada.</i><span style='color:white'><br>.<br>.</span></h4>";

                }
       }else{
         echo "<h4 class='text-warning'><i>Digite um número de vaga.</i><span style='color:white'><br>.<br>.</span></h4>";
       }


  }

  if($origem=="pesquisa_vaga_index" && $query != "")
  {
      $query = str_pad($query,4,"0",STR_PAD_LEFT);
        $sql = "SELECT
                 SP.id, SP.id_vehicle, SP.id_parking, SP.timestamp,SP.notified, SP.closed, SP.licence_plate,
                 SP.closed_timestamp, SP.notified_timestamp,
                 P.name as parking_code, P.description as parking_description,
                 S.name as street_name,
                 T.time, T.time_warning
               FROM
                 sepud.eri_schedule_parking SP
                 JOIN sepud.eri_parking      P ON P.id = SP.id_parking AND P.name = '".$query."'
                 JOIN sepud.streets          S ON S.id = P.id_street
                 JOIN sepud.eri_parking_type T ON T.id = P.id_parking_type
               WHERE SP.timestamp >= '".$agora['datasrv']." 00:00:00'
               ORDER BY SP.closed ASC, SP.notified ASC,SP.timestamp ASC";
        $res = pg_query($sql)or die("<tr><td>Erro: ".$sql."</td></tr>");

        if(pg_num_rows($res))
        {
          while($dados = pg_fetch_assoc($res))
          {
            $data  = formataData($dados['timestamp'],1);
            $dtAux = explode(" ",$data);
            $hora  = $dtAux[1];

            $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

            $class = "success";

            if($dados['closed']!="t" && $dados['notified']!="t")
            {
              if($diff >= 0                      && $diff < $dados['time_warning']){ $class = "success"; $stats['no_prazo']++; $total++;    }
              if($diff >= $dados['time_warning'] && $diff < $dados['time']        ){ $class = "warning"; $stats['prox_do_fim']++; $total++; }
              if($diff >= $dados['time']                                          ){ $class = "danger";  $stats['expirado']++; $total++;    }
            }else{
              if($dados['closed']  =="t"){ $class = "primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $stats['baixado']++;$total++;}
              if($dados['notified']=="t"){ $class = "dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $stats['notificado']++;$total++;}
            }


            echo "<tr id='".$dados['id']."' class='".$class."' onClick=\"go('erg/app_FORM.php?id=".$dados['id']."');\"
                                                               onMouseOver=\"style:cursor.hand\">";
                echo "<td>".$dados['licence_plate']."</td>";
                echo "<td>".$dados['parking_code']." <sup>".$dados['time']."min</sup></td>";
                echo "<td>".$hora."</td>";
                echo "<td><b>".$diff." min</b></td>";
                echo "<td>".$dados['street_name']."</td>";
            echo "</tr>";
        }
      }else {
        echo "<tr><td colspan='5' class='text-center'>";
            echo "<div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhum registro de atividade para a vaga <b>nº ".$query."</b>, ou vaga não encontrada.</div>";
        echo "</td></tr>";
      }
  }

  if($origem=="pesquisa_placa_index")
  {

    $sql = "SELECT
             SP.id, SP.id_vehicle, SP.id_parking, SP.timestamp,SP.notified, SP.closed, SP.licence_plate,
             SP.closed_timestamp, SP.notified_timestamp,
             P.name as parking_code, P.description as parking_description,
             S.name as street_name,
             T.time, T.time_warning
           FROM
             sepud.eri_schedule_parking SP
             JOIN sepud.eri_parking      P ON P.id = SP.id_parking
             JOIN sepud.streets          S ON S.id = P.id_street
             JOIN sepud.eri_parking_type T ON T.id = P.id_parking_type
           WHERE SP.timestamp >= '".$agora['datasrv']." 00:00:00' AND SP.licence_plate like '%".$query."%'
           ORDER BY SP.closed ASC, SP.notified ASC,SP.timestamp ASC";
    $res = pg_query($sql)or die("<tr><td>Erro: ".$sql."</td></tr>");

    if(pg_num_rows($res))
    {
      while($dados = pg_fetch_assoc($res))
      {
        $data  = formataData($dados['timestamp'],1);
        $dtAux = explode(" ",$data);
        $hora  = $dtAux[1];

        $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

        $class = "success";

        if($dados['closed']!="t" && $dados['notified']!="t")
        {
          if($diff >= 0                      && $diff < $dados['time_warning']){ $class = "success"; $stats['no_prazo']++; $total++;    }
          if($diff >= $dados['time_warning'] && $diff < $dados['time']        ){ $class = "warning"; $stats['prox_do_fim']++; $total++; }
          if($diff >= $dados['time']                                          ){ $class = "danger";  $stats['expirado']++; $total++;    }
        }else{
          if($dados['closed']  =="t"){ $class = "primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $stats['baixado']++;$total++;}
          if($dados['notified']=="t"){ $class = "dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $stats['notificado']++;$total++;}
        }


        echo "<tr id='".$dados['id']."' class='".$class."' onClick=\"go('erg/app_FORM.php?id=".$dados['id']."');\"
                                                           onMouseOver=\"style:cursor.hand\">";
            echo "<td>".$dados['licence_plate']."</td>";
            echo "<td>".$dados['parking_code']." <sup>".$dados['time']."min</sup></td>";
            echo "<td>".$hora."</td>";
            echo "<td><b>".$diff." min</b></td>";
            echo "<td>".$dados['street_name']."</td>";
        echo "</tr>";
    }
  }else {
    echo "<tr><td colspan='5' class='text-center'>";
        echo "<div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhum registro de atividade para a PLACA <b>".$query."</b>.</div>";
    echo "</td></tr>";
  }



  }




?>
