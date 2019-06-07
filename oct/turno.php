<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  logger("Acesso","OCT", "Turno");
  $agora = now();

  if($_GET['id']!=""){
    $id    = $_GET['id'];
    $sql   = "SELECT * FROM sepud.oct_workshift WHERE id = ".$id;
    $res   = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
    $dados = pg_fetch_assoc($res);
    $acao  = "atualizar";
    $bt_enviar_txt = "Atualizar";
  }else{
    $acao = "inserir";
    $bt_enviar_txt = "Inserir novo turno de trabalho";
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Abertura de turno de trabalho</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Turno de trabalho</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions text-right">
                  <? if($id == ""){ echo "<h4><i><b>Abertura de turno</b></i></h4>"; }
                     else{ echo "<h4><i><span class='text-muted'>Turno <b>nº ".str_pad($id,5,"0",STR_PAD_LEFT)."</b> aberto</span></i></h4>"; }
                 ?>
                </div>
            </header>
            <div class="panel-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                            <h4>Informações do turno</h4>
                            <hr style="margin-top:-3px;margin-bottom:5px">
                      </div>
                    </div>

                    <form name="form_turno" method="post" action="oct/turno_sql.php">
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Data de abertura:</label>
                                  <input type="text" name="opened" class="form-control text-center" value="<?=$agora['data'];?>">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Data de fechamento:</label>
                                  <input type="text" name="closed" class="form-control text-center" <?=($acao=="inserir"?"disabled":"");?> value="<?=$dados[''];?>">
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de abertura:</label>
                                  <input type="text" name="opened_hour" class="form-control text-center" value="<?=$agora['hm'];?>">
                             </div>
                           </div>

                            <div class="col-sm-6">
                              <div class="form-group">
                              <label class="control-label">Hora de fechamento:</label>
                                  <input type="text" name="closed_hour" class="form-control text-center" <?=($acao=="inserir"?"disabled":"");?> value="<?=$dados[''];?>">
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Período:</label>
                                  <select name="period" class="form-control">
                                      <option value="matutino">Matutino (6h as 12h)</option>
                                      <option value="vespertino">Vespertino (12h as 18h)</option>
                                      <option value="noturno">Noturno(18h a 0h)</option>
                                      <option value="madrugada">Madrugada (0h as 6h)</option>
                                  </select>
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Orgão:</label>
                                  <select name="id_company" class="form-control">
                                    <option value="<?=$_SESSION['id_company'];?>"><?=$_SESSION['company_name'];?></option>
                                  </select>
                             </div>
                           </div>
                      </div>
                      <div class="row">
                            <div class="col-sm-12">
                              <div class="form-group">
                              <label class="control-label">Observações:</label>
                                  <textarea name="observation" class="form-control" rows="1"></textarea>
                             </div>
                           </div>
                      </div>

                      <div class="row" style='margin-top:10px'>
                            <div class="col-sm-12 text-center">
                              <input type="hidden" name="acao" value="<?=$acao;?>" />
                              <a href="oct/index.php">
                                <button type='button' class='mb-xs mt-xs mr-xs btn btn-default loading'>Voltar</button>
                              </a>
                              <? if($acao=="atualizar"){ ?>
                                  <a href='oct/turno_sql.php?id=<?=$id;?>&acao=fechar'><button id='bt_fechar_turno'    type='button' class='mb-xs mt-xs mr-xs btn btn-warning loading'>Fechar turno</button></a>
                              <? } ?>
                              <button type='submit' class='mb-xs mt-xs mr-xs btn btn-primary loading'><?=$bt_enviar_txt;?></button>
                           </div>
                      </div>
                    </form>

                  </div><!--<div class="col-md-6">-->
                      <div class="col-md-6">

                          <div class="row">
                            <div class="col-sm-12 text-right">
                                  <h4>Vinculação ao turno</h4>
                                  <hr style="margin-top:-3px;margin-bottom:5px">
                            </div>
                          </div>

                                <? if($id != ""){ ?>
                                    <form name="person" method="post" action="oct/turno_sql.php">
                                          <div class="row">
                                                <div class="col-sm-12">
                                                  <div class="form-group">
                                                  <label class="control-label">Colaborador:</label>
                                                      <select name="id_user" class="form-control">
                                                          <?
                                                              //$sql = "SELECT * FROM sepud.users WHERE id_company = '".$_SESSION[company_id]."' ORDER BY name ASC";
                                                              //Lista os colaboradores que ainda não estão associados ao turno, evita que um mesmo colaborador seja associado a mais de uma função dentro do turno.
                                                              $sql = "SELECT
                                                                      	U.*
                                                                      FROM
                                                                      	sepud.users U
                                                                      LEFT JOIN sepud.oct_rel_workshift_persona T ON T.id_person = U.id AND T.id_shift = '".$id."'
                                                                      WHERE
                                                                      	U.id_company = '".$_SESSION[id_company]."' AND T.id_shift is null";
                                                              $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

                                                              if(pg_num_rows($res))
                                                              {
                                                                  while($u = pg_fetch_assoc($res))
                                                                  {
                                                                    echo "<option value='".$u['id']."'>".$u['name']."</option>";
                                                                  }
                                                              }else{
                                                                    $bt_associar_recurso = "disabled";
                                                                    echo "<option>Todos os colaboradores já foram associados ao turno.</option>";
                                                              }
                                                          ?>
                                                      </select>
                                                 </div>
                                               </div>
                                              </div>

                                             <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de entrada:</label>
                                                           <input type="text" name="opened" class="form-control text-center" value="<?=$agora['data'];?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Data de saída:</label>
                                                           <input type="text" name="closed" class="form-control text-center" disabled value="<?=$dados[''];?>">
                                                      </div>
                                                    </div>
                                               </div>
                                               <div class="row">
                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de entrada:</label>
                                                           <input type="text" name="opened_hour" class="form-control text-center" value="<?=$agora['hm'];?>">
                                                      </div>
                                                    </div>

                                                     <div class="col-sm-6">
                                                       <div class="form-group">
                                                       <label class="control-label">Hora de saída:</label>
                                                           <input type="text" name="closed_hour" class="form-control text-center" disabled value="<?=$dados[''];?>">
                                                      </div>
                                                    </div>
                                               </div>

                                               <div class="row">
                                                     <div class="col-sm-12">
                                                       <div class="form-group">
                                                       <label class="control-label">Funcão:</label>
                                                           <select name="type" class="form-control">
                                                               <option value="Central de atendimento">Central de atendimento</option>
                                                               <option value="Agente de campo">Agente de campo</option>
                                                               <option value="Coordenação">Coordenação</option>
                                                               <option value="Direção">Direção</option>
                                                           </select>
                                                      </div>
                                                    </div>
                                              </div>

                                              <div class="row">
                                                    <div class="col-sm-8">
                                                      <div class="form-group">
                                                      <label class="control-label">Veículo:</label>
                                                        <select name="id_fleet" class="form-control">
                                                            <option value="">- - -</option>
                                                            <?
                                                                $sql = "SELECT * FROM sepud.oct_fleet WHERE id_company = '".$_SESSION['id_company']."' ORDER BY type ASC, brand ASC, model ASC";
                                                                $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
                                                                while($v = pg_fetch_assoc($res))
                                                                {
                                                                  $veic[$v['type']][]=$v;
                                                                }
                                                                  foreach ($veic as $type => $info) {
                                                                    echo "<optgroup label='".ucfirst($type)."'>";
                                                                    for($i=0; $i<count($info);$i++)
                                                                    {
                                                                        echo "<option value='".$info[$i]['id']."'>".$info[$i]['plate']." - ".$info[$i]['brand']." ".$info[$i]['model']."</option>";
                                                                    }
                                                                  }
                                                            ?>
                                                          </select>
                                                     </div>
                                                   </div>
                                                   <div class="col-sm-4">
                                                     <div class="checkbox" style="margin-top:35px">
                             														<label><input name="is_driver" type="checkbox" value="true">É o motorista ?</label>
                             													</div>
                                                   </div>
                                              </div>
                                              <div class="row">
                                                  <div class="col-sm-12 text-center">
                                                      <input type="hidden" name="acao" value="associar" />
                                                      <input type="hidden" name="id_workshift" value="<?=$id?>" />
                                                      <button type='submit' class='mb-xs mt-xs mr-xs btn btn-primary loading' data-loading-msg="Aguarde, processando informação." <?=$bt_associar_recurso;?>>Associar ao turno corrente</button>
                                                  </div>
                                            </div>

                                    </form>
                                <?
                                    }else {
                                      echo "<br><br><br><br><br><div class='alert alert-warning text-center'>Primeiro deve-se abrir um turno de trabalho para depois associar um recurso.</div>";
                                    }
                                ?>



                      </div>
                </div>
            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

<?
  if($id != "")
  {
?>
    <div class="col-md-12">
          <section class="panel box_shadow">
              <header class="panel-heading" style="height:70px">
                  <?="<h4><i><span class='text-muted'>Recursos vinculados ao turno de trabalho <b>nº ".str_pad($id,5,"0",STR_PAD_LEFT)."</b></span></i></h4>";?>
                  <div class="panel-actions text-right"></div>
              </header>
              <div class="panel-body">
                  <div class="row">
                    <div class="col-md-12">
                    <?
                            $sql = "SELECT
                                      	U.name as nome,
                                      	F.plate, F.model, F.brand,
                                      	WP.*
                                      FROM
                                      	        sepud.oct_rel_workshift_persona WP
                                           JOIN sepud.users                      U ON U.id = WP.id_person
                                      LEFT JOIN sepud.oct_fleet                  F ON F.id = WP.id_fleet
                                      WHERE
                                      	WP.id_shift =  '".$id."'";
                            $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
                            if(pg_num_rows($res))
                            {
                              while($d = pg_fetch_assoc($res))
                              {
                                //echo "<pre>";
                                //print_r($d);
                                $recursosTurno[] = $d;
                              }

                              //echo "<pre>";
                            //  print_r($recursosTurno);
                              //echo "</pre>";
                              /*
                              [nome] => Jonathan Canfield Sniecikoski
                              [plate] => QHD7899
                              [model] => Kombi
                              [brand] => Volkswagen
                              [id_shift] => 29
                              [id_person] => 1
                              [id_fleet] => 6
                              [opened] => 2019-05-27 15:28:00
                              [closed] =>
                              [type] => Agente de campo
                              [is_driver] => t
                              */
                              echo "<table class='table table-striped'>";
                              echo "<thead><tr>
                                    <th>Nome</th><th>Área</th><th>Data</th><th colspan='3' class='text-center'>Veículo</th>
                                    </tr></thead>";
                              echo "<tbody>";
                              for($i=0;$i<count($recursosTurno);$i++)
                              {
                                echo "<tr>";
                                  echo "<td>".$recursosTurno[$i]['nome']."</td>";
                                  echo "<td>".$recursosTurno[$i]['type']."</td>";
                                  echo "<td>".formataData($recursosTurno[$i]['opened'],1)."</td>";
                                  if($recursosTurno[$i]['plate'] != "")
                                  {
                                    if($recursosTurno[$i]['is_driver']=='t'){ echo "<td>Motorista</td>"; }else{ echo "<td>Auxiliar</td>"; }
                                    echo "<td>".$recursosTurno[$i]['brand']." ".$recursosTurno[$i]['model']."</td>";
                                    echo "<td>".$recursosTurno[$i]['plate']."</td>";
                                  }else{ echo "<td colspan='3' class='text-center text-muted'>- - - - -</td>"; }
                                echo "</tr>";
                              }
                              echo "</tbody></table>";
                            }else{
                              echo "<div class='alert alert-success text-center'>Nenhum recurso associado a este turno.</div>.";
                            }

                    ?>
                    </div>
                  </div>
              </div>
        </section>
    </div>
<? } ?>





</section>

<script>

$(".loading").click(function(){

  var msg = "Aguarde";
  if($(this).attr("data-loading-msg"))
  {
    msg = $(this).attr("data-loading-msg");
  }
  $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>"+msg+"</small>");
  //$(this).attr("disabled", true);
});
</script>
