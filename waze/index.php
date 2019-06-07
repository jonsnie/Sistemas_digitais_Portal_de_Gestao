<?
  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  //error_reporting(E_ALL);
  session_start();
  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");

    logger("Acesso","WAZE - Dashboard");

if(isset($_POST['waze_filtro_data']))
{
  $filtro_data = mkt2date(date2mkt($_POST['waze_filtro_data']));
}else {
  $filtro_data = now();
}
  $agora = now();

  $filtro_data = mkt2date(date2mkt('01/05/2019'));


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



  $sql = "SELECT
        			 type, subtype,
        			 COUNT(*) AS qtd,
        			 date_part('day', pub_utc_date)   as dia,
        			 date_part('month', pub_utc_date) as mes,
        			 date_part('year', pub_utc_date)  as ano
          FROM
          	   waze.alerts
          WHERE
          	   pub_utc_date BETWEEN '".$filtro_data['ano']."-".$filtro_data['mes']."-01 00:00:00' AND '".$filtro_data['ano']."-".$filtro_data['mes']."-".$filtro_data['ultimo_dia']." 23:59:59'
          GROUP BY
            	type, subtype,
            	date_part('day', pub_utc_date),
            	date_part('month', pub_utc_date),
            	date_part('year', pub_utc_date)";
          $res=pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
  while($d = pg_fetch_assoc($res))
  {
  //  print_r_pre($d);
    $vet_qtd['dias'][$d['dia']]['total'] += $d['qtd'];
    $vet_qtd['mes'][$d['mes']] += $d['qtd'];

    if($d['subtype']==""){$d['subtype']=$d['type'];}

    $vet_qtd['dias'][$d['dia']]['infos'][$d['type']]['total'] += $d['qtd'];
    $vet_qtd['dias'][$d['dia']]['infos'][$d['type']][$d['subtype']] += $d['qtd'];

    $reports[$d['dia']] += $d['qtd'];
    $reports_tipo[$d['type']] += $d['qtd'];

    if($d['type']=="WEATHERHAZARD"){
      $reports_alertas[$d['subtype']] += $d['qtd'];
    }

  }

  for($dia = 1; $dia <= $filtro_data['ultimo_dia']; $dia++)
  {
    unset($valor);
    $valor = $reports[$dia];
    if($valor==""){$valor=0;}
    $vetor[] = "[".$dia.", ".$valor."]";

    $legenda[] = "[".$dia.", '".$dia."/".$filtro_data['mes']."']";
  }
    $legenda_str = implode(",",$legenda);
    $vetor_str   = implode(",",$vetor);
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
    <h2>Dashboard</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Waze</span></li>
        <li><span class='text-muted'>Dashboard</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    Mês de referência: <b><?=$filtro_data['mes_txt']."/".$filtro_data['ano'];?></b>
                    <div class="panel-actions">
<!--
                      <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" data-toggle="modal" data-target="#waze_modal_filtro">
                        Filtros
                      </button>
-->
									  </div>
                  </header>
									<div class="panel-body">
  <div class="row">
    <div class="col-sm-6" style="margin-left:10px">
        <h4>Informações adicionais:</h4>
        <table class="table">
          <thead><tr><th colspan="2">Descrição</th></thead>
          <tbody>
            <tr>
                <td>Registro de alertas de buraco na via:</td>
                <td class="text-danger"><strong><?=number_format($reports_alertas['HAZARD_ON_ROAD_POT_HOLE'],0,'','.');?></strong></td>
            </tr>
            <tr>
                <td>Última sincronização:</td>
                <td>
                    <? $sql = "SELECT MAX(date_created) as dataupd FROM waze.data_files";
                       $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
                       $d   = pg_fetch_assoc($res);
                       echo formataData($d['dataupd'],1)." <sup><strong>(UTC)</strong></sup>";
                    ?>
                </td>
            </tr>
          </tbody>
        </table>
    </div>
    <div class="col-sm-6" style="margin-top:160px;margin-left:-10px">
<!--
          Alertas ATIVOS:
          "SELECT type, subtype, count(*) as qtd
          FROM alerts WHERE datafile_id = (SELECT id FROM data_files ORDER BY id DESC LIMIT 1)
          GROUP BY type, subtype;"
-->
    </div>
  </div>


<!----------------------------------------------------------------------------->
<div class="row">
    <div class="col-sm-12">
      <h5>Quantidade de registros no banco de dados</h5>

<div class="chart chart-md" id="flotBasic" style="height:200px"></div>
<script type="text/javascript">

  var flotBasicData = [{
    data: [<?=$vetor_str;?>],
    color: "#2baab1"
  }];
</script>
</div>
<div>
<!----------------------------------------------------------------------------->
<div class="row">
  <div class="col-sm-4" style="margin-top:40px;margin-left:10px">
<?

//print_r_pre($_GET);
//print_r_pre($_POST);
//print_r_pre($reports_tipo);

foreach($reports_tipo as $tipo => $qtd)
{
  switch($tipo)
  {
     case "ACCIDENT":      $tipo = "Acidente";         break;
     case "JAM":           $tipo = "Congestionamento"; break;
     case "ROAD_CLOSED":   $tipo = "Rua interditada";  break;
     case "WEATHERHAZARD": $tipo = "Alertas";          break;
  }

  $vetaux[] = "['".$tipo."',".$qtd."]";
}

