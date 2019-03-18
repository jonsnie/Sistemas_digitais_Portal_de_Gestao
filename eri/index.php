<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $agora = now();

  logger("Acesso","ERG");

       $sql = "SELECT
              	SP.id, SP.id_vehicle, SP.id_parking, SP.timestamp,SP.notified, SP.closed, SP.licence_plate,
                SP.closed_timestamp, SP.notified_timestamp,
              	--V.brand, V.model, V.color, V.licence_plate,
              	P.id_place, P.description as parking_description,
              	S.name as street_name
              FROM
              	sepud.eri_schedule_parking SP
                --JOIN sepud.eri_vehicles V ON V.id = SP.id_vehicle
                JOIN sepud.eri_parking  P ON P.id = SP.id_parking
                JOIN sepud.streets      S ON S.id = P.id_street
              WHERE SP.timestamp >= '".$agora['datasrv']." 00:00:00'
              ORDER BY SP.closed ASC, SP.notified ASC,SP.timestamp ASC";
    $rs  = pg_query($conn_neogrid,$sql);
?>
<style>
</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Estacionamento Rotativo Gratuito</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Estacionamento Rotativo Gratuito</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<div class="col-md-12">
								<section class="panel">


                  <!--
									<header class="panel-heading" style="padding:0; margin:0;">
                      <div class="col-md-2  text-center"><?="<h4><b>".$agora['data']."</b></h4>";?></div>
                      <div class="col-md-8 text-center"><?="<h4><b>".pg_num_rows($rs)." Registros</b></h4>";?></div>
                      <div class="col-md-2 text-center">
                          <a href="eri/FORM.php">
                            <button type="button" class="btn btn-lg btn-success">Novo</button>
                          </a>
									    </div>
                    </div>
                  </header>
                -->
									<div class="panel-body">
                    <div class="row">
                      <div class="col-md-12" style="margin-bottom:-20px">
                          <table class="table">
                            <tr><td class="text-center text-muted"><?="<h4><b>".$agora['data']."<br>".pg_num_rows($rs)." registro(s)</b></h4>";?></td>
                                <td class="text-center">
                                    <a href="eri/FORM.php">
                                        <button  style="margin-top:6px" type="button" class="btn btn-lg btn-success">Novo</button>
                                    </a>
                                </td>
                            </tr>
                          </table>
                      </div>
                    </div>

                            <?  if(pg_num_rows($rs))
                            {
                                                      echo
                                                           "<div class='table-responsive'>
                                      											<table class='table table-hover mb-none'>
                                      												<thead>
                                                                  <tr>
                                          														<th>Placa</th>
                                                                      <th>Vaga</th>
                                                                      <th>Entrada</th>
                                                                      <th>Tempo</th>
                                                                  </tr>
                                      												</thead>
                                      												<tbody>";
                                        while($dados = pg_fetch_assoc($rs))
                                        {
                                          $data  = formataData($dados['timestamp'],1);
                                          $dtAux = explode(" ",$data);
                                          $hora  = $dtAux[1];

                                          $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

                                          $class = "success";
                                          if($diff >= 0  && $diff <= 60 && $dados['closed']!="t" && $dados['notified']!="t"){ $class = "success"; $stats['no_prazo']++; $total++;}
                                          if($diff >= 61 && $diff <= 89 && $dados['closed']!="t" && $dados['notified']!="t"){ $class = "warning"; $stats['prox_do_fim']++; $total++;}
                                          if($diff >= 90                && $dados['closed']!="t" && $dados['notified']!="t"){ $class = "danger";  $stats['expirado']++; $total++;}
                                          if($dados['closed']=="t")                                                        { $class = "primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $stats['baixado']++;$total++;}
                                          if($dados['notified']=="t")                                                      { $class = "dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $stats['notificado']++;$total++;}



                                          echo "<tr id='".$dados['id']."' class='".$class."' onClick=\"go('eri/FORM.php?id=".$dados['id']."');\"
                                                                                             onMouseOver=\"style:cursor.hand\">";
                                          //echo "<td><b>".$dados['id']."</b></td>";
                                          echo "<td>".$dados['licence_plate']."</td>";
                                          //echo "<td>".$dados['brand']." ".$dados['model']." ".$dados['color']."</td>";
                                          //echo "<td>".$dados['parking_description']."</td>";
                                          echo "<td>".$dados['id_place']."</td>";
                                          //echo "<td>".$dados['street_name']."</td>";
                                          echo "<td>".$hora."</td>";
                                          echo "<td><b>".$diff." min</b></td>";

                                          /*
                                          echo "<td class='actions text-center'>
                                                  <a href='eri/FORM.php?id=".$dados['id']."' class='mb-xs mt-xs mr-xs btn btn-default' style='margin-top:15px'><i class='fa fa-pencil'></i></a>
                                                </td>";
                                          */
                                          echo "</tr>";
                                        }

                                                echo "</tbody>
                                      											</table></div>";
                            }else{
                              echo "<div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhuma registro de estacionamento rotativo realizado hoje.</div>";
                            }
                            ?>


                            <div class="row">
                                <div class="col-sm-4">
                                  <hr>
                                  Estatiscas:
                                  <table class="table table-condensed>"
                                      <tbody>
                                        <tr class="danger"><td>Expirado <sup>(> 90 min)</sup></td><td><?=number_format($stats['expirado'],0,'','.');?></td></tr>
                                        <tr class="warning"><td>Próx do fim <sup>(> 60 min)</sup></td><td><?=number_format($stats['prox_do_fim'],0,'','.');?></td></tr>
                                        <tr class="success"><td>No prazo <sup>(< 60 min)</sup></td><td><?=number_format($stats['no_prazo'],0,'','.');?></td></tr>
                                        <tr class="dark"><td>Notificado <sup>(Emitir multa talonário eletrônico)</td><td><?=number_format($stats['notificado'],0,'','.');?></td></tr>
                                        <tr class="primary"><td>Baixado</td><td><?=number_format($stats['baixado'],0,'','.');?></td></tr>
                                        <tr class="text-danger"><td><b>Total:</b></td><td><b><?=number_format($total,0,'','.');?></b></td></tr>
                                      </tbody>
                                  </table>
                                </div>
                            </div>

									</div>

								</section>
							</div>

</section>
<script>


function go(url)
{
  //alert("Clicou, URL: "+url);
  $('#wrap').load(url);
  return false;
}
// $(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
// $(".loading2").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

(function( $ ) {

	'use strict';

  /*
  $('.modal-basic').click(function() {
    var id_remover = $(this).attr("remover_id");



    $('.modal-basic').magnificPopup({
      type: 'inline',
  		fixedContentPos: false,
  		fixedBgPos: true,
  		overflowY: 'auto',
  		closeBtnInside: true,
  		preloader: false,
  		midClick: true,
  		removalDelay: 300,
  		mainClass: 'my-mfp-slide-bottom',
  		modal: true,
      callbacks: {
        beforeClose: function()
        {
            $.ajax({
              method: "POST",
              url: "usuarios/sqls.php",
              data: { id: id_remover, acao: "remover" }
            }).done(function( msg ) {
                $("#"+id_remover).fadeOut("slow");
              });

        }
      }
  	});
  });
*/
$(".modal-basic").click(function(){
    var ID = $(this).attr('remover_id');
    $('.modal-confirm').attr('remover_id',ID);
});

	$('.modal-basic').magnificPopup({
    type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom',
		modal: true
	});

  	$(document).on('click', '.modal-dismiss', function (e) {
  		e.preventDefault();
  		$.magnificPopup.close();
      $('.modal-confirm').removeAttr('remover_id');
  	});


  	$(document).on('click', '.modal-confirm', function (e) {
      var remover_id = $(this).attr('remover_id');
      var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15};
      e.preventDefault();
  		$.magnificPopup.close();

      $.ajax({
        method: "POST",
        url: "usuarios/sqls.php",
        data: { id: remover_id, acao: "remover" }
      }).done(function( msg ) {
          //alert("REMOVIDO !!!! ID: "+remover_id);
          $("#"+remover_id).fadeOut("slow");

          var notice = new PNotify({
                title: 'Sucesso',
                text:  'Registro #'+remover_id+' removido.',
                type:  'success',
                addclass: 'stack-bottomright',
                stack: stack_bottomright,
                hide: true,
                delay: 1000,
                closer: true
              });

        });
  	});

  }).apply( this, [ jQuery ]);
</script>
