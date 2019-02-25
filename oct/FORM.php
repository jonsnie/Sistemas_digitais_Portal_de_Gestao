<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

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


      $sql = "SELECT * FROM sepud.oct_rel_events_event_conditions WHERE id_events = '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      while($d = pg_fetch_assoc($res))
      {
        $dadosCondicoes[] = $d['id_event_conditions'];
      }


      $acao  = "atualizar";

      $dt = formataData($dados['date'],1);
      $data = $dt;
      $aux  = explode(" ",$data);
      $data = $aux[0];
      $hora = $aux[1];
      $txt_bread = "Ocorrência n.".$id;

  }else{
      $acao                   = "inserir";
      $dados['status']        = "Nova ocorrência";
      $dados['company_acron'] = $_SESSION['company_acron'];
      $dados['company']       = $_SESSION['company'];
      $agora = now();
      $txt_bread = "Nova ocorrência";

  }
?>
<form id="form_oct" action="oct/FORM_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?
          if($acao == "inserir")
          {
              echo "<h2>Nova ocorrência</h2>";
              //print_r_pre($agora);
          }else{
              echo "<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";
          }
      ?>

      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/index.php'>Ocorrências de trânsito</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>
    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted">Status: </span><strong><i id='txt_status'><?=$dados['status'];?></i></strong></h4>
        <input type="hidden" id="status" name="status" value="<?=$dados['status'];?>" >
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
                                    $vet[$d['type']][] = $d;

                                  //if($dados['id_event_type'] == $d['id']){ $sel = "selected"; }else{ $sel = ""; }
                                  //echo "<option value='".$d['id']."' $sel>".$d['name']."</option>";
                                }
                                foreach($vet as $type => $d)
                                {
                                  echo "<optgroup label='".$type."'>";
                                    for($i = 0; $i < count($d); $i++)
                                    {
                                      if($dados['id_event_type'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                                      echo "<option value='".$d[$i]['id']."' $sel>".$d[$i]['name']."</option>";
                                    }
                                  echo "</optgroup>";
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
                                      if($dados['victim_inform'] == $i){ $sel = "selected"; }else{ $sel = ""; }
                                      echo "<option value='".$i."' $sel>".$i."</option>";
                                    }
                                  ?>
                                </select>
                        </div>
                  </div>
            </div>


            <div class="row">
                  <div class="col-sm-8">
                    <div class="form-group">
                    <label class="control-label">Endereço:</label>
                        <input type="text" id="endereco" name="endereco" class="form-control" value="<?=$dados['address_reference'];?>">
                   </div>
                  </div>


                  <div class="form-group">
											<div class="col-md-4 text-center" style="margin-top:28px">
												<button id="geocode" type="button" class="btn btn-sm btn-primary" style="width:100%"><i class="fa fa-map-marker"></i> Localizar no mapa</button>
											</div>
										</div>
           </div>

           <div class="row">
                 <div class="col-sm-12">
                   <div class="form-group">
                   <label class="control-label">Complemento do endereço/pontos de referencia:</label>
                       <input type="text" name="endereco_complemento" class="form-control" value="<?=$dados['address_complement'];?>">
                  </div>
                 </div>
            </div>

           <div class="row">
                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Data:</label>
                       <input type="text" name="data" class="form-control" value="<?=($acao=="inserir"?$agora['data']:$data);?>">
                  </div>
                 </div>
                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Hora:</label>
                       <input type="text" name="hora" class="form-control" value="<?=($acao=="inserir"?$agora['hm']:$hora);?>">
                  </div>
                 </div>

                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Coordenadas Geográficas:</label>
                       <input type="text" id="coordenadas" name="coordenadas" class="form-control text-center" value="<?=$dados['geoposition'];?>">
                  </div>
                 </div>
          </div>

<? if($acao != "inserir"){ ?>
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
                                    //   $aux = json_decode($dados['event_conditions']);

                                       $sql = "SELECT * FROM sepud.oct_event_conditions ORDER BY subtype ASC";
                                       $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);
                                       while($d = pg_fetch_assoc($res)){ $v[$d['type']][] = $d; }
                                       foreach($v as $optg => $d){
                                         echo "<optgroup label='".$optg."'>";
                                           for($i = 0; $i < count($d); $i++){
                                              if(in_array($d[$i]['id'],$dadosCondicoes)){ $sel = "selected"; }
                                              else                                      { $sel = "";         }
                                              echo "<option value='".$d[$i]['id']."' ".$sel.">".ucfirst($d[$i]['subtype'])."</option>";
                                           }
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
                                      <a href="oct/FORM_providencias.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-plus-square"></i> Providências</button></a>
                                  </div>
                              </div>
                              <h2 class="panel-title">Providências:</h2>
                            </header>
                            <div class="panel-body">

                                <?
                                  $sql = "SELECT
                                          			 U.name,
                                          			 C.acron, C.name as company,
                                          			 VE.description as vehicle, VE.color as vehicle_color, VE.licence_plate,
                                          			 VI.name as victim_name, VI.age as victim_age,
                                          			 H.name as hospital,
                                          			 CO.acron as company_acron, CO.name as company_name,
                                          			 PR.area, PR.providence,
                                          		   P.*
                                          FROM sepud.oct_rel_events_providence P
                                          JOIN sepud.users U ON U.id = P.id_owner
                                          JOIN sepud.company C ON C.id = U.id_company
                                          LEFT JOIN sepud.oct_vehicles VE ON VE.id = P.id_vehicle
                                          LEFT JOIN sepud.oct_victim   VI ON VI.id = P.id_victim
                                          LEFT JOIN sepud.hospital      H ON  H.id = P.id_hospital
                                          LEFT JOIN sepud.company      CO ON CO.id = P.id_company_requested
                                          JOIN sepud.oct_providence    PR ON PR.id = P.id_providence
                                          WHERE P.id_event = '".$id."'
                                          ORDER BY P.opened_date DESC";
                                    $res = pg_query($sql)or die("Erro ".__LINE__."<hr><pre>".$sql."</pre>");


                                  if(pg_num_rows($res))
                                  {
                                      while($p = pg_fetch_assoc($res))
                                      {
                                        /*
                                        [name] => Jonathan Canfield Sniecikoski
    [acron] => SEPUD
    [company] => Secretaria de Planejamento Urbano e Desenvolvimento Sustentável
    [vehicle] => Audi A4
    [vehicle_color] => Cinza chumbo
    [licence_plate] => AAA0A00
    [victim_name] => Dolores Fuertes de Barriga
    [victim_age] => 58
    [hospital] => Hospital Dona Helena
    [company_acron] => CB
    [company_name] => Corpo de Bombeiros Voluntários
    [area] => Outros
    [providence] => Apoio - Bombeiro
    [id] => 23
    [opened_date] => 2019-02-18 19:04:46.123768
    [closed_date] =>
    [id_owner] => 1
    [id_vehicle] => 21
    [id_victim] => 28
    [id_hospital] => 2
    [id_company_requested] => 4
    [observation] => teste de inserção de dados...
    [id_event] => 53
    [id_providence] => 17
                                        */
                                        echo "<table class='table table-bordered table-condensed'>";
                                          echo "<tr bgcolor='#dbe9ff'>";
                                            echo "<td width='10'>".$p['area']."</td>";
                                            echo "<td>".$p['providence']."</td>";
                                            echo "<td  width='150' align='center'>".formataData($p['opened_date'],1)."</td>";
                                            //echo "<td  width='150px' align='center'>".formataData($p['closed_date'],1)."</td>";
                                          echo "</tr>";
                                          echo "<tr>";
                                            echo "<td colspan='3'>";


                                            echo "<table class='table'>";
                                            echo "<tr><td width='50'><span style='color:#CCCCCC'>Observações:</span></td><td>";
                                            if($p['observation'] != ""){ echo $p['observation']; }else{ echo "<span style='color:#CCCCCC'>Nenhuma anotação de observação para essa providência.</span>";}
                                            echo "</td></tr>";

                                              if(isset($p['vehicle']) || isset($p['victim_name']) || isset($p['hospital']) || isset($p['company_name']))
                                              {
                                                //echo "<hr><span style='color:#CCCCCC'>Envolvidos: </span>";

                                                echo "<tr>";
                                                if(isset($p['vehicle'])){      echo "<td width='50'><span style='color:#CCCCCC'>Veículo:</span></td><td>".$p['vehicle'].", ".$p['vehicle_color']." - ".$p['licence_plate']."</td>"; }
                                                if(isset($p['victim_name'])){  echo "<td width='50'><span style='color:#CCCCCC'>Vítima:</span></td><td>".$p['victim_name'];
                                                                               if(isset($p['victim_age'])){ echo ", idade: ".$p['victim_age']." ano(s)"; }
                                                                               echo  "</td>"; }

                                                echo "</tr><tr>";

                                                if(isset($p['hospital'])){     echo "<td width='50'><span style='color:#CCCCCC'>Hospital:</span></td><td>".$p['hospital']."</td>"; }
                                                if(isset($p['company_name'])){ echo "<td width='50'><span style='color:#CCCCCC'>Orgão:</span></td><td>".$p['company_name']."</td>";}

                                                echo "</tr>";

                                              }
                                              echo "</table>";
                                            echo "</td>";
                                          echo "</tr>";

                                          echo "<tr bgcolor='#eeeeee'>";
                                            echo "<td colspan='4' align='right'>".$p['name']."<br><small>".$p['acron']." - ".$p["company"]."</small></td>";
                                          echo "</tr>";



                                        echo "</table>";
                                      }
                                  }else{
                                        echo "<div class='alert alert-warning text-center'>Nenhuma providência cadastrada para esta ocorrência.</div>";
                                  }



                                ?>


                          </div>
                        </section>
                  </div>
            </div>



              <div class="row" style="margin-top:15px">
                    <div class="col-md-12">
            							<section class="panel panel-featured panel-featured-warning">
            								<header class="panel-heading">
            									<div class="panel-actions" style="margin-top:-10px">
                                  <div class="btn-group">
                  										<a href="oct/FORM_veiculo.php?id=<?=$id;?>"><button type="button" class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-car"></i> Veículos</button></a>
                                      <a href="oct/FORM_vitima.php?id=<?=$id;?>"><button type="button"  class="mb-xs mt-xs mr-xs btn btn-default loading"><i class="fa fa-user"></i> Vítimas/Envolvidos</button></a>
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

                                    foreach($veics as $id_veic => $info)
                                    {
                                      echo "<table class='table  table-striped table-bordered table-condensed'>";
                                      echo "<tr>";
                                        echo "<td width='20px' class='text-muted'>".$id_veic."</td>";
                                        echo "<td>".$info['description']."</td>";
                                        echo "<td width='200px'>Cor: ".$info['color']."</td>";
                                        echo "<td width='200px'>Placa: ".$info['licence_plate']."</td>";
                                      echo "</tr>";
                                      echo "<tr>";
                                        echo "<td colspan='4'><b>Observações: </b>".$info['observation']."</td>";
                                      echo "</tr>";
                                      if(isset($info['vitimas']))
                                      {
                                        echo "<tr>";
                                          echo "<td colspan='4'>";
                                              //if(count($info['vitimas'])){ echo "<h6>Vítimas:</h6>";}
                                              for($i = 0; $i < count($info['vitimas']);$i++)
                                              {
                                                echo "<table class='table  table-striped table-bordered table-condensed'>";
                                                echo "<thead><tr bgcolor='#dbe9ff'><th>#</th>
                                                             <th width='300px'>Nome</th>
                                                             <th>Idade</th>
                                                             <th>Sexo</th>
                                                             <th>Posição</th>
                                                             <th>Encaminhado</th>
                                                             <th>Estado</th>
                                                             </tr></thead>";
                                                echo "<tbody>";

                                                echo "<tr>";
                                                  echo "<td class='text-muted'>".$info['vitimas'][$i]['id']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['name']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['age']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['genre']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['postion_in_vehicle']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['forwarded_to']."</td>";
                                                  echo "<td>".$info['vitimas'][$i]['state']."</td>";
                                                echo "</tr>";
                                                echo "<tr>";
                                                  echo "<td colspan='7'><b>Observações: </b>".$info['vitimas'][$i]['description']."</td>";
                                                echo "</tr>";
                                                echo "</tbody></table>";
                                              }

                                          echo "</td>";
                                        echo "</tr>";
                                      }
                                      echo "</table>";
                                    }

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
<? } ?>


            <!-- ========================================================= -->
          </div><!--<div class="col-sm-8"> FORM PRINCIPAL-->
          <div class="col-sm-4">
            <!-- ========================================================= -->
            <div class="row">
            <div class="col-sm-12">
              <div class="row">
                <div class="col-sm-12">
                  <div id="map" style="width:100%;height:240px"></div>
                </div>
                <div class="col-sm-12">
                  <div id="mapinfo" class="text-muted" align="right" style="width:100%;margin:5px;">Debug</div>
                </div>
              </div>
            </div>
            </div>
<? if($acao != "inserir"){ ?>
            <div class="row">
            <div class="col-sm-12">
            <table class="table table-condensed">
              <tbody>
                <tr><td>Abertura:</td>     <td class="text-center"><b><?=formataData($dados['date'],1);?></b></td></tr>
                <tr><td>Chegada:</td>      <td class="text-center"><b><?=formataData($dados['arrival'],1);?></b></td></tr>
                <tr><td>Encerramento:</td> <td class="text-center"><b><?=formataData($dados['closure'],1);?></b></td></tr>
              </tbody>
            </table>
              <hr>
            </div>
          </div>
<? } ?>
            <!-- ========================================================= -->
          </div><!--<div class="col-sm-4"> FORM LATERAL-->
        </div><!--<div class="row">-->



    </div>
    <footer class="panel-footer">

          <input type="hidden" name="userid" value="<?=$_SESSION['id']?>">
          <input type="hidden" name="acao"   value="<?=$acao;?>">
          <input type="hidden" name="id"     value="<?=$id;?>">
      <?
          if($acao == "inserir")
          {
            echo "<button id='bt_inserir_oc' type='submit' class='btn btn-primary'>Inserir ocorrência</button>";
          }else {

      ?>
          <div class="btn-group">
    					<div class="btn-group dropup">
    						<!--<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><?=$dados['status'];?> <span class="caret"></span></a>-->
                <a id="bt_status" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" ajax="false">Alterar Status <span class="caret"></span></a>
    						<ul class="dropdown-menu" role="menu">
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=d">Em deslocamento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=a">Em atendimento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=e">Encaminhamento</a></li>
    							<li class="divider"></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=f">Ocorrência Terminada</a></li>
    						</ul>
    					</div>
              <button id='bt_atualizar_oc' type='submit' class="btn btn-primary" role="button">Atualizar Ocorrência </buttona>
    			</div>
      <? } ?>
    </footer>


    </section>
</section>
</form>
<script>

$(".loading").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});

