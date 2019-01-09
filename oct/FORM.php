<?
  session_start();
  require_once("../libs/php/conn.php");
  require_once("../libs/php/funcoes.php");

  $id = $_GET['id'];
  if($id != "")
  {
      $sql   = "SELECT EV.*,
                       U.company,
                       U.company_acron
                FROM  sepud.oct_events EV
                JOIN  sepud.users AS U ON U.id = EV.id_user
                WHERE EV.id = '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      $dados = pg_fetch_assoc($res);
  }
?>

<section role="main" class="content-body">
    <header class="page-header">
      <h2>Ocorrência n° <?=str_pad($_GET['id'],3,"0",STR_PAD_LEFT);?></h2>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='#'  ic-get-from='oct/index.php' ic-target='#wrap'>Ocorrências de trânsito</a></li>
          <li><span class='text-muted'>Ocorrência</span></li>
        </ol>
      </div>
    </header>
    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted">Status: </span><strong><i><?=$dados['status'];?></i></strong></h4>
        <div class="panel-actions"><h4 class="text-right"><span class="text-muted"></span><strong><i><?=$dados['company_acron'];?></i><br><small><?=$dados['company'];?></small></strong></h4></div>
      </header>
      <div class="panel-body">

        <div class="row">
          <div class="col-sm-8">
            <!-- ========================================================= -->
            <div class="row">
              <div class="col-sm-8">
                    <div class="form-group">
          						<label class="control-label" for="tipo_oc">Ocorrência:</label>
              							<select id="tipo_oc" name="tipo_oc" class="form-control">
              								<?
                                $sql = "SELECT * FROM sepud.oct_event_type ORDER BY name ASC";
                                $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                                while($d = pg_fetch_assoc($res))
                                {
                                  echo "<option value='".$d['id']."'>".$d['name']."</option>";
                                }
                              ?>
              							</select>
          					</div>
              </div>

                  <div class="col-sm-4">
                        <div class="form-group">
                          <label class="control-label" for="victim_inform">Vítimas informadas:</label>
                                <select id="victim_inform" name="victim_inform" class="form-control">
                                  <?
                                    for($i = 0; $i <= 100; $i++)
                                    {
                                      echo "<option value='".$i."'>".$i."</option>";
                                    }
                                  ?>
                                </select>
                        </div>
                  </div>
            </div>


            <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                    <label class="control-label">Endereço:</label>
                      <div class="input-group mb-md">
                        <input type="text" name="endereco" class="form-control" value="<?=$dados['address_reference'];?>">
                        <span class="input-group-btn">
                          <button class="btn btn-primary" type="button"><i class="fa fa-map-marker"></i></button>
                        </span>
                      </div>
                   </div>
                  </div>
           </div>


           <div class="row">
                         <div class="col-sm-8">
                           <div class="form-group">
                             <label class="control-label">Descrição detalhada:</label>
                             <textarea name="description" class="form-control" rows="10" placeholder="Descreva a ocorrência."><?=$dados['description'];?></textarea>
                           </div>
                         </div>

                         <div class="col-sm-4">
                           <div class="form-group">
                                 <label class="control-label" for="condicoes[]">Condições:</label>
                                   <select size='10' multiple data-plugin-selectTwo id="condicoes[]" name="condicoes[]" class="form-control populate">
                                     <?
                                       $sql = "SELECT * FROM sepud.oct_event_conditions ORDER BY subtype ASC";
                                       $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                                       while($d = pg_fetch_assoc($res)){ $v[$d['type']][] = $d; }
                                       foreach($v as $optg => $d){
                                         echo "<optgroup label='".$optg."'>";
                                           for($i = 0; $i < count($d); $i++){ echo "<option value='".$d[$i]['id']."'>".ucfirst($d[$i]['subtype'])."</option>";}
                                         echo "</optgroup>";
                                       }
                                     ?>
                                   </select>
                           </div>
                         </div>
              </div>


              <div class="row" style="margin-top:15px">
                    <div class="col-md-12">
            							<section class="panel panel-featured panel-featured-warning">
            								<header class="panel-heading">
            									<div class="panel-actions" style="margin-top:-10px">
                                  <div class="btn-group">
                  										<a href="#"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-plus"></i> <i class="fa fa-car"></i></button></a>
                                      <a href="#"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-plus"></i> <i class="fa fa-user"></i></button></a>
                                  </div>
            									</div>
            									<h2 class="panel-title">Envolvidos:</h2>
            								</header>
            								<div class="panel-body">
            								<?


                                $sqlVei = "SELECT * FROM sepud.oct_vehicles WHERE id_events = '".$id."'";
                                $resVei = pg_query($conn_neogrid,$sqlVei)or die("Error ".__LINE__);
                                while($d = pg_fetch_assoc($resVei)){ $veics[$d['id']] = $d; }


                                $sqlVit = "SELECT * FROM sepud.oct_victim WHERE id_events = '".$id."'";
                                $resVit = pg_query($conn_neogrid,$sqlVit)or die("Error ".__LINE__);
                                while($d = pg_fetch_assoc($resVit)){
                                  if($d['id_vehicle'] != ""){ $veics[$d['id_vehicle']]['vitimas'][] = $d; }
                                                        else{ $vits[$d['id']] = $d;                       }

                                }

                                if(isset($veics))
                                {
                                  echo "<table class='table table-condensed'>";
                                    foreach($veics as $id_veic => $info)
                                    {
                                      echo "<tr>";
                                        echo "<td>".$id_veic."</td>";
                                        echo "<td>".$info['description']."</td>";
                                      echo "</tr>";
                                      if(isset($info['vitimas']))
                                      {

                                        //echo "<tr><td>".$info['vitimas'][0]['name']."</td></tr>";
                                        echo "<tr>";
                                          echo "<td>&nbsp;</td>";
                                          echo "<td>";
                                            echo "<table class='table table-condensed'>";
                                            echo "<thead><th>#</th>
                                                         <th>Nome</th>
                                                         <th>Idade</th>
                                                         <th>Sexo</th>
                                                         <th>Posição</th>
                                                         <th>Encaminhado</th>
                                                         <th>Estado</th>
                                                         <th>Descrição</th></thead>";
                                            echo "<tbody>";
                                              for($i = 0; $i < count($info['vitimas']);$i++)
                                              {
                                                echo "<tr>";
                                                  echo "<td>".$info['vitimas'][$i]['id']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['name']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['age']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['genre']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['postion_in_vehicle']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['forwarded_to']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['state']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['description']."</td>";
                                                echo "</tr>";
                                              }
                                            echo "</table>";
                                          echo "</td>";
                                        echo "</tr>";
                                      }
                                    }
                                  echo "</table>";
                                }

                                if(isset($vits))
                                {
                                  echo "<table class='table table-condensed'>";
                                  echo "<thead><th>#</th>
                                               <th>Nome</th>
                                               <th>Idade</th>
                                               <th>Sexo</th>
                                               <th>Encaminhado</th>
                                               <th>Estado</th>
                                               <th>Descrição</th></thead>";
                                  echo "<tbody>";
                                    foreach($vits as $id_vit => $info)
                                    {
                                      echo "<tr>";
                                        echo "<td>".$info['id']."</td>";
                                        echo "<td>".$info['name']."</td>";
                                        echo "<td>".$info['age']."</td>";
                                        echo "<td>".$info['genre']."</td>";
                                        echo "<td>".$info['forwarded_to']."</td>";
                                        echo "<td>".$info['state']."</td>";
                                        echo "<td>".$info['description']."</td>";
                                      echo "</tr>";
                                    }
                                  echo "</tbody>";
                                  echo "</table>";
                                }
                                //print_r_pre($veics);
                              //  echo "<hr>";
                              //  print_r_pre($vits);
                              //  echo "<hr>";
                            ?>
            								</div>
            							</section>
    						      </div>
              </div>



            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-4">
            <!-- ========================================================= -->
            <table class="table table-condensed">
              <tbody>
                <tr><td>Abertura:</td>     <td class="text-center"><b><?=formataData($dados['date'],1);?></b></td></tr>
                <tr><td>Chegada:</td>      <td class="text-center"><b><?=formataData($dados['arrival'],1);?></b></td></tr>
                <tr><td>Encerramento:</td> <td class="text-center"><b><?=formataData($dados['closure'],1);?></b></td></tr>
              </tbody>
            </table>
              <hr>
            <!-- ========================================================= -->
          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->



    </div>
    <footer class="panel-footer">
      <button class="btn btn-primary">Gravar ocorrência</button>
    </footer>


    </section>
</section>
