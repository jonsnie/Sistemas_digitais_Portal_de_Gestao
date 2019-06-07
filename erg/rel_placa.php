<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

if($_GET['placa']!="")
{


$sql = "SELECT
        	U.NAME AS nome_registrou,
          UN.NAME AS nome_notificou,
          UW.NAME AS nome_guinchou,
          UC.NAME AS nome_baixou,
        	S.NAME AS logradouro,
        	PT.TYPE AS tipo_vaga,
        	PT.TIME AS tempo_permanencia,
        	PT.observation AS tipo_desc,
        	P.NAME AS vaga,
        	P.description AS vaga_obs,
        	SP.licence_plate AS placa_veiculo,
        	SP.ID AS id_registro,
        	SP.TIMESTAMP,
        	SP.notified_timestamp,
        	SP.closed_timestamp,
        	SP.winch_timestamp
        FROM
        			      sepud.eri_schedule_parking SP
        	     JOIN sepud.eri_parking 					P ON  P.ID = SP.id_parking
        	     JOIN sepud.eri_parking_type 		 PT ON PT.ID = P.id_parking_type
        	     JOIN sepud.streets 							S ON  S.ID = P.id_street
        	     JOIN sepud.users 								U ON  U.ID = SP.id_user
          LEFT JOIN sepud.users 							 UN ON UN.ID = SP.id_user_notified
          LEFT JOIN sepud.users 							 UW ON UW.ID = SP.id_user_winch
          LEFT JOIN sepud.users 							 UC ON UC.ID = SP.id_user_closed
        WHERE
        	  SP.licence_plate = '".$_GET['placa']."'
            --SP.licence_plate = 'AWP7828'
        ORDER BY SP.timestamp DESC";

  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
    $dados[] = $d;
  }
}
  logger("Acesso","SERP - Relatório por placa de veículo");
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Sistema de Estacionamento Rotativo Público</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="erg/rel_autuados.php">SERP - Veículos autuados</a></li>
        <li><span>Placa de veículo</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel box_shadow">
        <header class="panel-heading" style="height:60px">
          <h3 style="margin-top:0px"><small class='text-muted'><i>Placa do veículo:</small> <b><?=$_GET['placa'];?></b></i></h3>
          <div class="panel-actions">
              <a href="erg/rel_autuados.php"><button class='btn btn-sm btn-default loading'>Voltar</button></a>
          </div>
        </header>

        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
                      <?
                        if(isset($res) && pg_num_rows($res)){
                              echo "<table class='table table-condensed'>";

                              echo       "<thead><tr>
                                               <th>Registro nº</th>
                                               <th>Placa</th>
                                               <th>Vaga nº</th>
                                               <th>Tipo</th>
                                               <th>Logradouro</th>
                                               <th class='text-right'>Status</th>

                                          </tr></thead>";

                              echo "<tbody>";
                              for($i=0;$i<count($dados);$i++)
                              {

                                if($dados[$i]['notified_timestamp']){  $diff_notified = "+".floor((strtotime($dados[$i]['notified_timestamp']) - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_notified = ""; }
                                   if($dados[$i]['winch_timestamp']){  $diff_winch    = "+".floor((strtotime($dados[$i]['winch_timestamp'])    - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_winch = ""; }
                                  if($dados[$i]['closed_timestamp']){  $diff_closed   = "+".floor((strtotime($dados[$i]['closed_timestamp'])   - strtotime($dados[$i]['timestamp']))/60);}else{ $diff_closed = ""; }

                                  if($dados[$i]['notified_timestamp'] != ""){ $aux = explode(" ",$dados[$i]['notified_timestamp']); $notified_timestamp_hour = $aux[1];}else{ $notified_timestamp_hour = "";}
                                  if($dados[$i]['winch_timestamp']    != ""){ $aux = explode(" ",$dados[$i]['winch_timestamp']);    $winch_timestamp_hour    = $aux[1];}else{ $winch_timestamp_hour = "";}
                                  if($dados[$i]['closed_timestamp']   != ""){ $aux = explode(" ",$dados[$i]['closed_timestamp']);   $closed_timestamp_hour   = $aux[1];}else{ $closed_timestamp_hour = "";}

                                echo "<tr style='background-color:#E0E0E0'>";
                                  echo "<td class='text-muted'><small>".$dados[$i]['id_registro']."</small></td>";
                                  echo "<td><b>".$dados[$i]['placa_veiculo']."</b></td>";
                                  echo "<td><b>".$dados[$i]['vaga']."</b></td>";
                                  echo "<td>".$dados[$i]['tipo_vaga']." <sup><small>(".$dados[$i]['tempo_permanencia']." min.)</sup></small></td>";
                                  echo "<td>".$dados[$i]['logradouro']."</td>";

                                  if($dados[$i]['notified_timestamp'] != "")
                                  {
                                    if($dados[$i]['winch_timestamp'] != "")
                                    {
                                      echo "<td class='danger text-right'><b>Notificado e Guinchado</b></td>";
                                    }else {
                                      echo "<td class='danger text-right'><b>Notificado</b></td>";
                                    }
                                  }else {
                                      echo "<td class='primary text-right'><b>Baixado</b></td>";
                                  }


                                echo "</tr>";


                                echo "<tr><td colspan='2' class='text-right'><i>Ação</i></td>
                                          <td colspan='2'><i>Responsável pelo registro</i></td>
                                          <td><i>Data/Hora</i></td>
                                          <td><i>Tempo</i></td><tr>";

                                echo "<tr><td colspan='2' class='text-right'>Registro de entrada:</td>";
                                  echo "<td colspan='2'>".$dados[$i]['nome_registrou']."</td>";
                                  echo "<td>".formataData($dados[$i]['timestamp'],1)."</td>";
                                  echo "<td>&nbsp;</td>";
                                //  echo "<td>".$notified_timestamp_hour.$diff_notified."</td>";
                                //  echo "<td>".$winch_timestamp_hour.$diff_winch."</td>";
                                //  echo "<td>".$closed_timestamp_hour.$diff_closed."</td>";
                                echo "</tr>";

                                if($dados[$i]['notified_timestamp']!="")
                                {
                                    echo "<tr><td colspan='2' class='text-right'>Notificação:</td>";
                                      echo "<td colspan='2'>".($dados[$i]['nome_notificou']=!""?$dados[$i]['nome_notificou']:"- - - -")."</td>";
                                      echo "<td>".formataData($dados[$i]['notified_timestamp'],1)."</td>";
                                      echo "<td>".$diff_notified." min.</td>";
                                    echo "</tr>";
                                }

                                if($dados[$i]['winch_timestamp']!="")
                                {
                                      echo "<tr><td colspan='2' class='text-right'>Guinchamento:</td>";
                                        echo "<td colspan='2'>".($dados[$i]['nome_guinchou']=!""?$dados[$i]['nome_guinchou']:"- - - -")."</td>";
                                        echo "<td>".formataData($dados[$i]['winch_timestamp'],1)."</td>";
                                        echo "<td>".$diff_winch." min.</td>";
                                      echo "</tr>";
                                }

                                if($dados[$i]['closed_timestamp']!="")
                                {
                                    echo "<tr><td colspan='2' class='text-right'>Baixa do registro:</td>";
                                      echo "<td colspan='2'>".($dados[$i]['nome_baixou']=!""?$dados[$i]['nome_baixou']:"- - - -")."</td>";
                                      echo "<td>".formataData($dados[$i]['closed_timestamp'],1)."</td>";
                                      echo "<td>".$diff_closed." min.</td>";
                                    echo "</tr>";
                                }

/*
                                echo "<tr style='background-color:#FFFFF0'>";
                                  echo "<td class='text-muted text-right'><small>Agente que realizou a ação:</small></td>";

                                  echo "<td>".$dados[$i]['nome_notificou']."</td>";
                                  echo "<td>".$dados[$i]['nome_guinchou']."</td>";
                                  echo "<td>".$dados[$i]['nome_baixou']."</td>";
                                echo "</tr>";
*/
                                //echo "<tr><td colspan='10'><hr></td></tr>";

                              }

                              echo "</tbody></table>";

                          }else {
                            echo "<div class='alert alert-warning text-center'>Nenhuma placa de veículo com estas características registrada no sistema.</div>";
                          }

                      ?>
                  </div>
              </div>
            </div>
            <div class='panel-footer' style="height:45px">
              <span class='row pull-right' style="margin-right:5px"><small><i>Relatório gerado em <b><?=$agora['dthm'];?></b></i></small></span>
            </div>
          </section>
          </div>
        </div>
</section>
<script>
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_placa.php");});
</script>