$("#geocode").click(function(){

  if($("#endereco").val() != "")
  {
    $("#mapinfo").html("Iniciando pesquisa de geocode...");
    geocode();
  }else {
    $("#mapinfo").html("Campo do endereço não pode estar vazio.");
  }

});
$("#bt_inserir_oc").click(function(){
          $("#bt_inserir_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, inserindo ocorrência</small>");
});
$("#bt_atualizar_oc").click(function(){

          $("#bt_atualizar_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");
          $("#bt_atualizar_oc").attr("disabled", "disabled");
          $("#form_oct").submit();
});

$(".bt_status_action").click(function(){
  $("#bt_status").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");
});

<?
  if($dados['geoposition'] != "")
  {
    $zoommap = 14;
    $posicao = $dados['geoposition'];
  }else{
    $zoommap = 10;
    $posicao = "-26.301033,-48.840862";
  }
?>
zoommap 		= <?=$zoommap;?>;
var latlon  = new L.latLng(<?=$posicao;?>);
var map 		= new L.map('map', {attributionControl: false, zoomControl: true}).setView(latlon, zoommap);


L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjanBsMnpzbzgwOXVkNDhxbG4xemF0N3gwIn0.Tbsjfe9j7zZsf3HIHI3QGQ', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1Ijoiam9uc25pZSIsImEiOiJjanBsMnpzbzgwOXVkNDhxbG4xemF0N3gwIn0.Tbsjfe9j7zZsf3HIHI3QGQ'
}).addTo(map);

