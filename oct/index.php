<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");



  logger("Acesso","OCT", "Sistema - página inicial");

  $agora = now();
  $sql   = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['company_id']." AND closed is null";
  $res   = pg_query($sql)or die("Erro ".__LINE__);
  $turno = pg_fetch_assoc($res);
  $ano   = substr($turno['opened'],0,4);


?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registro de Ocorrências de Trânsito e Segurança</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Gestão do sistema</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions"></div>
            </header>
            <div class="panel-body">
                <div class="row">
                  <div class="col-sm-8">

                      <div class="row">
                          <div class="col-sm-12">
                      <?

                          if(pg_num_rows($res))
                          {
                            echo "<section class='panel panel-horizontal'>
                 <header class='panel-heading bg-success' style='width:150px'>
                   <div class='panel-heading-icon'>
                     <i class='fa fa-cogs'></i>
                   </div>
                 </header>

                 <div class='panel-body p-lg'>
                        <table class='table table-condensed'>
                          <tr>
                            <td class='text-muted text-left'>Turno:</td>
                            <td colspan='2' style='white-space:nowrap;vertical-align: middle;'><b>".str_pad($turno['id'],5,"0",STR_PAD_LEFT)."</b></td>
                          </tr>
                          <tr>
                              <td class='text-muted' style='white-space:nowrap;vertical-align: middle;'>Data de abertura:</td>
                              <td                     style='white-space:nowrap;vertical-align: middle;'><b>".formataData($turno['opened'],1)."</b> (".$turno['period'].")</td>
                              <td>
                                <a href='oct/turno_sql.php?id=".$turno['id']."&acao=fechar'><button id='bt_fechar_turno'    type='button' class='mb-xs mt-xs mr-xs btn btn-sm btn-warning'>Fechar</button></a>
                                <a href='oct/turno.php?id=".$turno['id']."'><button id='bt_atualizar_turno' type='button' class='mb-xs mt-xs mr-xs btn btn-sm btn-primary'>Atualizar</button></a>
                              </td>
                          </tr>
                          <tr><td class='text-muted'>Observações:</td>
                          <td colspan='2'>".$turno['observation']."</td></tr>
                        </table>

                 </div>
               </section>";
                          }else{
                             echo "<section class='panel panel-horizontal'>
									<header class='panel-heading bg-default' style='width:150px'>
										<div class='panel-heading-icon'>
											<i class='fa fa-cogs'></i>
										</div>
									</header>
									<div class='panel-body p-lg text-center'>
                    <a href='oct/turno.php'>
                        <button type='button' class='mb-xs mt-xs mr-xs btn btn-primary'>Abrir novo turno de trabalho</button>
                    </a>
									</div>
								</section>";
                          }
                      ?>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-12">
                          <?

                          echo "<section class='panel panel-horizontal'>
                          <header class='panel-heading bg-info' style='width:150px'>
                          <div class='panel-heading-icon'>
                          <i class='fa fa-users'></i>
                          </div>
                          </header>

                          <div class='panel-body p-lg'>

                                <table class='table table-condensed'>
                                <thead><tr><th>Viaturas designadas e agentes de campo</th></tr></thead>
                                <tbody>
                                  <tr>
                                    <td style='white-space:nowrap;vertical-align: middle;'>agente xxxx</td>
                                  </tr>
                                </tbody>
                                </table>

                          </div>
                          </section>";


                          echo "<section class='panel panel-horizontal'>
                          <header class='panel-heading bg-info' style='width:150px'>
                          <div class='panel-heading-icon'>
                          <i class='fa fa-user'></i>
                          </div>
                          </header>

                          <div class='panel-body p-lg'>

                                <table class='table table-condensed'>
                                <thead><tr><th>Coordenação e direção</th></tr></thead>
                                <tbody>
                                  <tr>
                                    <td style='white-space:nowrap;vertical-align: middle;'>agente xxxx</td>
                                  </tr>
                                </tbody>
                                </table>

                          </div>
                          </section>";

                          echo "<section class='panel panel-horizontal'>
                          <header class='panel-heading bg-info' style='width:150px'>
                          <div class='panel-heading-icon'>
                          <i class='fa fa-phone-square'></i>
                          </div>
                          </header>

                          <div class='panel-body p-lg'>

                                <table class='table table-condensed'>
                                <thead><tr><th>Central de atendimento</th></tr></thead>
                                <tbody>
                                  <tr>
                                    <td style='white-space:nowrap;vertical-align: middle;'>agente xxxx</td>
                                  </tr>
                                </tbody>
                                </table>

                          </div>
                          </section>";

                          ?>
                      </div>
                  </div>

                  </div>
                  <div class="col-sm-4">
                      ...<br><br><br><br><br><br><br>...
                  </div>
                </div>
            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

</section>

<script>
$("#bt_fecsssshar_turno").click(function(){
  var url = "oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar";
  $("#wrap").load(url);
});
</script>
