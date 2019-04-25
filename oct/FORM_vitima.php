<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id         = $_GET['id'];
  $victim_sel = $_GET['victim_sel'];
  $veic_sel   = $_GET['veic_sel'];

  logger("Acesso","OCT - Vítima", "Ocorrência n.".$_GET['id']);

  if($victim_sel)
  {
    $sql   = "SELECT * FROM sepud.oct_victim WHERE id = '".$victim_sel."'";
    $res   = pg_query($sql)or die("Erro ".__LINE__);
    $dados = pg_fetch_assoc($res);
    $acao  = "atualizar";
    $margin_upd = "-19px";
  }else{
    $margin_upd = "15px";
    $acao = "inserir";
  }
?>
<form id="form_vitima" name="form_vitima" action="oct/FORM_vitima_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?="<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";?>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/ocorrencias.php'>Ocorrências de trânsito</a></li>
          <li><a href='oct/FORM.php?id=<?=$_GET['id']?>'>Ocorrência n.<?=$_GET['id'];?></a></li>
          <li><span class='text-muted'>Vítimas/Evolvidos</span></li>
        </ol>
      </div>
    </header>

    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted"><i class="fa fa-user"></i> Vítimas/Evolvidos</h4>
      </header>
      <div class="panel-body">

        <div class="row">
          <div class="col-sm-6">
            <!-- ========================================================= -->

            <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                    <label class="control-label">Nome:</label>
                        <input type="text" name="name" placeholder="Nome completo" class="form-control" value="<?=$dados['name'];?>">
                   </div>
                 </div>
            </div>

          <div class="row">
                <div class="col-sm-2">
                  <div class="form-group">
                  <label class="control-label">Idade:</label>
                      <input type="text" name="age" class="form-control" value="<?=$dados['age'];?>">
                 </div>
               </div>
               <div class="col-sm-2">
                 <div class="form-group">
                 <label class="control-label">Genero:</label>
                 <select id="genre" name="genre" class="form-control">
                     <option value="Masc" <?=($dados['genre']=="Masc"?"selected":"");?>>Masc</option>
                     <option  value="Fem" <?=($dados['genre']=="Fem"?"selected":"");?>>Fem</option>
                 </select>
                 <!--<input type="text" name="genre" class="form-control" value="<?=$dados['genre'];?>">-->
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                <label class="control-label">Estado:</label>
                <select id="state" name="state" class="form-control">
                    <option value="Nada"  <?=($dados['state']=="Nada"?"selected":"");?>>Nada consta</option>
                    <option value="Leve"  <?=($dados['state']=="Leve"?"selected":"");?>>Leve</option>
                    <option value="Médio" <?=($dados['state']=="Médio"?"selected":"");?>>Médio</option>
                    <option value="Grave" <?=($dados['state']=="Grave"?"selected":"");?>>Grave</option>
                    <option value="Óbito" <?=($dados['state']=="Óbito"?"selected":"");?>>Óbito</option>
                </select>
                <!--<input type="text" name="state" class="form-control" value="<?=$dados['state'];?>">-->
               </div>
             </div>
             <div class="col-sm-4">
               <div class="form-group">
               <label class="control-label">Recusou:</label>
               <select id="refuse_help" name="refuse_help" class="form-control">
                   <option value="t" <?=($dados['refuse_help']=="t"?"selected":"");?>>Sim</option>
                   <option value="f" <?=($dados['refuse_help']=="f"?"selected":"");?>>Não</option>
               </select>
               <!--<input type="text" name="genre" class="form-control" value="<?=$dados['genre'];?>">-->
              </div>
            </div>
           </div>

           <div class="row">
             <div class="col-sm-8">
                   <div class="form-group">
                     <label class="control-label" for="tipo_oc">Associar ao veículo:</label>
                           <select id="id_vehicle" name="id_vehicle" class="form-control">
                               <option value="">- - -</option>
                             <?
                               $sql = "SELECT * FROM sepud.oct_vehicles WHERE id_events = '".$id."' ORDER BY description ASC";
                               $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                               while($d = pg_fetch_assoc($res))
                               {
                                 if($d['licence_plate'] != ""){ $placa = " (".$d['licence_plate'].")";}else{$placa="";}
                                 if($d['id'] == $veic_sel || $d['id'] == $dados["id_vehicle"]){ $sel = "selected"; }else{ $sel = "";}
                                 echo "<option value='".$d['id']."' ".$sel.">".$d['description'].$placa."</option>";
                               }
                             ?>
                           </select>
                   </div>
             </div>

             <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label" for="tipo_oc">Posição no veículo:</label>
                           <select id="position_in_vehicle" name="position_in_vehicle" class="form-control">
                             <option value="">- - -</option>
                             <option value="Condutor"          <?=($dados['position_in_vehicle']=="Condutor"?"selected":"");?>>Condutor</option>
                             <option value="Passageiro_frente" <?=($dados['position_in_vehicle']=="Passageiro_frente"?"selected":"");?>>Passageiro frente</option>
                             <option value="Passageiro_atras"  <?=($dados['position_in_vehicle']=="Passageiro_atras"?"selected":"");?>>Passageiro atrás</option>

                           </select>
                   </div>
             </div>

           </div>







            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-6">
            <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                    <label class="control-label">Observações:</label>
                        <textarea name="description" placeholder="Descrição detalhada do estado da vítima, procedimentos realizados, encaminhamento, etc." rows="4" class="form-control"><?=$dados['description'];?></textarea>
                   </div>
                 </div>
            </div>

            <div class="row">
                  <div class="col-sm-12 text-center" style="margin-top:28px">
                      <input type="hidden" name="id"          value="<?=$id;?>">
                      <input type="hidden" name="victim_sel"  value="<?=$victim_sel;?>">
                      <input type="hidden" name="acao" value="<?=$acao?>">
                      <input type="hidden" name="retorno_acao" id="retorno_acao" value="">
                      <a href='oct/FORM.php?id=<?=$_GET['id']?>'><button type="button"  class="btn btn-default loading">Voltar</button></a>
                      <a href="oct/FORM_veiculo.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-car"></i> Veículos</button></a>

                      <? if($acao == "inserir"){ ?>
                      <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary loading" role="button">Inserir e voltar</button>
                      <button id='bt_inserir_continuar' type='button' class="btn btn-primary loading" role="button">Inserir e continuar</button>
                      <? }else{ ?>
                        <a href="oct/FORM_vitima.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-user"></i> Nova vítima</button></a><br>
                        <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary loading" role="button">Atualizar e voltar</button>
                        <button id='bt_inserir_continuar' type='button' class="btn btn-primary loading" role="button">Atualizar e continuar</button>
                      <? } ?>
                  </div>
            </div>


          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->


          <div class="row">
            <div class="col-sm-12" style="margin-top:<?=$margin_upd;?>">
              <hr>
            </div>
          </div>

        <div class="row">
          <div class="col-sm-12" style="margin-top:15px">

            <!-- ========================================================= -->
              <?
                $sqlv = "SELECT
                            VI.id as vitima_id,
                            VI.description as vitima_desc,
                            VE.id as veiculo_id,
                            VE.description as veiculo_desc,
                            *
                          FROM
                            sepud.oct_victim VI
                          LEFT JOIN sepud.oct_vehicles VE ON VE.ID = VI.id_vehicle
                          WHERE
                            VI.id_events = '".$id."'";
                $resv = pg_query($sqlv)or die("Erro ".__LINE__);
                if(pg_num_rows($resv))
                {

                    while($d = pg_fetch_assoc($resv))
                    {
                      echo "<table class='table  table-striped table-bordered table-condensed'>
                            <thead><tr bgcolor='#dbe9ff'>
                            <th>#</th>
                            <th width='300px'>Nome</th>
                            <th>Genero</th>
                            <th>Idade</th>
                            <th>Estado</th>
                            <th>Posição</th>
                            <th>Veículo</th>
                            <th colspan='2' class='text-center'>Ações</th>
                            </tr></thead><tbody>";

                        echo "<tr>";
                          echo "<td>".$d['vitima_id']."</td>";
                          echo "<td>".$d['name']."</td>";
                          echo "<td>".$d['genre']."</td>";
                          echo "<td>".$d['age']."</td>";
                          echo "<td>".$d['state']."</td>";
                          echo "<td>".$d['position_in_vehicle']."</td>";
                          echo "<td>".$d['veiculo_desc']."</td>";

                          echo "<td class='text-center' width='50px'><a href='oct/FORM_vitima_sql.php?id=".$id."&victim_sel=".$d['vitima_id']."&acao=remover'><button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-danger  loading2'><i class='fa fa-trash'></i></button></a></td>";
                          echo "<td class='text-center' width='50px'><a href='oct/FORM_vitima.php?id=".$id."&victim_sel=".$d['vitima_id']."'                 ><button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-primary loading2'><i class='fa fa-pencil'></i></button></a></td>";

                        echo "</tr>";

                        echo "<tr><td colspan='9'><b>Observações: </b>".$d['vitima_desc']."</td></tr>";

                      echo "</tbody></table>";
                    }

                }else{
                  echo "<div class='alert alert-warning text-center'>Nenhuma vítima cadastrada para esta ocorrência.</div>";
                }

              ?>
            <!-- ========================================================= -->

          </div>
        </div>



    </div>
    <footer class="panel-footer">
    </footer>
  </section>
</section>
</form>
<script>
//$(".loading").click(function() { $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
$("#bt_inserir_continuar").click(function(){
    $("#retorno_acao").val("continuar");
    $("#form_vitima").submit();
});
</script>