map.on("dragend",   function (e) {$("#mapinfo").html("MAPA: dragend");	count=0;	});
map.on("dragstart", function (e) {});
map.on("drag",      function (e) {$("#mapinfo").html("MAPA: drag    ["+count+++"]");});
map.on("zoom",      function (e) {$("#mapinfo").html("MAPA: zoom"); map.flyTo(marco.getLatLng()); });

map.removeControl(map.zoomControl);
if (!L.Browser.mobile){  L.control.zoom({position:'bottomright'}).addTo(map);}

//var marker = L.marker([<?=$posicao;?>]).addTo(map);
var latlon  = new L.latLng(<?=$posicao;?>);
marco 			= false;
marco = L.marker(latlon, {draggable:'true', autoPan: 'true', autoPanSpeed: '1' })
         .addTo(map)
         .on('dragstart', function(){ map.dragging.disable(); $('#mapinfo').html('MARKER: dragstart'); 				  })
         .on('drag',      function(){ 												$('#mapinfo').html('MARKER: drag ['+count+++']');  })
         .on('dragend',   function(){ map.dragging.enable();
                                      $('#mapinfo').html('MARKER: dragend: '+marco.getLatLng());
                                      count=0;
                                      map.flyTo(marco.getLatLng());
                                      $("#coordenadas").val(marco.getLatLng().lat+","+marco.getLatLng().lng);
                                    });



