<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id         = $_GET['id'];
  $agora      = now();

  logger("Acesso","OCT - Providencias", "Ocorrência n.".$_GET['id']);

  if($prov_sel)
  {
    $acao       = "atualizar";
    $margin_upd = "-19px";
  }else{
    $margin_upd = "15px";
    $acao       = "inserir";
  }
?>
<form id="form_providencias" name="form_providencias" action="oct/FORM_providencias_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?="<h2>Ocorrência n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";?>
      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='oct/index.php'>Ocorrências de trânsito</a></li>
          <li><a href='oct/FORM.php?id=<?=$_GET['id']?>'>Ocorrência n.<?=$_GET['id'];?></a></li>
          <li><span class='text-muted'>Providências</span></li>
        </ol>
      </div>
    </header>

    <section class="panel">
      <header class="panel-heading">
        <h4><span class="text-muted"><i class="fa fa-cogs"></i> Providências</h4>
      </header>
      <div class="panel-body">

        <div class="row">
          <div class="col-sm-6">
            <!-- ========================================================= -->

          <div class="row">

               <div class="col-sm-12">
                 <div class="form-group">
                 <label class="control-label">Providência:</label>
                 <select id="id_providence" name="id_providence" class="form-control">
                    <?
                          $sql = "SELECT * FROM sepud.oct_providence ORDER BY area, providence ASC";
                          $res = pg_query($sql)or die("Erro ".__LINE__);
                          while($p = pg_fetch_assoc($res))
                          {
                            $provs[$p['area']][] = $p;
                          }

                          foreach ($provs as $area => $prov)
                          {
                            echo "<optgroup label='$area'>";
                              for($i=0;$i<count($prov);$i++)
                              {

                                if($prov[$i]["id"] == 26){ $sel = "selected"; }else{ $sel = "";}
                                echo "<option value='".$prov[$i]["id"]."' $sel>".$prov[$i]["providence"]."</option>";
                              }
                            echo "</optgroup>";
                          }
                    ?>
                 </select>

                </div>
              </div>


           </div>

           <div class="row">
             <div class="col-sm-6">

                 <div class="form-group">
                     <label class="control-label">Veículo:</label>
                      <select id="id_vehicle" name="id_vehicle" class="form-control">
                        <option value="">- - -</option>
                          <?
                              $sql = "SELECT
                                        	VE.*,
                                        	(SELECT COUNT ( * ) FROM sepud.oct_victim WHERE id_vehicle = VE.ID) AS qtd_vitimas
                                        FROM
                                        	sepud.oct_vehicles VE
                                        WHERE
                                        	VE.id_events = '".$id."'
                                        ORDER BY VE.id ASC";
                              $res = pg_query($sql) or die("Erro ".__LINE__);
                              while($d = pg_fetch_assoc($res))
                              {
                                if($d['licence_plate'] != ""){ $placa =  " (Placa: ".$d['licence_plate'].")"; }else{ $placa = "";}
                                echo "<option value='".$d['id']."'>".$d['description'].$placa."</option>";
                              }
                          ?>
                      </select>
                 </div>

               </div>


             <div class="col-sm-6">
               <div class="form-group">
                   <label class="control-label">Vítima:</label>
                    <select id="id_victim" name="id_victim" class="form-control">
                      <option value="">- - -</option>
                      <?
                          $sql = "SELECT
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

                          $res = pg_query($sql) or die("Erro ".__LINE__);
                          while($d = pg_fetch_assoc($res))
                          {
                            echo "<option value='".$d['vitima_id']."'>".$d['name']."</option>";
                          }
                      ?>
                    </select>
               </div>
             </div>

           </div>



           <div class="row">
             <div class="col-sm-6">

                 <div class="form-group">
                     <label class="control-label">Hospital:</label>
                      <select id="id_hospital" name="id_hospital" class="form-control">
                        <option value="">- - -</option>
                          <?
                              $sql = "SELECT
                                          H.*
                                        FROM
                                          sepud.hospital H
                                        ORDER BY H.name ASC";
                              $res = pg_query($sql) or die("Erro ".__LINE__);
                              while($d = pg_fetch_assoc($res))
                              {
                                echo "<option value='".$d['id']."'>".$d['name']."</option>";
                              }
                          ?>
                      </select>
                 </div>

               </div>


             <div class="col-sm-6">
               <div class="form-group">
                   <label class="control-label">Orgão:</label>
                    <select id="id_company" name="id_company" class="form-control">
                      <option value="">- - -</option>
                      <?
                          $sql = "SELECT
                                  	*
                                  FROM
                                  	sepud.company
                                  ORDER BY
                                  	name ASC";

                          $res = pg_query($sql) or die("Erro ".__LINE__);
                          while($d = pg_fetch_assoc($res))
                          {
                            echo "<option value='".$d['id']."'>".$d['acron']." - ".$d['name']."</option>";
                          }
                      ?>
                    </select>
               </div>
             </div>

           </div>


           <div class="row">


               <div class="col-sm-6">
                 <div class="form-group">
                     <label class="control-label">Data:</label>
                     <input type="text" id="data" name="data" value="<?=$agora['data'];?>" class="form-control"/>
                  </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Hora:</label>
                    <input type="text" id="hora" name="hora" value="<?=$agora["hm"];?>" class="form-control"/>
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
                        <textarea name="description" placeholder="Descrição detalhada providência tomada." rows="7" class="form-control"><?=$dados['description'];?></textarea>
                   </div>
                 </div>
            </div>

            <div class="row">
                  <div class="col-sm-12 text-center" style="margin-top:28px">
                      <input type="hidden" name="id"          value="<?=$id;?>">
                      <input type="hidden" name="victim_sel"  value="<?=$victim_sel;?>">
                      <input type="hidden" name="acao" value="<?=$acao?>">
                      <input type="hidden" name="retorno_acao" id="retorno_acao" value="">
                      <a class="btn btn-default loading" href='oct/FORM.php?id=<?=$_GET['id']?>'>Voltar</a>

                      <? if($acao == "inserir"){ ?>
                      <button id='bt_inserir_voltar'    type='submit' class="btn btn-primary loading" role="button">Inserir e voltar</button>
                      <button id='bt_inserir_continuar' type='button' class="btn btn-primary loading" role="button">Inserir e continuar</button>
                      <? }else{ ?>
                        <a href="oct/FORM_vitima.php?id=<?=$id;?>"  class="mb-xs mt-xs mr-xs btn btn-default"><i class="fa fa-user"></i> Nova vítima</a><br>
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

                                                    echo "<table class='table table-bordered table-condensed'>";
                                                      echo "<tr bgcolor='#dbe9ff'>";
                                                        echo "<td width='10px' class='text-muted'>".$p['area']."</td>";
                                                        echo "<td><b>".$p['providence']."</b></td>";
                                                        echo "<td  width='150px' align='center'>".formataData($p['opened_date'],1)."</td>";
                                                        //echo "<td  width='150px' align='center'>".formataData($p['closed_date'],1)."</td>";
                                                      echo "<td class='text-center'>Ações</td></tr>";
                                                      echo "<tr>";
                                                        echo "<td colspan='3'>";


                                                                  echo "<table class='table'>";
                                                                  echo "<tr><td width='50'><span style='color:#CCCCCC'>Observações:</span></td><td colspan='3'>";
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

                                                            echo "<td class='text-center' width='50px'><a href='oct/FORM_providencias_sql.php?id=".$id."&id_providence=".$p['id']."&acao=remover'><button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'><i class='fa fa-trash'></i></button></a></td>";

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
$(".loading").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$(".loading2").click(function(){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

$("#bt_inserir_continuar").click(function(){
    $("#retorno_acao").val("continuar");
    $("#form_providencias").submit();
});
</script>
