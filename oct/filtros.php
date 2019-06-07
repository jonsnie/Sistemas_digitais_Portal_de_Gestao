<?
//session_start();
//require_once("../libs/php/funcoes.php");
//require_once("../libs/php/conn.php");
?>

            <!-- ========================================================= -->

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="filtro_data">Data:</label>
                    <div class="col-md-8">
                        <input type="text" name="filtro_data" id="filtro_data" class="form-control">
                    </div>
                </div>
            </div>


<? /* if($_SESSION["id"]==1){ ?>

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="placaVeiculo">Tipo de ocorrência:</label>
                    <div class="col-md-8">
                      <select id="filtro_tipo_oc" name="filtro_tipo_oc" class="form-control">
                        <option value="">- - -</option>
                        <?
                          $sql = "SELECT * FROM sepud.oct_event_type ORDER BY name ASC";
                          $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                          while($d = pg_fetch_assoc($res)){ $vet[$d['type']][] = $d;}
                          foreach($vet as $type => $d)
                          {
                            echo "<optgroup label='".$type."'>";
                              for($i = 0; $i < count($d); $i++)
                              {
                                echo "<option value='".$d[$i]['id']."' $sel>".$d[$i]['name']."</option>";
                              }
                            echo "</optgroup>";
                          }
                        ?>
                      </select>
                    </div>
                </div>
            </div>
<? } */ ?>
              <div class="row" style="margin-bottom:10px">
                <div class="col-sm-12">
                    <hr />Ou apenas:
                </div>
              </div>

            <div class="row" style="margin-bottom:10px">
                <div class="form-group">
                    <label class="col-md-3 control-label" for="filtro_placaveiculo">Placa do veículo:</label>
                    <div class="col-md-8">
                        <input type="text" name="filtro_placaveiculo" id="filtro_placaveiculo" class="form-control">
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
