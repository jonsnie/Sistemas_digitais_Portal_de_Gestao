<?
  error_reporting(E_ALL);
  session_start();



  $sql="SELECT (SELECT count(*) FROM equipamentos) as qtd_equipamentos, (SELECT count(*) FROM leituras) as qtd_leituras";
  $res=mysqli_query($connsystem,$sql);
  $DadosRoterizador=mysqli_fetch_array($res,MYSQLI_ASSOC);



?>
<section role="main" class="content-body">

  <header class="page-header">
    <h2>Dashboard</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Dashboard</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->




  <div class="row">

                      <div class="col-md-6">
                        <section class="panel">
                          <header class="panel-heading">
                            <h3 class="panel-title">SEPUD - Digital</h3>
                          </header>
                          <div class='panel-body'>
                            <p>Portal da Secretaria de Planejamento Urbano e Desenvolvimento Sutentável de Joinville, Santa Catariana, para gestão e monitoramento das informações de mobilidade, gestão do OpenData Joinville e módulos de convênios.</p>
                            <p class='text-right text-muted'><small>Inicio do desenvolvimento: 01/11/2018</small></p>
                          </div>
                        </section>
                      </div>

                      <div class="col-md-6 col-lg-6 col-xl-4">
                      <h5 class="text-weight-semibold text-dark text-uppercase mb-md mt-lg">Informações do sistema:</h5>

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
                                          <strong class="amount"><?=$DadosRoterizador['qtd_equipamentos'];?></strong>
                                          <span class="text-primary">equipamento(s) cadastrado(s)</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount"><?=$DadosRoterizador['qtd_leituras'];?></strong>
                                          <span class="text-primary">eventos</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>



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
                                          <strong class="amount">0</strong>
                                          <span class="text-primary">equipamento(s) cadastrado(s)</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount">0</strong>
                                          <span class="text-primary">registros</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>

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
                                          <strong class="amount">0</strong>
                                          <span class="text-primary">alertas</span>
                                        </div>
                                        <div class="info">
                                          <strong class="amount">0</strong>
                                          <span class="text-primary">registros de congestinamentos</span>
                                        </div>
                                      </div>

                                    </div>
                                  </div>
                                </div>
                              </section>


                        </div>

</div>

</section>
