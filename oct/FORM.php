<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];
  $sistema_nao_config = false;
  if($id != "")
  {
      $sql   = "SELECT
                    W.opened as workshift_opened,
                    W.closed as workshift_closed,
                    W.period as workshift_period,
                    EV.*
                FROM
                          sepud.oct_events    EV
                     JOIN sepud.users         U  ON U.ID = EV.id_user
                LEFT JOIN sepud.oct_workshift W  ON W.id = EV.id_workshift
                WHERE EV.ID =  '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      $dados = pg_fetch_assoc($res);

      if($dados['id_workshift']!="")
      {
        $turno['id']     = $dados['id_workshift'];
        $turno['opened'] = $dados['workshift_opened'];
        $turno['closed'] = $dados['workshift_closed'];
        $turno['period'] = $dados['workshift_period'];
      }

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

      logger("Acesso detalhado","OCT", "Ocorrência n.".$id);

  }else{
      $acao                   = "inserir";
      $dados['status']        = "Nova ocorrência";
      $dados['company_acron'] = $_SESSION['company_acron'];
      $dados['company']       = $_SESSION['company'];
      $agora = now();
      $txt_bread = "Nova ocorrência";
      $sql        = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND closed is null";
      $resTurno   = pg_query($sql)or die("Erro ".__LINE__);
      if(pg_num_rows($resTurno))
      {
          $turno = pg_fetch_assoc($resTurno);
      }
  }



?>