$reports_tipo_str = implode(",",$vetaux);

//echo $reports_tipo_str;
?>
  <h5>Quantidade por tipo</h5>
    <div class="chart chart-md" id="graf_tipos" style="height:200px"></div>
    <script type="text/javascript">

      var flotBarsData = [<?=$reports_tipo_str;?>];
      (function() {
  var plot = $.plot('#graf_tipos', [flotBarsData], {
    colors: ['#8CC9E8'],
    series: {
      bars: {
        show: true,
        barWidth: 0.8,
        align: 'center'
      }
    },
    xaxis: {
      mode: 'categories',
      tickLength: 0
    },
    grid: {
      hoverable: true,
      clickable: true,
      borderColor: 'rgba(0,0,0,0.1)',
      borderWidth: 1,
      labelMargin: 15,
      backgroundColor: 'transparent'
    },
    tooltip: true,
    tooltipOpts: {
      content: '%y',
      shifts: {
        x: -10,
        y: 20
      },
      defaultTheme: false
    }
  });
})();
    </script>



  </div>
  <div class="col-sm-8" style="margin-top:40px;margin-left:-10px">
    <h5>Alertas <small>(Exceto buraco na via)</small></h5>
    <div class="chart chart-md" id="graf_alertas" style="height:200px"></div>
      <?
        unset($vetaux);
        foreach($reports_alertas as $tipo => $qtd)
        {
          if($tipo != "HAZARD_ON_ROAD_POT_HOLE")
          {
            switch($tipo)
            {
               case "WEATHERHAZARD":                      $tipo = "Perigo";             break;
               case "HAZARD_ON_ROAD":                     $tipo = "Perigo na via";      break;
               case "HAZARD_ON_ROAD_CAR_STOPPED":         $tipo = "Veículo parado";     break;
               case "HAZARD_ON_ROAD_CONSTRUCTION":        $tipo = "Via em construção";  break;
               case "HAZARD_ON_ROAD_ICE":                 $tipo = "Gelo na via";        break;
               case "HAZARD_ON_ROAD_OBJECT":              $tipo = "Objeto na via";      break;
               case "HAZARD_ON_ROAD_TRAFFIC_LIGHT_FAULT": $tipo = "Semáforo queimado";         break;
               case "HAZARD_WEATHER":                     $tipo = "Clima perigoso";            break;
               case "HAZARD_WEATHER_FLOOD":               $tipo = "Inundação";                 break;
               case "HAZARD_WEATHER_FOG":                 $tipo = "Neblina";                   break;
               case "HAZARD_ON_SHOULDER_MISSING_SIGN":    $tipo = "Sinalização perdida";       break;
               case "HAZARD_ON_SHOULDER_CAR_STOPPED":     $tipo = "Veículo parado sobre a via";break;
               case "HAZARD_ON_SHOULDER_ANIMALS":         $tipo = "Animal na via";             break;

            }
            $vetaux[] = "['".$tipo."',".$qtd."]";
          }
        }
        $reports_alertas_str = implode(",",$vetaux);
        //echo $reports_alertas_str;
      ?>

      <script type="text/javascript">

        var flotBarsData = [<?=$reports_alertas_str;?>];
        (function() {
    var plot = $.plot('#graf_alertas', [flotBarsData], {
      colors: ['#FFC9C9'],
      series: {
        bars: {
          show: true,
          barWidth: 0.8,
          align: 'center'
        }
      },
      xaxis: {
        mode: 'categories',
        tickLength: 0
      },
      grid: {
        hoverable: true,
        clickable: true,
        borderColor: 'rgba(0,0,0,0.1)',
        borderWidth: 1,
        labelMargin: 15,
        backgroundColor: 'transparent'
      },
      tooltip: true,
      tooltipOpts: {
        content: '%y',
        shifts: {
          x: -10,
          y: 20
        },
        defaultTheme: false
      }
    });
  })();
      </script>
  </div>
</div>
<!----------------------------------------------------------------------------->






										</div>
									</div>
                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
								</section>
							</div>
</section>





<div class="modal fade" id="waze_modal_filtro" tabindex="-1" role="dialog" aria-labelledby="waze_modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="waze_form_filtro" name="waze_form_filtro" method="post" action="waze/index.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Período:</label>
                          <select id="waze_filtro_data" name="waze_filtro_data" class="form-control">

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
                          <input type="hidden" id="popup_text" value="Filtrando resultado.">
                          <input type="hidden" id="popup_type" value="success">
                    </div>
                </div>
              </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary"   data-dismiss="modal" id="bt_submit">Filtrar</button>
      </div>
     </form>
    </div>
  </div>
</div>


<script>
(function( $ ) {
	'use strict';
  (function() {

    $('#bt_submit').on('click', function(e) {
         e.preventDefault();

         $("#waze_modal_filtro").removeClass("in");
         $(".modal-backdrop").remove();
         $('body').removeClass('modal-open');
         $('body').css('padding-right', '');
         $("#waze_modal_filtro").hide();

         $("#waze_form_filtro").submit();
         return false;

    });

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
        content: '%s: Data: %x, Registros: %y',
        shifts: {
          x: -60,
          y: 25
        },
        defaultTheme: false
      }
    });
  })();
    }).apply( this, [ jQuery ]);
</script>
