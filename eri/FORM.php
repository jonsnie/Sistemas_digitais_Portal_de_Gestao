<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];
  $agora = now();
  if($id != "")
  {

      $sql   = "SELECT
                  	U.name, U.id_company,
                  	C.name as company_name, C.acron as company_acron,
                  	SP.*
                  FROM sepud.eri_schedule_parking SP
                  	JOIN sepud.users   U ON U.id = SP.id_user
                  	JOIN sepud.company C ON C.id = U.id_company
                  WHERE
                  	SP.id = '".$id."'";
      $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
      $dados = pg_fetch_assoc($res);


      $acao  = "atualizar";

      $dt = formataData($dados['timestamp'],1); $data = $dt;  $aux  = explode(" ",$data);
      $agora['data'] = $aux[0];
      $agora['hms']  = $aux[1];

      $txt_bread = "Registro n.".$id;
      $dados['status']        = "Registro ativo";


      if($dados['notified'] == "t"){ $dados['status'] = "Notificado"; }
      if($dados['closed'] == "t")  { $dados['status'] = "Baixado";    }


      $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

      $class = "text-success";
      if($diff >= 0  && $diff <= 60)            { $class = "text-success"; $txtstatus="No prazo";}
      if($diff >= 61 && $diff <= 89)            { $class = "text-warning"; $txtstatus="Próximo do fim do prazo";}
      if($diff >= 90)            { $class = "text-danger"; $status = "expirado"; $txtstatus="Expirado";}
      if($dados['closed']=="t")  { $class = "text-primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $txtstatus="";}
      if($dados['notified']=="t"){ $class = "text-dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $txtstatus="";}

      logger("Acesso","ERG - Registro",$txt_bread.", Placa do veículo: ".$dados['licence_plate']);

  }else{
      $acao                   = "inserir";
      $dados['status']        = "Novo registro";
      $dados['company_acron'] = $_SESSION['company_acron'];
      $dados['company_name']  = $_SESSION['company_name'];
      $dados['name']          = $_SESSION['name'];
      $txt_bread = "Novo registro";
      logger("Acesso","ERG - Registro","Novo registro");

  }
?>
<form id="form_eri" action="eri/FORM_sql.php" method="post">
<section role="main" class="content-body">
    <header class="page-header">
      <?
          if($acao == "inserir")
          {
              echo "<h2>Novo registro</h2>";
          }else{
              echo "<h2>Registro n° ".str_pad($_GET['id'],3,"0",STR_PAD_LEFT)."</h2>";
          }
      ?>

      <div class="right-wrapper pull-right" style='margin-right:15px;'>
        <ol class="breadcrumbs">
          <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
          <li><span class='text-muted'>Aplicações</span></li>
          <li><a href='eri/index.php'>Estacionamento Rotativo</a></li>
          <li><span class='text-muted'><?=$txt_bread;?></span></li>
        </ol>
      </div>
    </header>
    <section class="panel">
      <header class="panel-heading text-center">
        <h3 class='<?=$class;?>'><strong><i><?=$dados['status'];?><br><small><?=$txtstatus;?></small></i></strong></h3>
        <div class="panel-actions"></div>
      </header>
      <div class="panel-body">

        <div class="row">
            <div class="col-sm-6">

                    <div class="row" style="margin-bottom:20px">

<!--
                        <div class="col-sm-8">
                            <div class="form-group">
                            <label class="control-label">Rua:</label>
                                <select id="id_street" name="id_street" class="form-control">
                                    <?
                                    /*
                                        $sql = "SELECT * FROM sepud.streets WHERE is_rotate_parking is true ORDER BY name ASC";
                                        $res = pg_query($sql)or die();
                                        while($d = pg_fetch_assoc($res))
                                        {
                                          echo "<option value='".$d['id']."'>".$d['name']."</option>";
                                        }
                                    */
                                    ?>
                                </select>
                           </div>
                        </div>
-->
                        <div class="col-sm-12">
                            <div class="form-group">
                            <label class="control-label">Vaga:</label>
                            <select id="id_parking" name="id_parking" class="form-control input-lg">
                                  <?
                                      $sql = "SELECT
                                                	P.id, P.id_place, P.description,
                                                	S.name as street_name
                                                FROM
                                                	     sepud.eri_parking P
                                                	JOIN sepud.streets S ON S.ID = P.id_street
                                                ORDER BY S.name ASC";
                                      $res = pg_query($sql)or die($sql);
                                      while($d = pg_fetch_assoc($res)){$vagas[$d['street_name']][] = $d;}

                                      foreach ($vagas as $rua => $d)
                                      {
                                        echo "<optgroup label='".$rua."'>";
                                        for($i = 0; $i < count($d); $i++)
                                        {
                                          if($dados['id_parking'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                                          echo "<option value='".$d[$i]["id"]."' ".$sel.">".$d[$i]['description']."</option>";
                                        }
                                        echo "</optgroup>";

                                      }


                                  ?>
                                </select>
                           </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                            <label class="control-label">Placa do veículo:</label>
                                <input placeholder="XXX9999 ou XXX9X99" type="text" id="licence_plate" name="licence_plate"  maxlength="7" size="7" class="form-control input-lg text-center" value="<?=$dados['licence_plate'];?>">
                           </div>
                         </div>
                    </div>

<?           if($acao != "inserir")
            {
?>
                    <div class="row">
                            <div class="col-xs-6 text-center">
                                <h3><? echo "<small><sup>Entrada:</sup></small><br><b>".$agora['hms']."</b><br><small class='text-muted'>".$agora['data']."</small>"; ?></h3>
                                <h3><small><sup>Tempo decorrido:</sup></small><br><b class='<?=$class;?>'><?=$diff;?> min</b></h3>
                            </div>


                          <div class="col-xs-6 text-center" style="margin-top:25px">
                          <?  if($dados['notified'] != "t" && $dados['closed'] != "t")
                            {

                              if($status != "expirado")
                              {
                                echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Notificar</span></button>";
                              }else {
                                echo "<a href='eri/FORM_sql.php?id=".$id."&acao=notificar'><button type='button' class='btn btn-lg btn-block  btn-dark  loading' role='button' style='margin-bottom:5px'>Notificar</button></a>";
                              }


                              echo "<a href='eri/FORM_sql.php?id=".$id."&acao=baixar'>   <button type='button' class='btn btn-lg btn-block btn-primary loading' role='button'                         >Baixar</button></a>";
                            }else {
                              echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button' style='margin-bottom:5px'><span class='text-muted'>Notificar</span></button>";
                              echo "<button type='button' class='btn btn-lg btn-block btn-default disabled' role='button'                             ><span class='text-muted'>Baixar</span></button>";
                            }
                            ?>
                          </div>

                    </div>
<?           } ?>
<!--
                    <div class="row">
                      <div class="col-sm-4">
                          <div class="form-group">
                          <label class="control-label">Notificado em:</label>
                              <input type="text" id="endereco" name="endereco" class="form-control" value="<?=$dados['address_reference'];?>">
                         </div>
                       </div>
                       <div class="col-sm-4">
                           <div class="form-group">
                           <label class="control-label">Baixado em:</label>
                               <input type="text" id="endereco" name="endereco" class="form-control" value="<?=$dados['address_reference'];?>">
                          </div>
                        </div>
                    </div>
-->

            </div>
            <div class="col-sm-6">
<!--
                    <div class="row" style="margin-top:10px">
                      <div class="col-sm-12">
                          <div id="map" style="width:100%;height:240px"></div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div id="mapinfo" class="text-muted" align="right" style="width:100%;margin:5px;">Debug</div>
                      </div>
                  </div>
-->
        </div>

    </div>
    <footer class="panel-footer text-center" style="margin-top:20px">
          <input type="hidden" id="data" name="data" value="<?=$agora['data'];?>">
          <input type="hidden" id="hora" name="hora" value="<?=$agora['hms'];?>">


          <input type="hidden" name="status"  value="<?=$dados['status'];?>" >
          <input type="hidden" name="id_user" value="<?=$_SESSION['id']?>">
          <input type="hidden" name="acao"    value="<?=$acao;?>">
          <input type="hidden" name="id"      value="<?=$id;?>">
          <a href="eri/index.php"><button type='button' class="btn btn-lg btn-default loading" role="button">Voltar</button></a>
      <?
          if($acao == "inserir")
          {
            echo "<button id='bt_inserir_oc' type='submit' class='btn btn-lg btn-success loading' style='margin-left:5px'>Registrar</button>";
          }else{

                echo "<a href='eri/FORM.php'><button type='button' class='btn btn-lg btn-success'>Novo</button></a>";



              //echo "<button type='button' class='btn btn-default loading' role='button' style='margin-left:5px'>Fotos</button>";

          }
      ?>

    </footer>
    <h5 class="text-center"><span class="text-muted"></span><strong><?=$dados['name']?></strong><br><small><?=$dados['company_acron'];?> - <?=$dados['company_name'];?></small></h5>


    </section>
</section>
</form>
<script>

$(document).ready(function() { $('#id_parking').select2();});

$('#licence_plate').keyup(function () {
    $(this).val( $(this).val().toUpperCase() );
});
//$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });

/*
$("#bt_atualizar_oc").click(function(){
          $("#bt_atualizar_oc").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>");
          $("#bt_atualizar_oc").attr("disabled", "disabled");
          $("#form_oct").submit();
});
*/





$("#geocode").click(function(){

  if($("#endereco").val() != "")
  {
    $("#mapinfo").html("Iniciando pesquisa de geocode...");
    geocode();
  }else {
    $("#mapinfo").html("Campo do endereço não pode estar vazio.");
  }

});

<?
  if($dados['geoposition'] != "")
  {
    $zoommap = 14;
    $posicao = $dados['geoposition'];
  }else{
    $zoommap = 14;
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
