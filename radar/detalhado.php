<?
  session_start();
  require_once("../libs/php/conn.php");
  require_once("../libs/php/funcoes.php");

  $agora = now();

  $id = $_GET['id'];

  if($_GET['filtro']=="dia"){
    $ontem = date('Y-m-d',strtotime("-1 days"));
    $filtro_sql = " EF.pubdate = '".$ontem."'";
    $txt_filtro = "Referência: ".formataData($ontem,1);
  }else{
    $filtro_sql = " EF.pubdate >= '".$agora['ano']."-".$agora['mes']."-01'";
    $txt_filtro = "Referência: ".$agora['mes_txt_c']."/".$agora['ano'];
  }

 $sql  = "SELECT  EF.equipment,  EQ.address, EQ.id, EF.pubdate,
               (SUM(F.speed_00_10) + 	SUM(F.speed_11_20) + 	SUM(F.speed_21_30) + 	SUM(F.speed_31_40) +
                SUM(F.speed_41_50) +	SUM(F.speed_51_60) +	SUM(F.speed_61_70) +	SUM(F.speed_71_80) +
                SUM(F.speed_81_90) +	SUM(F.speed_91_100)+	SUM(F.speed_100_up)) AS contador_veiculos
          FROM radars.equipment_files as EF
          LEFT JOIN radars.flows as F       ON F.equipment_files_id = EF.id
          JOIN      radars.equipments as EQ ON EQ.equipment = EF.equipment
          WHERE  $filtro_sql AND EQ.id = '".$id."'
          GROUP BY EF.equipment, EQ.address, EQ.id, EF.pubdate
          ORDER BY 	(SUM(F.speed_00_10) + SUM(F.speed_11_20) + 	SUM(F.speed_21_30) + 	SUM(F.speed_31_40) +
                     SUM(F.speed_41_50) +	SUM(F.speed_51_60) +	SUM(F.speed_61_70) +	SUM(F.speed_71_80) +
                     SUM(F.speed_81_90) +	SUM(F.speed_91_100)+	SUM(F.speed_100_up)) DESC";

  $res  = pg_query($conn_neogrid,$sql);
  while($d = pg_fetch_assoc($res)){

      $eqps[$d['equipment']]['contador_veiculos']      += $d['contador_veiculos'];
      $eqps[$d['equipment']]['id']                      = $d['id'];
      $eqps[$d['equipment']]['address']                 = $d['address'];
      $eqps[$d['equipment']]['contagem'][$d['pubdate']] = $d['contador_veiculos'];

      $nome_eqp = $d['equipment'];
  }


  $sqlImport  = "SELECT max(pubdate) as data_import FROM radars.equipment_files WHERE equipment = '".$nome_eqp."'";
  $resImport  = pg_query($conn_neogrid,$sqlImport);
  $infoImport = pg_fetch_assoc($resImport);
  $eqps[$nome_eqp]['last_file_imported'] = $infoImport['data_import'];



?>
<style>
.flot-x-axis .flot-tick-label {
    white-space: nowrap;
    transform: translate(-9px, 0) rotate(-60deg);
    text-indent: -100%;
    transform-origin: top right;
    text-align: right !important;

}
</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Visualização detalhada</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Radares</span></li>
        <li><span class=''><a href='#' ic-get-from='radar/index.php' ic-target='#wrap'> Equipamentos</a></span></li>
        <li><span class='text-muted'>Visualização</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>
                    </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
                      <table class="table table-hover mb-none">
												<thead>
													<tr>
														<th>#</th>
														<th>Equipamento</th>
                            <th>Endereço</th>
                            <th class='text-center'>Última atualização</th>
                            <th class='text-center'>Contagem de tráfego</th>
													</tr>
												</thead>
												<tbody>
<?
  foreach($eqps as $eqp => $info)
  {


    $datetime1 = date_create($info['last_file_imported']);
    $datetime2 = date_create($agora['datasrv']);
    $interval  = date_diff($datetime1, $datetime2);

    $classtd = "";
    if($interval->format('%a') >= 2){$classtd = "warning";}
    if($interval->format('%a') >= 5){$classtd = "danger";}

    echo "<tr id='".$info['id']."'>";
    echo "<td class='text-muted'>".$info['id']."</td>";
    echo "<td class=''>".$nome_eqp."</td>";
    echo "<td class=''>".$info['address']."</td>";
    echo "<td class='text-center ".$classtd."'>".formataData($info['last_file_imported'],1)." <sup>(".$interval->format('%R%a dias').")</sup></td>";
    echo "<td class='text-center'>".number_format($info['contador_veiculos'],0,'','.')."</td>";



    echo "</tr>";
  }

?>
								  </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>




              <div class="col-md-12">
              								<section class="panel">
              									<header class="panel-heading">
                                  <div class="panel-actions" style='margin-top:-12px'></div>
                                  <h2 class="panel-title">Evolução mensal</h2>
                                </header>
              									<div class="panel-body">

                              <?
                                for($dia = 1; $dia <= $agora['ultimo_dia']; $dia++)
                                {
                                  unset($valor);
                                  $valor = $eqps[$nome_eqp]['contagem'][$agora['ano']."-".$agora['mes']."-".str_pad($dia,2,"0",STR_PAD_LEFT)];
                                  if($valor==""){$valor=0;}
                                  $vetor[] = "[".$dia.", ".$valor."]";

                                  $legenda[] = "[".$dia.", '".$dia."/".$agora['mes']."']";
                                }
                                  $legenda_str = implode(",",$legenda);
                                  $vetor_str   = implode(",",$vetor);
                              ?>

										<div class="chart chart-md" id="flotBasic"></div>
										<script type="text/javascript">

											var flotBasicData = [{
												data: [<?=$vetor_str;?>],
												label: "Contagem de tráfego",
												color: "#2baab1"
											}];


										</script>

                                </div>
                              </section>
            </div>



<!-- Modal Warning -->
								<!--	<a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="4">Remover 1</a>
                  <a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="5">Remover 2</a>-->

									<div id="modalRemover" class="modal-block modal-header-color modal-block-warning mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Atenção</h2>
											</header>
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-warning"></i>
													</div>
													<div class="modal-text">
														<h4>Você tem certeza que deseja remover este cadastro?</h4>
														<p>Esta operação é permanente.</p>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
                            <button class="btn btn-warning modal-confirm">Remover</button>
														<button class="btn btn-default modal-dismiss">Cancelar</button>
													</div>
												</div>
											</footer>
										</section>
									</div>
</section>
<script>




(function( $ ) {

	'use strict';



  (function() {
    var plot = $.plot('#flotBasic', flotBasicData, {
      series: {
        lines: {
          show: true,
          fill: true,
          lineWidth: 1,
          fillColor: {
            colors: [{
              opacity: 0.45
            }, {
              opacity: 0.45
            }]
          }
        },
        points: {
          show: true
        },
        shadowSize: 0
      },
      grid: {
        hoverable: true,
        clickable: true,
        borderColor: 'rgba(0,0,0,0.1)',
        borderWidth: 1,
        labelMargin: 15,
        backgroundColor: 'transparent'
      },
      yaxis: {
        min: 0,
        color: 'rgba(0,0,0,0.1)'
      },
      xaxis: {
        color: 'rgba(0,0,0,0.1)',
        ticks:[<?=$legenda_str;?>]
      },
      tooltip: true,
      tooltipOpts: {
        content: '%s: Data: %x, Tráfego: %y',
        shifts: {
          x: -60,
          y: 25
        },
        defaultTheme: false
      }
    });
  })();


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
        //url: "usuarios/sqls.php",
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
