<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];

?>
<form id="form_veiculo" name="form_veiculo" action="oct/FORM_vitima_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?="<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";?>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='#' ic-get-from='oct/index.php' ic-target='#wrap'>Ocorrências de trânsito</a></li>
          <li><a href='#' ic-get-from='oct/FORM.php?id=<?=$_GET['id']?>' ic-target='#wrap'>Ocorrência n.<?=$_GET['id'];?></a></li>
          <li><span class='text-muted'>Vítimas</span></li>
        </ol>
      </div>
    </header>

    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted"><i class="fa fa-user"></i> Vitima</h4>
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
                <div class="col-sm-4">
                  <div class="form-group">
                  <label class="control-label">Idade:</label>
                      <input type="text" name="age" class="form-control" value="<?=$dados['age'];?>">
                 </div>
               </div>
               <div class="col-sm-4">
                 <div class="form-group">
                 <label class="control-label">Genero:</label>
                     <input type="text" name="genre" class="form-control" value="<?=$dados['genre'];?>">
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                <label class="control-label">Estado:</label>
                    <input type="text" name="state" class="form-control" value="<?=$dados['state'];?>">
               </div>
             </div>
           </div>

          <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                  <label class="control-label">Observações:</label>
                      <textarea name="description" placeholder="" rows="4" class="form-control"><?=$dados['description'];?></textarea>
                 </div>
               </div>
          </div>

          <div class="row">
            <div class="col-sm-12">
                  <div class="form-group">
                    <label class="control-label" for="tipo_oc">Associar ao veículo:</label>
                          <select id="id_vehicle" name="id_vehicle" class="form-control">
                              <option value="">- - -</option>
                            <?
                              $sql = "SELECT * FROM sepud.oct_vehicles WHERE id_events = '".$id."' ORDER BY description ASC";
                              $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                              while($d = pg_fetch_assoc($res))
                              {
                                echo "<option value='".$d['id']."'>".$d['description']." - ".$d['licence_plate']."</option>";
                              }
                            ?>
                          </select>
                  </div>
            </div>
          </div>

                <div class="row">
                      <div class="col-sm-12" style="margin-top:15px">
                          <input type="hidden" name="id"   value="<?=$id;?>">
                          <input type="hidden" name="acao" value="inserir">
                          <a class="btn btn-default" href='#' ic-get-from='oct/FORM.php?id=<?=$_GET['id']?>' ic-target='#wrap'>Voltar</a>
                          <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary" role="button">Inserir</button>

                      </div>
                </div>

            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-6">
            <!-- ========================================================= -->
              <?
                $sqlv = "SELECT * FROM sepud.oct_victim WHERE id_events = '".$id."'";
                $resv = pg_query($sqlv)or die("Erro ".__LINE__);
                if(pg_num_rows($resv))
                {
                    echo "<table class='table'>
                          <thead><tr>
                          <th>#</th>
                          <th>Nome</th>
                          <th>Genero</th>
                          <th>Idade</th></tr></thead><tbody>";
                    while($d = pg_fetch_assoc($resv))
                    {

                        echo "<tr>";
                          echo "<td>".$d['id']."</td>";
                          echo "<td>".$d['description']."</td>";
                          echo "<td>".$d['color']."</td>";
                          echo "<td>".$d['licence_plate']."</td>";
                        echo "</tr>";

                        echo "<tr><td colspan='4'>".$d['observation']."</td></tr>";
                    /*
                    [id] => 3
                       [description] => Renault Sandero
                       [id_events] => 43
                       [observation] => Causador do incidente.
                       [licence_plate] => ABC-12345
                       [color] => Prata
                    */
                    }
                    echo "</tbody></table>";
                }else{
                  echo "<div class='alert alert-warning text-center'>Nenhuma vítima cadastrada para esta ocorrência.</div>";
                }

              ?>
            <!-- ========================================================= -->
          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->



    </div>
    <footer class="panel-footer">
    </footer>
  </section>
</section>
</form>
<script>
</script>
