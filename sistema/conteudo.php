<?
  error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  session_start();
  require_once("libs/php/conn.php");
  require_once("libs/php/funcoes.php");
  require_once("libs/php/sessao.php");
  $agora = now();

  $sql = "SELECT * FROM sepud.resume";
  $res = pg_query($conn_neogrid,$sql);
  while($d = pg_fetch_assoc($res))
  {
    $infos[$d['module']][$d['type']][$d['field']] = $d['int_value'];
  }

/*
Array
(
    [roterizador] => Array
        (
            [evento] => Array
                (
                    [Inicialização do dispositivo] => 27
                    [Posição GPS] => 13012
                    [Temperatura (Graus Celsius)] => 13011
                )

            [resumo] => Array
                (
                    [eventos] => 52071
                    [equipamentos] => 2
                )

        )

    [radares] => Array
        (
            [resumo] => Array
                (
                    [equipamentos] => 100
                    [registros] => 2178
                )

        )

    [waze] => Array
        (
            [resumo] => Array
                (
                    [alertas] => 2298103
                    [congestionamentos] => 374443
                )

        )

)
*/

?>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Home</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">

                      <div class="col-md-6">
                        <div class="row">
                                  <section class="panel">
                                    <header class="panel-heading">
                                      <h3 class="panel-title">Sistemas digitais</h3>
                                    </header>
                                    <div class='panel-body'>
                                      <p>Portal para gestão e monitoramento das informações de mobilidade, gestão do OpenData Joinville e módulos de convênios.</p>
                                      <p class='text-right text-muted'><small>Inicio do desenvolvimento: 01/11/2018</small></p>
                                    </div>
                                  </section>
                        </div>
                        <div class="row">
                          <section class="panel">
                            <header class="panel-heading">


          <!--
                              <div class="panel-actions">
                                <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                              </div>
          -->
                              <h2 class="panel-title">Roterizador de frota - Eventos: <sup class="text-weight-semibold text-uppercase mb-md mt-lg text-muted">Filtro: <?=$agora['mes_txt_c']."/".$agora['ano'];?></sup></h2>
                            </header>
                            <div class="panel-body">
                              <div class="">
                                <?


                                      echo "<table class='table table-condensed table-striped'>";
                                      echo "<thead><tr><th>Descrição do evento</th>
                                                      <th>Quantidade de leituras</th>
                                            </tr></thead><tbody>";
                                    foreach($infos['roterizador']['evento'] as $evento => $val)
                                      {
                                          echo "<tr>";
                                            echo "<td>".$evento."</td>";
                                            echo "<td>".number_format($val,0,'','.')."</td>";
                                          echo "</tr>";

                                      }
                                      echo "</tbody></table>";
                                ?>
                              </div>

                              <!-- See: assets/javascripts/ui-elements/examples.charts.js for the example code. -->
                            </div>
                          </section>
                        </div>
                      </div>

                      <div class="col-md-6 col-lg-6 col-xl-4">
                      <h5 class="text-weight-semibold text-dark text-uppercase mb-md mt-lg">Informações do sistema: <sup class="text-muted">Filtro: <?=$agora['mes_txt_c']."/".$agora['ano'];?></sup></h5>

                              <section class="panel panel-featured-left panel-featured-primary">
                                <div class="panel-body">
                                  <div class="widget-summary widget-summary-sm">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                      <div class="summary-icon bg-primary">
                                        <i class="fa fa-bus"></i>
                                      </div>
                                    </div>
                                    <div class="widget-summary-col">
                                      <div class="summary">
                                        <h4 class="title">Roterizador</h4>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['roterizador']['resumo']['equipamentos'],0,'','.');?></strong>
                                          <span class="text-primary">equipamento(s) cadastrado(s)</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['roterizador']['resumo']['eventos'],0,'','.');?></strong>
                                          <span class="text-primary">eventos</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>



<?

?>
                              <section class="panel panel-featured-left panel-featured-primary">
                                <div class="panel-body">
                                  <div class="widget-summary widget-summary-sm">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                      <div class="summary-icon bg-primary">
                                        <i class="fa fa-warning"></i>
                                      </div>
                                    </div>
                                    <div class="widget-summary-col">
                                      <div class="summary">
                                        <h4 class="title">Radares</h4>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['radares']['resumo']['equipamentos'],0,'','.');?></strong>
                                          <span class="text-primary">equipamento(s) cadastrado(s)</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['radares']['resumo']['registros'],0,'','.');?></strong>
                                          <span class="text-primary">registros</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>



<?

?>
                              <section class="panel panel-featured-left panel-featured-primary">
                                <div class="panel-body">
                                  <div class="widget-summary widget-summary-sm">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                      <div class="summary-icon bg-primary">
                                        <i class="fa fa-road"></i>
                                      </div>
                                    </div>
                                    <div class="widget-summary-col">
                                      <div class="summary">
                                        <h4 class="title">Waze</h4>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['waze']['resumo']['alertas'],0,'','.');?></strong>
                                          <span class="text-primary">alertas</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount"><?=number_format($infos['waze']['resumo']['congestionamentos'],0,'','.');?></strong>
                                          <span class="text-primary">registros de congestionamentos</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>


                        </div>

</div>
<div class="row">
							<div class="col-md-6">

							</div>
</div>
<script>

</script>
