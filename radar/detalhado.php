<?
  session_start();
  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");

  if(isset($_POST['filtro_data']))
  {
    $filtro_data = mkt2date(date2mkt($_POST['filtro_data']));
  }else {
    $filtro_data = now();
  }
  $agora       = now();

  $id = $_GET['id'];



    $meses[1]['curto'] = "Jan";
    $meses[2]['curto'] = "Fev";
    $meses[3]['curto'] = "Mar";
    $meses[4]['curto'] = "Abr";
    $meses[5]['curto'] = "Mai";
    $meses[6]['curto'] = "Jun";
    $meses[7]['curto'] = "Jul";
    $meses[8]['curto'] = "Ago";
    $meses[9]['curto'] = "Set";
    $meses[10]['curto'] = "Out";
    $meses[11]['curto'] = "Nov";
    $meses[12]['curto'] = "Dez";


    $meses[1]['longo'] = "Janeiro";
    $meses[2]['longo'] = "Fevereiro";
    $meses[3]['longo'] = "Março";
    $meses[4]['longo'] = "Abril";
    $meses[5]['longo'] = "Maio";
    $meses[6]['longo'] = "Junho";
    $meses[7]['longo'] = "Julho";
    $meses[8]['longo'] = "Agosto";
    $meses[9]['longo'] = "Setembro";
    $meses[10]['longo'] = "Outubro";
    $meses[11]['longo'] = "Novembro";
    $meses[12]['longo'] = "Dezembro";



    $filtro_sql = " EF.pubdate >= '".$filtro_data['ano']."-".$filtro_data['mes']."-01'";
    $txt_filtro = "Referência: ".$filtro_data['mes_txt_c']."/".$filtro_data['ano'];

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
                    Mês de referência: <b><?=$filtro_data['mes_txt']."/".$filtro_data['ano'];?></b>
                    <div class="panel-actions">
                      <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_filtro">
                        Filtros
                      </button>
                    </div>
                  </header>
									<div class="panel-body">

                    <?
                      print_r_pre($_POST);
                    ?>
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





            <div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="modal_filtro" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Filtros de pesquisa</h5>
                  </div>
                  <form id="filtro" name="filtro" method="post" action="radar/detalhado.php?id=<?=$id;?>">
                  <div class="modal-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                  <label for="filtro_data">Período:</label>
                                      <select id="filtro_data" name="filtro_data" class="form-control">

                                         <?
                                          for($a = 2017; $a <= $agora['ano']; $a++)
                                          {
                                              echo "<optgroup label='".$a."'>";

                                                if($a == $agora['ano']){ $mes_ate = date('n'); }
                                                else                   { $mes_ate = 12;        }

                                                for($m = 1; $m <= $mes_ate; $m++)
                                                {
                                                    if($a == $agora['ano'] && $m == $mes_ate){ $sel = "selected"; }

                                                    echo  "<option value='01/".$m."/".$a." 00:00:00' ".$sel.">".$meses[$m]['longo']."/".$a."</option>";
                                                }
                                              echo "</optgroup>";

                                          }
                                         ?>
                                      </select>
                                </div>
                            </div>
                          </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary"   data-dismiss="modal">Filtrar</button>
                  </div>
                 </form>
                </div>
              </div>
            </div>
</section>
<script>




(function( $ ) {

	'use strict';



  (function() {

    $('#modal_filtro').on('hidden.bs.modal', function (e) {
        $("#filtro").submit();
    })


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
