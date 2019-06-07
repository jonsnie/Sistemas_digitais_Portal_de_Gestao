<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");


  logger("Acesso","ROTS - Acompanhamento mensal");
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


  if(isset($_POST['filtro_data']))
  {
    $filtro_data = mkt2date(date2mkt($_POST['filtro_data']));
  }else {
    $filtro_data = now();
  }

    $sql = "SELECT
              uuid, type, subtype,
              date_part('day',pub_utc_date) as dia,
              date_part('month',pub_utc_date) as mes,
              date_part('year',pub_utc_date) as ano,
              count(*)
              FROM waze.alerts
              WHERE pub_utc_date BETWEEN '".$filtro_data["ano"]."-".$filtro_data["mes"]."-01 00:00:00.000' AND '".$filtro_data["ano"]."-".$filtro_data["mes"]."-".$filtro_data["ultimo_dia"]." 23:59:59.999'
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
<section role="main" class="content-body ">
    <header class="page-header">
      <h2><i class="fa fa-bar-chart"></i> Evolução mensal</h2>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php" ajax="false"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><span class='text-muted'>Ocorrências de trânsito</span></li>
          <li><span class='text-muted'>Evolução mensal</span></li>
        </ol>
      </div>
    </header>
    <section class="panel box_shadow">
      <header class="panel-heading" style="height:70px">
        <span class="text-muted">Referência: </span><b><?=$filtro_data['mes_txt'].", ".$filtro_data['ano'];?></b>
        <div class="panel-actions" style='margin-top:10px'>
            <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary" data-toggle="modal" data-target="#modal_filtro">Filtros</button>
        </div>
      </header>
      <div class="panel-body" style="min-height:600px">
    <div class="row">
      <div class="col-sm-4">
          <h4>Waze - Reportes detalhados:</h4>
          <table class="table">
            <tbody>
              <tr><td>Acidente</td>      <td class="text-right" width='10px'><?=$tipos['ACCIDENT'];?></td></tr>
              <tr><td>Acidente menor</td><td class="text-right"><?=$tipos['ACCIDENT_MINOR'];?></td></tr>
              <tr><td>Acidente maior</td><td class="text-right"><?=$tipos['ACCIDENT_MAJOR'];?></td></tr>
              <tr><td class="text-muted text-right">Total:</td><td class="text-right"><?=$tipos['total'];?></td></tr>
            </tbody>
          </table>
      </div>
      <div class="col-sm-4">
          <h4>SGO - Detalhamento por tipo:</h4>
          <?
            unset($sql, $res, $d, $orgao, $oc);
            $sql = "SELECT C.acron as company_acron,
                    			 T.name event_name, T.type as event_type FROM sepud.oct_events E
                    JOIN sepud.oct_event_type T ON T.id = E.id_event_type
                    JOIN sepud.users          U ON U.id = E.id_user
                    JOIN sepud.company        C ON C.id = E.id_company
                    WHERE E.date BETWEEN '".$filtro_data["ano"]."-".$filtro_data["mes"]."-01 00:00:00'
                                     AND '".$filtro_data["ano"]."-".$filtro_data["mes"]."-".$filtro_data["ultimo_dia"]." 23:59:59'";
            $res = pg_query($conn_neogrid,$sql) or die("Error ".__LINE__."<br>".$sql);
            while($d   = pg_fetch_assoc($res))
            {
              $orgao[$d['company_acron']]++;
              $oc[$d["event_type"]]++;
              $oc_nomes[$d['company_acron']][$d["event_name"]]++;
              $total_oc_sistema++;
            }

          echo "<table class='table'>";
          if(isset($oc) && count($oc))
          {
                foreach ($oc as $key => $value)
                {
                    echo "<tr><td>".$key."</td>";
                    echo "<td class='text-right' width='10px'>".$value."</td></tr>";
                }
                echo "<tr><td class='text-muted text-right'>Total:</td><td class='text-right'>".$total_oc_sistema."</td></tr>";
          }else {
            echo "<tr><td><div class='alert alert-warning text-center'>Nenhum registro para esta data.</div></td></tr>";
          }
          echo "</table>";
      ?>
      </div>
        <div class="col-sm-4">
          <h4>SGO - Detalhamento por orgão:</h4>
          <?
              echo "<table class='table'>";
              if(isset($orgao) && count($orgao))
              {
                    foreach ($orgao as $key => $value)
                    {
                        echo "<tr><td>".$key."</td>";
                        echo "<td class='text-right' width='10px'>".$value."</td></tr>";
                    }
                    echo "<tr><td class='text-muted text-right'>Total:</td><td class='text-right'>".$total_oc_sistema."</td></tr>";
              }else {
                echo "<tr><td><div class='alert alert-warning text-center'>Nenhum registro para esta data.</div></td></tr>";
              }
              echo "</table>";
          ?>
        </div>



    </div>

    <div class="row">
      <div class="col-sm-4 text-center">
        <h5>Quantidade de reportes de acidentes WAZE<br><small>versus</small><br>Ocorrências de trânsito</h5>
            <div style="margin-top:-25px" class="text-center">
    			       <canvas id="gaugeBasic"  height="110" data-plugin-options='{ "value": <?=($oc['Acidente de trânsito']*100/$tipos['total']);?>, "maxValue": 100 }'></canvas>
    		    </div>
        <p><b><?=round($oc['Acidente de trânsito']*100/$tipos['total'],2);?>% atendido</b></p>
      </div>
      <div class="col-sm-8 text-center">

        <h4>Waze - Reportes de acidentes <small><sup>(mês atual)</sup></small></h4>
        <div class="chart chart-md" id="flotBasic" style="height:150px"></div>
        <script type="text/javascript">

          var flotBasicData = [{
            data: [<?=$vetor_str;?>],
            label: "Quantidade de reportes",
            color: "#2baab1"
          }];


        </script>
      </div>

    </div>

    <div class="row" style="margin-top:20px">
      <div class="col-sm-12">

        <?
            echo "<table class='table'>";
            if(isset($oc_nomes) && count($oc_nomes))
            {
                      foreach ($oc_nomes as $org => $ocs)
                      {

                            echo "<tr class='warning'>
                                  <td><h5><b>".$org."</b></h5></td>
                                  <td width='10px' class='text-center'>".$orgao[$org]."</td>";
                            echo "<td width='10px' class='text-center'>".round($orgao[$org]*100/$total_oc_sistema,1)."%</td>";
                            echo "</tr>";

                            echo "<tr><th>Tipificação</th><th class='text-center'>Qtd.</th><th class='text-center'>%</th></tr>";
                                foreach ($ocs as $oc => $qtd) {
                                    echo "<tr><td>".$oc."</td>";
                                    echo "<td class='text-center'>".$qtd."</td>";
                                    echo "<td class='text-center'>".round($qtd*100/$total_oc_sistema,1)."%</td>";
                                    echo "</tr>";
                                }
                      }

              }else{
                echo "<tr><td><div class='alert alert-warning text-center'>Nenhum registro para esta data.</div></td></tr>";
              }
            echo "</table>";
        ?>


      </div>


    </div>

  </div>
    </section>
