<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];
  $veic_sel = $_GET['veic_sel'];

  logger("Acesso","OCT - Veículo", "Ocorrência n.".$_GET['id']);

  if($veic_sel)
  {
    $sql   = "SELECT * FROM sepud.oct_vehicles WHERE id = '".$veic_sel."'";
    $resV  = pg_query($sql)or die("Erro ".__LINE__);
    $dados = pg_fetch_assoc($resV);
    $acao  = "atualizar";
    $margin_upd = "-19px";
  }else{
    $acao = "inserir";
    $margin_upd = "15px";
  }

?>
<form id="form_veiculo" name="form_veiculo" action="oct/FORM_veiculo_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?="<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";?>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/ocorrencias.php'>Ocorrências de trânsito</a></li>
          <li><a href='oct/FORM.php?id=<?=$_GET['id']?>'>Ocorrência n.<?=$_GET['id'];?></a></li>
          <li><span class='text-muted'>Veículos Envolvidos</span></li>
        </ol>
      </div>
    </header>

    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted"><i class="fa fa-car"></i> Veículos Envolvidos</h4>
      </header>
      <div class="panel-body">

        <div class="row">
          <div class="col-sm-6">
            <!-- ========================================================= -->

            <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                    <label class="control-label">Veículo:</label>
                        <input type="text" name="description" placeholder="Marca, modelo" class="form-control" value="<?=$dados['description'];?>">
                   </div>
                 </div>
            </div>

          <div class="row">
                <div class="col-sm-4">
                  <div class="form-group">
                  <label class="control-label">Placa:</label>
                      <input type="text" name="licence_plate" class="form-control" value="<?=$dados['licence_plate'];?>">
                 </div>
               </div>

               <div class="col-sm-2">
                 <div class="form-group">
                 <label class="control-label">Cor:</label>
                     <input type="text" name="color" class="form-control" value="<?=$dados['color'];?>">
                </div>
              </div>

  <div class="col-sm-6">
    <div class="form-group">
    <label class="control-label">Tipo:</label>
        <select id="tipo_veiculo" name="tipo_veiculo" class="form-control">
          <option value="">- - -</option>
          <?
              $sql = "SELECT * FROM sepud.oct_vehicle_type ORDER BY name ASC";
              $res = pg_query($sql) or die();
              while($t = pg_fetch_assoc($res))
              {
                if($t["desc"]!=""){ $desc = " (".$t['desc'].")"; }else{ $desc = ""; }
                if($dados["id_vehicle_type"] == $t["id"]){ $sel = "selected"; }else{ $sel = ""; }
                echo "<option value='".$t['id']."' $sel>".$t['name'].$desc."</option>";
              }
          ?>
        </select>
   </div>
 </div>

          </div>
          <div class="row">

                <div class="col-sm-4">
                  <div class="form-group">
                  <label class="control-label">Renavam:</label>
                      <input type="text" name="renavam" class="form-control" value="<?=$dados['renavam'];?>">
                 </div>
               </div>

                <div class="col-sm-8">
                  <div class="form-group">
                  <label class="control-label">Chassi:</label>
                      <input type="text" name="chassi" class="form-control" value="<?=$dados['chassi'];?>">
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
                        <textarea name="observation" placeholder="Observações sobre o veículo, condições geraias, posicionamento na via, etc." rows="4" class="form-control"><?=$dados['observation'];?></textarea>
                   </div>
                 </div>
            </div>


            <div class="row">
                  <div class="col-sm-12 text-center" style="margin-top:28px">
                      <input type="hidden" name="veic_sel"   value="<?=$veic_sel;?>">
                      <input type="hidden" name="id"   value="<?=$id;?>">
                      <input type="hidden" name="acao" value="<?=$acao;?>">
                      <input type="hidden" name="retorno_acao" id="retorno_acao" value="">
                      <a href='oct/FORM.php?id=<?=$_GET['id']?>'><button type="button" class="btn btn-default loading">Voltar</button></a>
                      <a href="oct/FORM_vitima.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-user"></i> Vítimas/Envolvidos</button></a>
                      <? if($acao == "inserir"){ ?>
                      <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary loading" role="button">Inserir e voltar</button>
                      <button id='bt_inserir_continuar' type='button' class="btn btn-primary loading" role="button">Inserir e continuar</button>
                    <? }else{ ?>
                      <a href="oct/FORM_veiculo.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-car"></i> Novo veículo</button></a><br>
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
            <div class="col-sm-12"  style="margin-top:15px">
              <!-- ========================================================= -->
                <?
                  $sqlv = "SELECT
                            	VE.*,
                              T.name as vehicle_type,
                            	(SELECT COUNT ( * ) FROM sepud.oct_victim WHERE id_vehicle = VE.ID) AS qtd_vitimas
                            FROM
                            	sepud.oct_vehicles VE
                              LEFT JOIN sepud.oct_vehicle_type T ON T.id = VE.id_vehicle_type
                            WHERE
                            	id_events = '".$id."'
                            ORDER BY id ASC";
                  $resv = pg_query($sqlv)or die("Erro ".__LINE__);
                  if(pg_num_rows($resv))
                  {

                      while($d = pg_fetch_assoc($resv))
                      {
                        echo "<table class='table table-striped table-bordered table-condensed'>
                              <thead><tr bgcolor='#dbe9ff'>
                              <th width='10px'>#</th>
                              <th>Veículo</th>
                              <th width='10px'>Cor</th>
                              <th width='80px'>Placa</th>
                              <th width='10px'>Renavam</th>
                              <th width='10px'>Chassi</th>
                              <th width='100px'>Tipo</th>
                              <th class='text-center' width='10px'>Vítimas</th>
                              <th colspan='3' class='text-center'>Ações</th>



                              </tr></thead><tbody>";

                          echo "<tr>";
                            echo "<td>".$d['id']."</td>";
                            echo "<td>".$d['description']."</td>";
                            echo "<td>".$d['color']."</td>";
                            echo "<td>".$d['licence_plate']."</td>";
                            echo "<td>".$d['renavam']."</td>";
                            echo "<td>".$d['chassi']."</td>";
                            echo "<td>".$d['vehicle_type']."</td>";
                            echo "<td class='text-center'>".$d['qtd_vitimas']."</td>";


                              echo "<td class='text-center' width='50px'><a href='oct/FORM_veiculo_sql.php?id=".$id."&veic_sel=".$d['id']."&acao=remover'><button type='button' class='loading2 mb-xs mt-xs mr-xs btn btn-xs btn-danger'><i class='fa fa-trash'></i></button></a></td>";
                              echo "<td class='text-center' width='50px'><a href='oct/FORM_veiculo.php?id=".$id."&veic_sel=".$d['id']."'                 ><button type='button' class='loading2 mb-xs mt-xs mr-xs btn btn-xs btn-primary'><i class='fa fa-pencil'></i></button></a></td>";
                              echo "<td class='text-center' width='50px'><a href='oct/FORM_vitima.php?id=".$id."&veic_sel=".$d['id']."'                  ><button type='button' class='loading2 mb-xs mt-xs mr-xs btn btn-xs btn-warning'><i class='fa fa-plus'></i> <i class='fa fa-user'></i></button></a></td>";

                          echo "</tr>";

                          echo "<tr><td colspan='10'><b>Observações: </b>".$d['observation']."</td></tr>";
                      /*
                      [id] => 3
                         [description] => Renault Sandero
                         [id_events] => 43
                         [observation] => Causador do incidente.
                         [licence_plate] => ABC-12345
                         [color] => Prata
                      */
                        echo "</tbody></table>";
                      }

                  }else{
                    echo "<div class='alert alert-warning text-center'>Nenhum veículo cadastrado para esta ocorrência.</div>";
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
  $("#bt_inserir_continuar").click(function(){
      $("#retorno_acao").val("continuar");
      $("#form_veiculo").submit();
  });

//$(".loading").click(function() { $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
//$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});
</script>
