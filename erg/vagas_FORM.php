<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  $agora = now();

  $sql = "SELECT P.*
          FROM sepud.eri_parking P
          WHERE P.id = ".$_GET['id'];

  $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
  $dados = pg_fetch_assoc($res);
  logger("Acesso","SERP - Visualização das vagas detalhado","Vaga ID: ".$_GET['id']);
?>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Sistema de Estacionamento Rotativo Público</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><a href="erg/vagas.php"><span>SERP - Vagas</span></a></li>
        <li><span>SERP - Vagas detalhado</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

  <!-- start: page -->
  <div class="row">
    <div class="col-md-12 col-lg-12 col-xl-12">
      <section class="panel">
        <header class="panel-heading">
          <div class="panel-actions" style='margin-top:-12px'>
          </div>
        </header>

        <div class="panel-body">

          <div class="row">
            <div class="col-md-6 col-md-offset-3">

            </div>
          </div>


                  <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                			<form id="form" name="form" class="form-horizontal" method="post" action="erg/vagas_FORM_sql.php" debug='0'>

                       <h4 class="mb-xlg">Informações cadastrais da vaga nº <b><?=$dados['name'];?></b></h4>
	                     <fieldset>
                        <div class="form-group">
                          <label class="col-md-2 control-label" for="ativa">Status:</label>
                          <div class="col-md-10">
                            <select class="form-control" name="active">
                                <option value="true"  <?=($dados['active']=="t"?"selected":"");?>>Vaga ativa</option>
                                <option value="false" <?=($dados['active']=="f"?"selected":"");?>>Vaga desativada</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="type">Tipo:</label>
                          <div class="col-md-10">
                            <select class="form-control" name="type">
                                <?
                                    $sql = "SELECT * FROM sepud.eri_parking_type ORDER BY id ASC";
                                    $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
                                    while($d = pg_fetch_assoc($res))
                                    {
                                      if($dados["id_parking_type"] == $d["id"]){ $sel = "selected"; }else{ $sel = "";}
                                      echo "<option value='".$d['id']."' ".$sel.">";
                                        echo $d["type"]." [".$d['time']."min]";
                                      echo "</option>";
                                    }
                                ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="ativa">Logradouro:</label>
                          <div class="col-md-10">
                            <select class="form-control" name="street">
                                <?
                                    $sql = "SELECT * FROM sepud.streets WHERE is_rotate_parking = 't' ORDER BY name ASC";
                                    $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__);
                                    while($d = pg_fetch_assoc($res))
                                    {
                                      if($dados["id_street"] == $d["id"]){ $sel = "selected"; }else{ $sel = "";}
                                      echo "<option value='".$d['id']."' ".$sel.">";
                                        echo $d["name"];
                                      echo "</option>";
                                    }
                                ?>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="ativa">Descrição:</label>
                          <div class="col-md-10">
                            <textarea class="form-control" name="description" placeholder="exemplo: próx. ao num. xxx, em frente a yyy"><?=$dados["description"];?></textarea>
                          </div>
                        </div>
                    </fieldset>

  											<div class="panel-footer" style="margin-top:20px;height:60px">
  												<div class="row pull-right">
  														<div class="col-md-12">
                                <input type="hidden" name="id" value="<?=$dados['id'];?>" />
                                <a href="erg/vagas.php"><button type='button' class='btn btn-default  loading'>Voltar</button></a>
                                <button type='submit' class='btn btn-primary  loading'>Atualizar</button>
                              </div>
                          </div>
                       </div>

                      </form>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">

                        <?
                            $sql = "SELECT * FROM sepud.eri_schedule_parking SP  WHERE id_parking = '".$dados['id']."' ORDER BY timestamp DESC LIMIT 100";
                            $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__."<br>".$sql);


                            if(pg_num_rows($res))
                            {
                                  echo "<h4>Histórico de utilização: <sup><small>(Últimos 100 registros)</small></sup></h4>";
                                  echo "<table class='table table-striped table-hover'>";
                                  echo "<thead><tr>";
                                  echo "<th>#</th><th>Placa</th><th>Entrada</th><th>Baixa</th><th>Notificado</th><th>Guinchado</th><th>Obs</th>";
                                  echo "</tr></thead><tbody>";
                                  while($h = pg_fetch_assoc($res))
                                  {
                                    echo "<tr>";
                                      echo "<td class='text-muted'>".$h['id']."</td>";
                                      echo "<td>".$h['licence_plate']."</td>";
                                      echo "<td>".formataData($h['timestamp'],1)."</td>";
                                      echo "<td>".formataData($h['closed_timestamp'],1)."</td>";
                                      echo "<td>".formataData($h['notified_timestamp'],1)."</td>";
                                      echo "<td>".formataData($h['winch_timestamp'],1)."</td>";
                                      echo "<td>".$h['obs']."</td>";
                                    echo "</tr>";
                                  }
                                  /*
                                  [id] => 29327
                                     [id_vehicle] =>
                                     [id_parking] => 1
                                     [timestamp] => 2019-05-02 16:25:05
                                     [notified] => f
                                     [notified_timestamp] =>
                                     [closed] => t
                                     [closed_timestamp] => 2019-05-02 19:11:40
                                     [id_user] => 63
                                     [licence_plate] => QJU7219
                                     [obs] =>
                                     [id_user_notified] =>
                                     [id_user_closed] =>
                                     [winch_timestamp] =>
                                     [id_user_winch] =>
                                  */
                                  echo "<tbody></table>";
                            }else {
                              echo "<div class='row' style='margin-top:15px'><div class='col-md-6 col-md-offset-3 text-center'><div class='alert alert-warning'>Não há registros de estacionamento para essa vaga.</div></div></div>";
                            }
                        ?>
                    </div>
                  </div>
        </div>
    </section>
  </div>
</div>
</section>
<script>
$(document).scrollTop(0);
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>"); });
</script>