<form id="form_oct" action="oct/FORM_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?
          if($acao == "inserir")
          {
              echo "<h2>Nova ocorrência</h2>";
          }else{
              echo "<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";
          }
      ?>

      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/ocorrencias.php?filtro_data=<?=$_GET['filtro_data'];?>'>Ocorrências de trânsito</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>
    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted">Status: </span><strong><i id='txt_status'><?=$dados['status'];?></i></strong>
                  <?
                      if(isset($turno))
                      {
                        echo "<br><small class='text-muted'>Turno nº <b>".str_pad($turno['id'],5,"0",STR_PAD_LEFT)."</b> - ".$turno['period']. " - ";
                        if($turno['closed']!=""){ echo " <span class='text-warning'>Turno fechado</span>";}else{ echo " <span class='text-success'>Turno aberto</span>";}
                        echo "<br>Início: <b>".formataData($turno['opened'],1)."</b>";
                        if($turno['closed']!=""){echo ", fim: <b>".formataData($turno['closed'],1)."</b>";}
                        echo "</small>";
                        echo "<input type='hidden' name='id_workshift' value='".$turno['id']."' />";
                      }else{
                        echo "<br><small class='text-danger'>Nenhum turno de trabalho aberto.</small>";
                      }
                  ?>
       </h4>
        <input type="hidden" id="status" name="status" value="<?=$dados['status'];?>" >
        <div class="panel-actions"><h4 class="text-right"><span class="text-muted"></span><strong><i><?=$dados['company_acron'];?></i><br><small><?=$dados['company'];?></small></strong></h4></div>
      </header>
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-8">
            <!-- ========================================================= -->
            <div class="row">
              <div class="col-sm-10">
                    <div class="form-group">
          						<label class="control-label" for="tipo_oc">Ocorrência:</label>
              							<select id="tipo_oc" name="tipo_oc" class="form-control select2 changefield">
              								<?
                              if(isset($_SESSION["id_company"]))
                              {
                                  $sql = "SELECT T.* FROM sepud.oct_event_type T
                                          JOIN sepud.oct_rel_event_type_company R ON R.id_event_type = T.id AND R.id_company = '".$_SESSION["id_company"]."'
                                          WHERE T.active = true
                                          ORDER BY T.name ASC";

                                  $res = pg_query($conn_neogrid,$sql)or die("Error: ".__LINE__);

                                  if(pg_num_rows($res))
                                  {
                                          while($d = pg_fetch_assoc($res))
                                          {
                                              $vet[$d['type']][] = $d;
                                          }
                                          foreach($vet as $type => $d)
                                          {
                                            echo "<optgroup label='".$type."'>";
                                              for($i = 0; $i < count($d); $i++)
                                              {
                                                if($d[$i]['name_acron'] != ""){ $acron = $d[$i]['name_acron']." - ";}else{$acron = "";}
                                                if($dados['id_event_type'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                                                echo "<option value='".$d[$i]['id']."' $sel>".$acron.$d[$i]['name']."</option>";
                                              }
                                            echo "</optgroup>";
                                          }
                                  }else{
                                      echo "<option value=''>Nenhuma ocorrência associada a este orgão</option>";
                                      $sistema_nao_config = true;
                                  }
                              }else {
                                echo "<option value=''>Usuário não associado ao orgão em que trabalho</option>";
                                $sistema_nao_config = true;
                              }
                              ?>
              							</select>
          					</div>
              </div>

                  <div class="col-sm-2">
                        <div class="form-group">
                          <label class="control-label" for="victim_inform">Vítima(s) info.:</label>
                                <select id="victim_inform" name="victim_inform" class="form-control changefield">
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


              <div class="col-sm-6">
                <div class="form-group">
                <label class="control-label">Logradouro:</label>
                    <select id="id_street" name="id_street" class="form-control select2 changefield" style="width: 100%; height:100%">
                      <option value="">- - -</option>
                      <?
                        $sql = "SELECT * FROM sepud.streets ORDER BY name ASC";
                        $res = pg_query($sql)or die();
                        while($s = pg_fetch_assoc($res))
                        {
                          if($dados["id_street"] == $s["id"]){ $sel = "selected";}else{$sel="";}
                          echo "<option value='".$s['id']."' ".$sel.">".$s['name']."</option>";
                        }
                      ?>
                    </select>
               </div>
              </div>

                  <div class="col-sm-2">
                    <div class="form-group">
                    <label class="control-label">Numero:</label>
                        <input type="number" id="street_number" name="street_number" class="form-control changefield" value="<?=$dados['street_number'];?>">
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
                       <input type="text" name="endereco_complemento" class="form-control changefield" value="<?=$dados['address_complement'];?>">
                  </div>
                 </div>
            </div>

           <div class="row">
                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Data:</label>
                       <input type="text" name="data" class="form-control changefield" value="<?=($acao=="inserir"?$agora['data']:$data);?>">
                  </div>
                 </div>
                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Hora:</label>
                       <input type="text" name="hora" class="form-control changefield" value="<?=($acao=="inserir"?$agora['hm']:$hora);?>">
                  </div>
                 </div>

                 <div class="col-sm-4">
                   <div class="form-group">
                   <label class="control-label">Coordenadas Geográficas:</label>
                       <input type="text" id="coordenadas" name="coordenadas" class="form-control text-center changefield" value="<?=$dados['geoposition'];?>">
                  </div>
                 </div>
          </div>

<? if($acao != "inserir"){ ?>
           <div class="row">
                         <div class="col-sm-8">
                           <div class="form-group">
                             <label class="control-label">Descrição detalhada:</label>
                             <textarea name="description" id="description" class="form-control changefield" rows="10" placeholder="Descreva a ocorrência."><?=$dados['description'];?></textarea>
                           </div>
                         </div>

                         <div class="col-sm-4">
                           <div class="form-group">
                                 <label class="control-label" for="condicoes[]">Condições:</label>
                                   <select size='10' multiple data-plugin-selectTwo id="condicoes[]" name="condicoes[]" class="form-control populate changefield">
                                     <?
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


  <div class="row">
    <div class="col-sm-12" style="margin-top:10px">

      <div class="btn-group">
          <!--<button id="bt_voltar" type="button" class="btn btn-default loading">Voltar</button>-->
          <a href="oct/ocorrencias.php?filtro_data=<?=$_GET['filtro_data'];?>" class="btn btn-default loading">Voltar</a>
          <div class="btn-group dropup">
            <!--<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><?=$dados['status'];?> <span class="caret"></span></a>-->
            <button type="button" id="bt_status" class="btn btn-warning dropdown-toggle disable_button" data-toggle="dropdown" ajax="false">Alterar Status <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu">
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=d">Em deslocamento</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=a">Em atendimento</a></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=e">Encaminhamento</a></li>
              <li class="divider"></li>
              <li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=f">Ocorrência Terminada</a></li>
            </ul>
          </div>
          <button id='bt_atualizar_oc'  type='submit' class="btn btn-primary bt_atualizar_oc" role="button">Atualizar Ocorrência</button>
          <button type='button' class="btn btn-danger bt_recarregar_oc" style="display:none" role="button">Cancelar atualizações</button>

      </div>


    </div>
  </div>



              <div class="row" style="margin-top:15px">
                    <div class="col-md-12">
                          <section class="panel panel-featured panel-featured-warning">
                            <header class="panel-heading">
                              <div class="panel-actions" style="margin-top:-10px">
                                  <div class="btn-group">
                                      <button id="bt_add_prov" type="button" class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><i class="fa fa-file-powerpoint-o"></i> <sup><i class="fa fa-plus"></i></sup> Providências</button>
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
                  										<button id="bt_add_veic" type="button" class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><i class="fa fa-car"></i> <sup><i class="fa fa-plus"></i></sup> Veículos</button>
                                      <button  id="bt_add_vit"  type="button"  class="mb-xs mt-xs mr-xs btn btn-default loading disable_button"><i class="fa fa-user"></i> <sup><i class="fa fa-plus"></i></sup> Vítimas/Envolvidos</button>
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



<div class="row">
    <div class="col-sm-12">


<? if($acao == "atualizar"){

  $sql = "SELECT * FROM sepud.oct_rel_events_images WHERE id_events = '".$id."' ORDER BY id DESC";
  $res = pg_query($sql)or die("Erro ".__LINE__);

?>

      <section class="panel panel-featured panel-featured-primary">
        <header class="panel-heading">
          <h4><span class="text-primary">Fotos:</h4>
          <div class="panel-actions">
            <button id="bt_upload_imgs" type="button" class="mb-xs mt-xs mr-xs btn  btn-default disable_button"><i class="fa fa-camera"></i> <sup><i class="fa fa-plus"></i></sup></button>
            <input type="file" id="input_img_files" name="files[]" multiple="multiple" style="display:none" />

            <? if(pg_num_rows($res)){   ?>
                <button id="bt_ver_imgs" type="button" class="mb-xs mt-xs mr-xs btn  btn-default disable_button" data-toggle='modal' data-target='#modalFotos'><i class="fa fa-image"></i> <sup><i class="fa fa-eye"></i></sup></button>
            <? } ?>
          </div>
        </header>
        <div class="panel-body">
          <div class="row">
              <div class="col-sm-12 text-center">
                <?

                    if(pg_num_rows($res))
                    {
                      while($f = pg_fetch_assoc($res))
                      {

                      //  $aux = explode(".",$f['image']);
                      //  $aux = explode("_",$aux[0]);
                      //  $id_img = ltrim(end($aux),"0");

                        echo  "<img src='oct/uploads/".$id."/".$f['image']."' style='padding:2px; width:100px' data-toggle='modal' data-target='#modalFotos' />";
                        $arqs_imgs[] = "oct/uploads/".$id."/".$f['image'];
                      }
                      unset($aux,$id_img);

                    }else {
                      echo "<div class='text-center' style='margin-bottom:20px'><i class='fa fa-camera fa-5x text-muted'></i></div>";
                    }
                ?>


              </div>
          </div>
       </div>
       <footer class="panel-footer text-right">
         <span id="msg" class="text-muted"><?=pg_num_rows($res);?> foto(s)</span>
       </footer>
     </section>
<? } ?>

    </div>
</div>


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
            echo "<a href='oct/ocorrencias.php?filtro_data=".$_GET['filtro_data']."' class='btn btn-default loading'>Voltar</a> ";
            if(!$sistema_nao_config)
            {
              echo "<button id='bt_inserir_oc' type='submit' class='btn btn-primary'>Inserir ocorrência</button>";
            }else {
              echo "<div class='row'><div class='col-md-6 col-md-offset-3'><div class='alert alert-danger text-center'>Sistema ou perfil de usuário não esta completamente configurado.</div></div></div>";
            }
          }else {

      ?>
          <div class="btn-group">
              <!--<button id="bt_voltar" type="button" class="btn btn-default loading">Voltar</button>-->
              <a href="oct/ocorrencias.php?filtro_data=<?=$_GET['filtro_data'];?>" class="btn btn-default loading">Voltar</a>
              <div class="btn-group dropup">
    						<!--<a class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><?=$dados['status'];?> <span class="caret"></span></a>-->
                <button type="button" id="bt_status" class="btn btn-warning dropdown-toggle disable_button" data-toggle="dropdown" ajax="false">Alterar Status <span class="caret"></span></button>
    						<ul class="dropdown-menu" role="menu">
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=d">Em deslocamento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=a">Em atendimento</a></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=e">Encaminhamento</a></li>
    							<li class="divider"></li>
    							<li><a class="bt_status_action" href="oct/FORM_sql.php?id=<?=$id;?>&status_acao=atualizar&status_alterar=f">Ocorrência Terminada</a></li>
    						</ul>
    					</div>
              <button id='bt_atualizar_oc' type='submit' class="btn btn-primary bt_atualizar_oc" role="button">Atualizar Ocorrência</button>
              <button type='button' class="btn btn-danger bt_recarregar_oc" style="display:none" role="button">Cancelar atualizações</button>

    			</div>
      <? } ?>
    </footer>


    </section>
</section>
</form>



<div class="modal fade"  id="modalFotos" tabindex="-1" role="dialog" aria-labelledby="modalDeFotos" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-primary" id="modalDeFotos">Visualização das fotos da ocorrência:
          <br><small>Ocorrência nº. <?=$id;?>
        </h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form_filtro" action="oct/ocorrencias.php" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4" style="">
            <?
                for($i = 0; $i < count($arqs_imgs); $i++)
                {
                  $aux = explode(".",$arqs_imgs[$i]);
                  $aux = explode("_",$aux[0]);
                  $id_img = ltrim(end($aux),"0");
                  echo  "<img id='".$id_img."' class='loadimage img-responsive img-rounded img-thumbnail' src='".$arqs_imgs[$i]."' style='max-width:100px; max-height:90px' />";
                }

            ?>

          </div>
          <div class="col-sm-8">
              <div class="row">
                <div class="col-sm-12" style="min-height:300px;text-align:center;display:table-cell;vertical-align:middle;" id="area_foto">
                  <i class='fa fa-camera fa-5x text-muted'></i>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-4" style="">
                    <button type="button" id="bt_remover_foto" class="btn btn-danger disabled"><i class="fa fa-trash"></i></button>
                </div>
                <div class="col-sm-8 text-right" id="debug_fotos">. . .</div>
             </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>



function bloqueio()
{
  //alert("Descrição da ocorrência alterada, não esqueça de atualizar os dados antes de qualquer outra interação com o sistema");
  $(".disable_button").addClass("disabled");
  $(".bt_recarregar_oc").show();
  //$(".bt_atualizar_oc").html("Atualizar Ocorrência <sup>(Houve alteração)</sup>");
}

$(".changefield").change(function(){ bloqueio(); });

$("#bt_add_prov").click(function(){ $("#wrap").load("oct/FORM_providencias.php?id=<?=$id;?>"); })
$("#bt_add_veic").click(function(){ $("#wrap").load("oct/FORM_veiculo.php?id=<?=$id;?>"); })
$("#bt_add_vit").click(function(){ $("#wrap").load("oct/FORM_vitima.php?id=<?=$id;?>"); })

$(".bt_recarregar_oc").click(function(){ $('#wrap').load("oct/FORM.php?id=<?=$id;?>"); });

var id_img_remover;
var removeu_image = false;

$("#bt_remover_foto").click(function(e){

  var srcDel = $("#foto_ativa").attr("src");
  $("#debug_fotos").html("Removendo foto: "+srcDel);
  $("#area_foto").html("<i class='fa fa-camera fa-5x text-muted'></i>");
  $("#"+id_img_remover).hide();
  $("#debug_fotos").load("oct/image_remove.php?id_oc=<?=$id;?>&id="+id_img_remover+"&arq="+srcDel);
  removeu_image = true;

});

$('#modalFotos').on('hidden.bs.modal',function(e){

    if(removeu_image){ $('#wrap').load("oct/FORM.php?id=<?=$id;?>");}
    removeu_image = false;

});


$(".loadimage").click(function(){
    var src        = $(this).attr("src");
    id_img_remover = $(this).attr("id");
    $("#debug_fotos").html("Carregando foto: "+src);
    $("#area_foto").html("<img id='foto_ativa' src='"+src+"' class='img-responsive img-rounded img-thumbnail' style='max-height:430px'/>");
    $("#debug_fotos").html(src);
    $("#bt_remover_foto").removeClass("disabled");
});

/*
$(document).ready(function (e) {

  $('#bt_upload').on('click',function(){
    $("#msg").html("Clicou para inserir imagens<br>");
    $("#arq_imgs").click();

  });
   $("#arq_imgs").change(function(){ alert( "Upload de imagens..." );});


 $('#bt_uploadsss').on('click', function () {

                   var form_data = new FormData();
                   var ins = document.getElementById('arq_imgs').files.length;
                   for (var x = 0; x < ins; x++) {
                       form_data.append("files[]", document.getElementById('arq_imgs').files[x]);
                   }
                   $.ajax({
                       url: 'oct/image_upload.php?id_oc=<?=$id?>', // point to server-side PHP script
                       dataType: 'text', // what to expect back from the PHP script
                       cache: false,
                       contentType: false,
                       processData: false,
                       data: form_data,
                       type: 'post',
                       success: function (response) {
                           alert(response);
                           $('#msg').html(response); // display success response from the PHP script
                       },
                       error: function (response) {
                           alert(response);
                           $('#msg').html(response); // display error response from the PHP script
                       }
                   });
               });
           });
*/

$("#bt_upload_imgs").click(function(){ $("#msg").html("Buscando imagens."); $("#input_img_files").trigger("click"); });
$("#input_img_files").change(function(e){
    if(e.target.files.length == 1)
    {
      $("#msg").html("<br>Imagen selecionada:");
    }else {
      $("#msg").html("<br>Imagens selecionadas:");
    }

    for(i = 0; i < e.target.files.length; i++)
    {
      $("#msg").append("<br><b>"+e.target.files[i].name+"</b>");
    }

    $("#msg").append("<br><b class='text-danger'>Aguarde, enviando arquivos...</b>");

    var form_data = new FormData();
    var ins = document.getElementById('input_img_files').files.length;
    for (var x=0;x<ins;x++){form_data.append("files[]", document.getElementById('input_img_files').files[x]);  }
    $.ajax({
        url: 'oct/image_upload.php?id_oc=<?=$id?>',
        dataType: 'text', // what to expect back from the PHP script
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (response) {
            $('#msg').html(response);
            $('#wrap').load("oct/FORM.php?id=<?=$id;?>");
        },
        error: function (response) {
            $('#msg').html(response);
        }
    });
})

//$('#id_street').select2();
//$('#tipo_oc').select2();
$('.select2').select2();

$("#geocode").click(function(){geocode();});
$("#bt_inserir_oc").click(function(){ $("#bt_inserir_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, inserindo ocorrência</small>");});
$(".bt_status_action").click(function(){ $("#bt_status").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");});
$("#bt_atualizar_oc").click(function(){
          $("#bt_atualizar_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde, atualizando ocorrência</small>");
          $("#bt_atualizar_oc").attr("disabled", "disabled");
          $("#form_oct").submit();
});



<?
  if($dados['geoposition'] != "")
  {
    $zoommap = 16;
    $posicao = $dados['geoposition'];
  }else{
    $zoommap = 13;
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
                                      bloqueio();
                                    });



function geocode(){

          $("#mapinfo").html('Pesquisa iniciada.');
//  if(marco){ map.removeLayer(marco); marco = false;}
          cidade = "Joinville";
          estado = "Santa Catarina";
          pais   = "Brasil";

          endereco = $("#id_street option:selected").text()+" "+$("#street_number").val();

//https://nominatim.openstreetmap.org/search?street=Rua%20Max%20Colin,%201265&city=Joinville&state=Santa%20Catarina&country=Brasil&format=json
//https://nominatim.openstreetmap.org/search?street=Rua Dr Joao Colin, 2008, 401A&city=Joinville&state=Santa Catarina&country=Brasil&format=json

      var query = "street="+endereco+"&city="+cidade+"&state="+estado+"&country="+pais;
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
                if(val.type == "tertiary" ||  val.type=="city" || val.type=="residential" || val.type=="house" || val.type=="bus_station" || val.type=="secondary" || val.type=="primary")
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
                    map.flyTo(marco.getLatLng(),12);
                    $("#coordenadas").val(val.lat+","+val.lon);
                    bloqueio();
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