function geocode(){


//  if(marco){ map.removeLayer(marco); marco = false;}
          cidade = "Joinville";
          estado = "Santa Catarina";
          pais   = "Brasil";

//https://nominatim.openstreetmap.org/search?street=Rua%20Max%20Colin,%201265&city=Joinville&state=Santa%20Catarina&country=Brasil&format=json
//https://nominatim.openstreetmap.org/search?street=Rua Dr Joao Colin, 2008, 401A&city=Joinville&state=Santa Catarina&country=Brasil&format=json

      var query = "street="+$("#endereco").val()+"&city="+cidade+"&state="+estado+"&country="+pais;
      var url = 'https://nominatim.openstreetmap.org/search?format=json&'+query
      //$("#mapinfo").html(url);

      $.getJSON(url, function(data) {
          $("#mapinfo").html('');
          var nome  = "";
          if(data.length)
          {

              $.each(data, function(key, val)
              {
                $("#mapinfo").html("<br>Geocode retornado, tipo: "+val.type);
                if(val.type=="city" || val.type=="residential" || val.type=="house" || val.type=="bus_station" || val.type=="secondary" || val.type=="primary")
                {
                    notFound = false; //Para travar na primeira ocorrencia
                    nome = val.display_name.split(',',3).join();
                    //$("#mapinfo").val("Geocode encontrado: "+nome+" Coords:["+val.lat+","+val.lon+"]");
                    if(marco){ map.removeLayer(marco); }
                    marco = L.marker([val.lat, val.lon], {draggable:'true', autoPan: 'true', autoPanSpeed: '1' })
                               .addTo(map)
                               .on('dragstart', function(){ map.dragging.disable(); $('#mapinfo').html('ADDR START MARCO'); 						 })
                               .on('drag',      function(){ 												$('#mapinfo').html('ADDR MOVENDO MARCO: '+count++);  })
                               .on('dragend',   function(){ map.dragging.enable();
                                                            $('#mapinfo').html('ADDR Fim Pos: '+marco.getLatLng());
                                                            count=0;
                                                            map.flyTo(marco.getLatLng());
                                                            $("#coordenadas").val(marco.getLatLng().lat+","+marco.getLatLng().lng);
                                                          });
                    map.flyTo(marco.getLatLng(),14);
                    $("#coordenadas").val(val.lat+","+val.lon);
                    //$("#geocoderet").removeClass("text-muted text-danger").addClass("text-success").html("<b>O marcador pode ser posicionado manualmente para um melhor ajuste. Para concluir, clique em atualizar.</b>");
                }
              });
          }else{
              $("#mapinfo").html("Geocode não encontrado.");
              //$("#geocoderet").removeClass("text-muted text-success").addClass("text-danger").html("<b>Endereço não encontrado no mapa, especifique melhor ou posicione manualmente o marcador sobre o mapa.</b>");
          }
      });

      return false;
};
</script>
