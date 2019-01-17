<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $agora = now();
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
      <h2><i class="fa fa-bar-chart"></i> Dashboard</h2>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><span class='text-muted'>Ocorrências de trânsito</span></li>
          <li><span class='text-muted'>Dashboard</span></li>
        </ol>
      </div>
    </header>
    <section class="panel">
      <header class="panel-heading">
        <div class="panel-actions" style='margin-top:-12px'><?=$agora['mes_txt'].", ".$agora['ano'];?></div>
      </header>
      <div class="panel-body" style="min-height:600px">
      <div class="row">
        <div class="col-sm-12">
        <?
            $sql = "SELECT
                    	uuid, type, subtype,
                    	date_part('day',pub_utc_date) as dia,
                    	date_part('month',pub_utc_date) as mes,
                    	date_part('year',pub_utc_date) as ano,
                    	count(*)
                    	FROM waze.alerts
                    	WHERE pub_utc_date BETWEEN '2018-12-01 00:00:00.000' AND '2018-12-31 23:59:59.999'
                    	AND type = 'ACCIDENT'
                    	GROUP BY type, subtype, uuid,	date_part('month',pub_utc_date),	date_part('year',pub_utc_date), date_part('day',pub_utc_date)
                      ORDER BY date_part('day',pub_utc_date) ASC";
            $res = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
            while($d = pg_fetch_assoc($res))
            {
              $reports[$d['dia']]++;
              $d['subtype'] = ($d['subtype'] == "" ? "ACCIDENT" : $d['subtype']);
              $tipos[$d['subtype']]++;
              $tipos['total']++;
            }

              for($dia = 1; $dia <= $agora['ultimo_dia']; $dia++)
              {
                unset($valor);
                $valor = $reports[$dia];
                if($valor==""){$valor=0;}
                $vetor[] = "[".$dia.", ".$valor."]";

                $legenda[] = "[".$dia.", '".$dia."/".$agora['mes']."']";
              }
                $legenda_str = implode(",",$legenda);
                $vetor_str   = implode(",",$vetor);


        ?>
        <h4>Waze - Reportes de acidentes <small><sup>(mês atual)</sup></small></h4>
        <div class="chart chart-md" id="flotBasic" style="height:200px"></div>
        <script type="text/javascript">

          var flotBasicData = [{
            data: [<?=$vetor_str;?>],
            label: "Quantidade de reportes",
            color: "#2baab1"
          }];


        </script>
      </div>
    </div>
    <div class="row" style="margin-top:40px">
      <div class="col-sm-6">
          <h4>Waze - Reportes detalhados: <small><sup>(mês atual)</sup></small></h4>
          <table class="table">
            <tbody>
              <tr><td>Acidente</td><td class="text-center"><?=$tipos['ACCIDENT'];?></td></tr>
              <tr><td>Acidente menor</td><td class="text-center"><?=$tipos['ACCIDENT_MINOR'];?></td></tr>
              <tr><td>Acidente maior</td><td class="text-center"><?=$tipos['ACCIDENT_MAJOR'];?></td></tr>
              <tr><td class="text-muted text-right">Total:</td><td class="text-center"><?=$tipos['total'];?></td></tr>
            </tbody>
          </table>
      </div>
      <div class="col-sm-6">
          <h4></h4>
          <?
            $sql = "SELECT count(*) as qtd
                    FROM sepud.oct_events EV
                    WHERE
                    EV.DATE BETWEEN '2018-12-01 00:00:00' AND '2018-12-31 23:59:59'";
            $res = pg_query($conn_neogrid,$sql) or die("Error ".__LINE__);
            $d   = pg_fetch_assoc($res);

          ?>

          <section class="panel panel-featured-left panel-featured-primary">
            <div class="panel-body">
              <div class="widget-summary widget-summary-sm">
                <div class="widget-summary-col widget-summary-col-icon">
                  <div class="summary-icon bg-warning">
                    <i class="fa fa-exclamation-triangle"></i>
                  </div>
                </div>
          <div class="widget-summary-col">
            <div class="summary">
              <h4 class="title">Ocorrências geradas/atendidas: <sup>(mês atual)</sup></h4>
              <div class="info">
                <strong class="amount"><?=number_format($d['qtd'],0,'','.');?></strong>
                <span class="text-primary">Ocorrência(s)</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      </section>




      </div>
    </div>

  </div>
    </section>
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
        content: '%s: Data: %x, Reports: %y',
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
