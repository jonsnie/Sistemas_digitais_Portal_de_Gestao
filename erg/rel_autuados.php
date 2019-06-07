<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora = now();

/*
Array
(
    [filtro_data] => 11/03/2019
    [filtro_placaveiculo] => xx9999
)
*/

  $filtro_data = "AND          SP.timestamp BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'";

if($_POST['filtro_data'] != ""){
  $filtro_data = " AND          SP.timestamp BETWEEN '".$_POST['filtro_data']." 00:00:00' AND '".$_POST['filtro_data']." 23:59:59' ";
}
if($_POST['filtro_placaveiculo'] != ""){
  $filtro_data = "";
  $filtro_placaveiculo = " AND SP.licence_plate = '".strtoupper(str_replace("-","",$_POST['filtro_placaveiculo']))."'";
}


$sql = "SELECT
        	U.NAME AS nome_registro,
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
        	JOIN sepud.eri_parking 					 P ON  P.ID = SP.id_parking
        	JOIN sepud.eri_parking_type 		PT ON PT.ID = P.id_parking_type
        	JOIN sepud.streets 							 S ON  S.ID = P.id_street
        	JOIN sepud.users 								 U ON  U.ID = SP.id_user
        WHERE
        	  SP.notified_timestamp IS NOT NULL
            ".$filtro_data.$filtro_placaveiculo."
        ORDER BY SP.timestamp DESC";

  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
    $dados[] = $d;
  }

  logger("Acesso","SERP - Relatório Autuados");
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Sistema de Estacionamento Rotativo Público</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>SERP - Veículos autuados</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel box_shadow">
        <header class="panel-heading" style="height:60px">
            <?
              if(pg_num_rows($res))
              {

              }else {
                //echo "<span>Nenhum veículo notificado para esta data.";
              }
            ?>
          <div class="panel-actions">

            <button type='button' class='btn btn-sm btn-info' data-toggle='modal' data-target='#modalFiltro'>
            <i class='fa fa-search'></i>
            </button>

            <!--<button id="bt_placa" style='' type='button' class='btn btn-sm btn-primary'><i id="bt_placa_icon" class='fa fa-refresh'></i></button>-->
            <button id="bt_refresh" style='' type='button' class='btn btn-sm btn-primary'><i id="bt_refresh_icon" class='fa fa-refresh'></i></button>
            &nbsp;&nbsp;&nbsp;<h5 class='pull-right'>Referência <?=$agora['dthm'];?></h5>
          </div>
        </header>

        <div class="panel-body">
          <div class="row">
            <div class="col-sm-12">
                      <?
                        if(pg_num_rows($res)){
                              echo "<table class='table table-striped table-hover'>";
                              echo       "<thead><tr>
                                               <th>#</th>
                                               <th>Placa</th>
                                               <th><i class='fa fa-search'></i></th>
                                               <th>Tipo</th>
                                               <th>Logradouro</th>
                                               <th>Vaga nº</th>
                                               <th>Entrada</th>
                                               <th>Notificado</th>
                                               <th>Guinchado</th>
                                               <th>Baixado</th>
                                          </tr></thead>";

                              echo "<tbody>";
                              for($i=0;$i<count($dados);$i++)
                              {

                                if($dados[$i]['notified_timestamp']){  $diff_notified = " <sup>(+".floor((strtotime($dados[$i]['notified_timestamp']) - strtotime($dados[$i]['timestamp']))/60).")</sup>";}else{ $diff_notified = ""; }
                                   if($dados[$i]['winch_timestamp']){  $diff_winch    = " <sup>(+".floor((strtotime($dados[$i]['winch_timestamp'])    - strtotime($dados[$i]['timestamp']))/60).")</sup>";}else{ $diff_winch = ""; }
                                  if($dados[$i]['closed_timestamp']){  $diff_closed   = " <sup>(+".floor((strtotime($dados[$i]['closed_timestamp'])   - strtotime($dados[$i]['timestamp']))/60).")</sup>";}else{ $diff_closed = ""; }

                                  if($dados[$i]['notified_timestamp'] != ""){ $aux = explode(" ",$dados[$i]['notified_timestamp']); $notified_timestamp_hour = $aux[1];}else{ $notified_timestamp_hour = "";}
                                  if($dados[$i]['winch_timestamp']    != ""){ $aux = explode(" ",$dados[$i]['winch_timestamp']);    $winch_timestamp_hour    = $aux[1];}else{ $winch_timestamp_hour = "";}
                                  if($dados[$i]['closed_timestamp']   != ""){ $aux = explode(" ",$dados[$i]['closed_timestamp']);   $closed_timestamp_hour   = $aux[1];}else{ $closed_timestamp_hour = "";}
                                echo "<tr>";
                                  echo "<td class='text-muted'><small>".$dados[$i]['id_registro']."</small></td>";
                                  echo "<td><b>".$dados[$i]['placa_veiculo']."</b></td>";
                                  echo "<td><a href='erg/rel_placa.php?placa=".$dados[$i]['placa_veiculo']."'><i class='fa fa-search'></i></a></td>";
                                  echo "<td>".$dados[$i]['tipo_vaga']." <sup><small>(".$dados[$i]['tempo_permanencia']." min.)</sup></small></td>";
                                  echo "<td>".$dados[$i]['logradouro']."</td>";
                                  echo "<td>".$dados[$i]['vaga']."</td>";
                                  echo "<td>".formataData($dados[$i]['timestamp'],1)."</td>";
                                  echo "<td>".$notified_timestamp_hour.$diff_notified."</td>";
                                  echo "<td>".$winch_timestamp_hour.$diff_winch."</td>";
                                  echo "<td>".$closed_timestamp_hour.$diff_closed."</td>";
                                echo "</tr>";
                              }

                              echo "</tbody></table>";

                          }else {
                            echo "<div class='alert alert-warning text-center'>Nenhum veículo autuado na data de hoje.</div>";
                          }

                      ?>
                  </div>
              </div>
            </div>
          </section>
          </div>
        </div>
</section>

<!-- Modal FILTROS -->
<div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="erg/rel_autuados.php" method="post">
      <div class="modal-body">
        <? require_once("filtros.php"); ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/rel_autuados.php");});
</script>
