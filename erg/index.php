<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $agora = now();

  $sql = "SELECT S.name as street, T.type, count(*) as qtd
          FROM sepud.eri_parking P, sepud.eri_parking_type T, sepud.streets S
          WHERE P.id_parking_type = T.id
            AND P.id_street = S.id
            AND P.active = true
          GROUP BY S.name, T.type
          ORDER BY S.name ASC";
  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
      $stats[$d['street']][$d['type']]['vagas'] = $d['qtd'];
      $stats_vagas[$d['type']]['vagas']        += $d['qtd'];
  }

  $sql = "SELECT S.name as street, PT.type, count(*) as qtd,
                 SP.notified_timestamp, SP.closed_timestamp
          FROM sepud.eri_schedule_parking SP
          JOIN sepud.eri_parking P ON SP.id_parking = P.id
          JOIN sepud.eri_parking_type PT ON P.id_parking_type = PT.id
          JOIN sepud.streets S ON P.id_street = S.id
          WHERE SP.timestamp >= '".$agora['datasrv']." 00:00:00'
          --AND SP.notified_timestamp is null AND SP.closed_timestamp is null
          GROUP BY S.name, PT.type, SP.notified_timestamp, SP.closed_timestamp
          ORDER BY S.name ASC";
  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
    //$stats[$d['street']][$d['type']]['em_uso'] = $d['qtd'];

    if($d['notified_timestamp']=="" && $d['closed_timestamp']=="")
    {
      $stats[$d['street']][$d['type']]['em_uso'] += $d['qtd'];
      $stats_vagas[$d['type']]['em_uso']         += $d['qtd'];
    }else{
      $stats[$d['street']][$d['type']]['baixado'] += $d['qtd'];
      $stats_vagas[$d['type']]['baixado']        += $d['qtd'];
    }
  }
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Sistema de Estacionamento Rotativo Público</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>SERP</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel">
        <header class="panel-heading">
          <div class="panel-actions" style='margin-top:-12px'>
            <h5 class='pull-right'>Referência <?=$agora['data'];?></h5>
          </div>
        </header>

        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">

              <div class="row">
                  <div class="col-sm-6">
                      <?

                      echo "<table class='table'>
                            <thead><tr><th>Tipos de vagas</th><th>Quantidade</th><th>Em uso</th><th>% em uso</th></thead>";
                      echo "<tbody>";

                                $azul     = round($stats_vagas['Zona Azul - Veículo de passeio']['em_uso']*100/$stats_vagas['Zona Azul - Veículo de passeio']['vagas'],1);
                                $preta    = round($stats_vagas['Zona Preta - Moto']['em_uso']*100/$stats_vagas['Zona Preta - Moto']['vagas'],1);
                                $verde    = round($stats_vagas['Zona Verde - Idoso']['em_uso']*100/$stats_vagas['Zona Verde - Idoso']['vagas'],1);
                                $branca   = round($stats_vagas['Zona Branca - Deficiente']['em_uso']*100/$stats_vagas['Zona Branca - Deficiente']['vagas'],1);
                                $amarela  = round($stats_vagas['Zona Amarela - Veículo de carga']['em_uso']*100/$stats_vagas['Zona Amarela - Veículo de carga']['vagas'],1);
                                $vermelha = round($stats_vagas['Zona Vermelha - Curta-duração']['em_uso']*100/$stats_vagas['Zona Vermelha - Curta-duração']['vagas'],1);
                      echo "<tr><td>Zona Azul - Veículo de passeio</td>
                                <td>".$stats_vagas['Zona Azul - Veículo de passeio']['vagas']."</td>
                                <td>".$stats_vagas['Zona Azul - Veículo de passeio']['em_uso']."</td>
                                <td>".$azul." %</td></tr>";
                      echo "<tr><td>Zona Preta - Moto</td>
                                <td>".$stats_vagas['Zona Preta - Moto']['vagas']."</td>
                                <td>".$stats_vagas['Zona Preta - Moto']['em_uso']."</td>
                                <td>".$preta." %</td></tr>";
                      echo "<tr><td>Zona Verde - Idoso</td>
                                <td>".$stats_vagas['Zona Verde - Idoso']['vagas']."</td>
                                <td>".$stats_vagas['Zona Verde - Idoso']['em_uso']."</td>
                                <td>".$verde." %</td></tr>";
                      echo "<tr><td>Zona Branca - Deficiente</td>
                                <td>".$stats_vagas['Zona Branca - Deficiente']['vagas']."</td>
                                <td>".$stats_vagas['Zona Branca - Deficiente']['em_uso']."</td>
                                <td>".$branca." %</td></tr>";
                      echo "<tr><td>Zona Amarela - Veículo de carga</td>
                                <td>".$stats_vagas['Zona Amarela - Veículo de carga']['vagas']."</td>
                                <td>".$stats_vagas['Zona Amarela - Veículo de carga']['em_uso']."</td>
                                <td>".$amarela." %</td></tr>";
                      echo "<tr><td>Zona Vermelha - Curta-duração</td>
                                <td>".$stats_vagas['Zona Vermelha - Curta-duração']['vagas']."</td>
                                <td>".$stats_vagas['Zona Vermelha - Curta-duração']['em_uso']."</td>
                                <td>".$vermelha." %</td></tr>";
                      echo "</tbody></table>";

                      ?>
                  </div>
                  <div class="col-sm-6">
                      <h5>Tempo de ocupação:</h5>
                      <?
                          $sql = "SELECT
                                      	P.name as vaga,
                                      	SP.timestamp,	SP.closed_timestamp,	SP.notified_timestamp, SP.notified, SP.closed, SP.obs,
                                      	PT.time, PT.time_warning
                                  FROM sepud.eri_schedule_parking SP
                                  JOIN sepud.eri_parking          P  ON P.ID  = SP.id_parking
                                  JOIN sepud.eri_parking_type     PT ON PT.ID = P.id_parking_type
                                  WHERE
                                      	SP.TIMESTAMP >= '".$agora['datasrv']." 00:00:00'";
                          $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
                          $status = ["no_prazo" => 0, "prox_do_fim" => 0, "expirado" => 0, "baixado" => 0, "notificado" => 0];
                          while($dados = pg_fetch_assoc($res))
                          {
                            $data  = formataData($dados['timestamp'],1);
                            $dtAux = explode(" ",$data);
                            $hora  = $dtAux[1];
                            $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

                            //echo "<br>[".$dados['time']."] ".$dados['timestamp']." + ".$diff." min";

                            if($dados['closed']!="t" && $dados['notified']!="t")
                            {
                              if($diff >= 0                      && $diff < $dados['time_warning']){ $status['no_prazo']++;    }
                              if($diff >= $dados['time_warning'] && $diff < $dados['time']        ){ $status['prox_do_fim']++; }
                              if($diff >= $dados['time']                                          ){ $status['expirado']++;    }
                            }else{
                              if($dados['closed']  =="t"){ $status['baixado']++;    }
                              if($dados['notified']=="t"){ $status['notificado']++; }
                            }
                          }

                          print_r_pre($status);

                      ?>
                  </div>
              </div>

              <div class="row">
                  <div class="col-sm-12">
                    <?
                        echo "<table class='table'>
                              <thead><tr><th></th>
                                         <th class='text-center' colspan='3'>Zona Azul<br>Veículo de passeio</th>
                                         <th class='text-center' colspan='3' style='background:#FFFFF0'>Zona Preta<br>Moto</th>
                                         <th class='text-center' colspan='3'>Zona Verde<br>Idoso</th>
                                         <th class='text-center' colspan='3' style='background:#FFFFF0'>Zona Branca<br>Deficiente</th>
                                         <th class='text-center' colspan='3'>Zona Amarela<br>Veículo de carga</th>
                                         <th class='text-center' colspan='3' style='background:#FFFFF0'>Zona Vermelha<br>Curta-duração</th>
                              </tr>
                              <tr><th>Logradouros</th>
                                  <th class='text-center text-muted'><small>Qtd</small></th><th class='text-center text-muted'><small>Em uso</small></th><th class='text-center text-muted'><small>Baixado</small></th>
                                  <th class='text-center text-muted' style='background:#FFFFF0'><small>Qtd</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Em uso</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Baixado</small></th>
                                  <th class='text-center text-muted'><small>Qtd</small></th><th class='text-center text-muted'><small>Em uso</small></th><th class='text-center text-muted'><small>Baixado</small></th>
                                  <th class='text-center text-muted' style='background:#FFFFF0'><small>Qtd</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Em uso</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Baixado</small></th>
                                  <th class='text-center text-muted'><small>Qtd</small></th><th class='text-center text-muted'><small>Em uso</small></th><th class='text-center text-muted'><small>Baixado</small></th>
                                  <th class='text-center text-muted' style='background:#FFFFF0'><small>Qtd</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Em uso</small></th><th class='text-center text-muted' style='background:#FFFFF0'><small>Baixado</small></th>
                              </thead><tbody>";
                            foreach($stats as $ruas => $tipos)
                            {
                              echo "<tr>";
                                  echo "<td>".$ruas."</td>";
                                  echo "<td class='text-center text-muted'>".$tipos['Zona Azul - Veículo de passeio']['vagas']."</td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Azul - Veículo de passeio']['em_uso']."</b></td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Azul - Veículo de passeio']['baixado']."</b></td>";
                                  echo "<td class='text-center text-muted' style='background:#FFFFF0'>".$tipos['Zona Preta - Moto']['vagas']."</td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Preta - Moto']['em_uso']."</b></td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Preta - Moto']['baixado']."</b></td>";
                                  echo "<td class='text-center text-muted'>".$tipos['Zona Verde - Idoso']['vagas']."</td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Verde - Idoso']['em_uso']."</b></td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Verde - Idoso']['baixado']."</b></td>";
                                  echo "<td class='text-center text-muted' style='background:#FFFFF0'>".$tipos['Zona Branca - Deficiente']['vagas']."</td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Branca - Deficiente']['em_uso']."</b></td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Branca - Deficiente']['baixado']."</b></td>";
                                  echo "<td class='text-center text-muted'>".$tipos['Zona Amarela - Veículo de carga']['vagas']."</td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Amarela - Veículo de carga']['em_uso']."</b></td>";
                                  echo "<td class='text-center'><b>".$tipos['Zona Amarela - Veículo de carga']['baixado']."</b></td>";
                                  echo "<td class='text-center text-muted' style='background:#FFFFF0'>".$tipos['Zona Vermelha - Curta-duração']['vagas']."</td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Vermelha - Curta-duração']['em_uso']."</b></td>";
                                  echo "<td class='text-center' style='background:#FFFFF0'><b>".$tipos['Zona Vermelha - Curta-duração']['baixado']."</b></td>";
                              echo "</tr>";
                            }
                        echo "</tbody></table>";
                    ?>
                  </div>
              </div>
            </div>

          </div>
        </div>
    </section>
  </div>
</div>
</section>
<th class='text-center text-muted'><small>Baixado/Notif</small></th>