</section>


<div class="modal fade" id="modal_filtro" tabindex="-1" role="dialog" aria-labelledby="modal_filtro" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Filtros de pesquisa</h5>
      </div>
      <form id="filtro" name="filtro" method="post" action="oct/dashboard.php">
      <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      <label for="filtro_data">Período:</label>
                          <select id="filtro_data" name="filtro_data" class="form-control">
                             <?
                              for($a = 2017; $a <= $filtro_data['ano']; $a++)
                              {
                                  echo "<optgroup label='".$a."'>";

                                    if($a == $filtro_data['ano']){ $mes_ate = date('n'); }
                                    else                   { $mes_ate = 12;        }

                                    for($m = 1; $m <= $mes_ate; $m++)
                                    {
                                        if($a == $filtro_data['ano'] && $m == $mes_ate){ $sel = "selected"; }

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




  (function() {


    $('#bt_submit').click(function(e) {
        e.preventDefault();
         $("#modal_filtro").removeClass("in");
         $(".modal-backdrop").remove();
         $('body').removeClass('modal-open');
         $('body').css('padding-right', '');
         $("#modal_filtro").hide();

         $("#filtro").submit();
        return false;
    });


  		var target = $('#gaugeBasic'),
  			opts = $.extend(true, {}, {
  				lines: 12, // The number of lines to draw
  				angle: 0.12, // The length of each line
  				lineWidth: 0.5, // The line thickness
  				pointer: {
  					length: 0.7, // The radius of the inner circle
  					strokeWidth: 0.05, // The rotation offset
  					color: '#444' // Fill color
  				},
  				limitMax: 'true', // If true, the pointer will not go past the end of the gauge
  				colorStart: '#0088CC', // Colors
  				colorStop: '#0088CC', // just experiment with them
  				strokeColor: '#F1F1F1', // to see which ones work best for you
  				generateGradient: true
  			}, target.data('plugin-options'));

  			var gauge = new Gauge(target.get(0)).setOptions(opts);

  		gauge.maxValue = opts.maxValue; // set max gauge value
  		gauge.animationSpeed = 60; // set animation speed (32 is default value)
  		gauge.set(opts.value); // set actual value
  		//gauge.setTextField(document.getElementById("gaugeBasicTextfield"));
  	})();





    }).apply( this, [ jQuery ]);
</script>
