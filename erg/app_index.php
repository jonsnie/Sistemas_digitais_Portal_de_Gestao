<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");


  $agora = now();



  //////////////////////////////////////////////////////////
  //Bloqueios da contagem de tempo para os registros em aberto após o horario final de operação//
  //Dia de semana após as 18:30
  if(($agora['dia_semana']>=1 && $agora['dia_semana']<=5) && (($agora['hora']>=18 && $agora['min']>=30) || $agora['hora']>=19))
  {
    $agora['datatimesrv'] = $agora['datasrv']." 18:30:00";
    $agora['hora'] = 18;
    $agora['min']  = 30;
  }
  //Sabado após as 13h ou domingo o dia todo
  if($agora['dia_semana']==6 && $agora['hora']>13)
  {
    $agora['datatimesrv'] = $agora['datasrv']." 13:00:00";
    $agora['hora'] = 13;
    $agora['min']  = 00;
  }
  //////////////////////////////////////////////////////////

  logger("Acesso","SERP - App inicio");


  if($_GET['filtro_rua'] != "")
  {
      $sel_filtro_rua         = $_GET['filtro_rua'];
      $_SESSION['filtro_rua'] = $_GET['filtro_rua'];
  }

  if($_SESSION['filtro_rua'] != "")
  {
    $sel_filtro_rua         = $_SESSION['filtro_rua'];
  }


      $sqlR = "SELECT id, name FROM sepud.streets WHERE is_rotate_parking is true ORDER BY name ASC";
      $resR = pg_query($sqlR)or die("<option>Erro ".__LINE__.", ".$sqlR."</option>");
      if(pg_num_rows($resR))
      {
           while($r = pg_fetch_assoc($resR))
           {
              if($sel_filtro_rua == $r['id']){ $sel = "selected"; }else{ $sel = ""; }
              $option_ruas .= "<option value='".$r['id']."' ".$sel.">".$r['name']."</option>";
              $ruas[]       = $r['id'];
           }
      }else{
              $option_ruas = "<option>Nenhuma rua cadastrada para estacionamento rotativo</option>";
      }


      if($_GET['filtro_rua'] == "" && $_SESSION['filtro_rua'] == "")
      {
            $filtro_rua = " AND S.id = '".$ruas[0]."'";
      }else {
            if($_GET['filtro_rua'] != ""){ $filtro_rua = " AND S.id = '".$_GET["filtro_rua"]."'";    }
                                     else{ $filtro_rua = " AND S.id = '".$_SESSION["filtro_rua"]."'";}


      }

      if($_GET['filtro']!="todos"){
        $sqlfiltro = "AND SP.closed_timestamp is null AND SP.notified_timestamp is null";
      }else{
        $filtro_rua = ""; //Se clicou no botão "TODOS" limpa o filtro da rua e mostra todos os registros de todas as ruas
      }




       $sql = "SELECT
              	SP.id, SP.id_vehicle, SP.id_parking, SP.timestamp,SP.notified, SP.closed, SP.licence_plate,
                SP.closed_timestamp, SP.notified_timestamp,
              	--V.brand, V.model, V.color, V.licence_plate,
              	P.name as parking_code, P.description as parking_description,
              	S.name as street_name, S.id as street_id,
                T.time, T.time_warning
              FROM
              	sepud.eri_schedule_parking SP
                --JOIN sepud.eri_vehicles V ON V.id = SP.id_vehicle
                JOIN sepud.eri_parking      P ON P.id = SP.id_parking
                JOIN sepud.streets          S ON S.id = P.id_street $filtro_rua
                JOIN sepud.eri_parking_type T ON T.id = P.id_parking_type
              WHERE SP.timestamp BETWEEN '".$agora['datasrv']." 00:00:00' AND '".$agora['datasrv']." 23:59:59'
              --ORDER BY SP.closed ASC, SP.notified ASC,SP.timestamp ASC
              ".$sqlfiltro."
              ORDER BY S.name, P.name ASC";
    $rs  = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
?>
<style>

</style>


<section role="main" class="content-body">
  <header class="page-header">
    <h2>SERP - Gestão de vagas</h2>
    <div class="pull-right" style='margin-top:9px; margin-right:20px'>
        <a href='auth/logout.php' ajax="false"><button type="button" class="btn btn-default">Sair</button></a>
    </div>
  </header>
								<section class="panel box_shadow">

									<div class="panel-body">
                    <div class="row" style="margin-top:-10px;margin-bottom:10px">


                      <div class="col-xs-2">
                        <div class="form-group">
                          <input type="number" pattern="\d*" class="form-control input-lg" id="pesquisa" placeholder="Vaga">
                        </div>
                      </div>

                        <div class="col-xs-4">
                          <div class="form-group">
                            <input type="text" class="form-control input-lg" id="pesquisa_placa" placeholder="Placa">
                          </div>
                        </div>

                        <div class="col-xs-6 text-right">

                          <button id="bt_refresh" style='' type='button' class='btn btn-lg btn-primary'><i id="bt_refresh_icon" class='fa fa-refresh'></i></button>

                          <? if($_GET['filtro']=="todos"){
                                  echo "<a href='erg/app_index.php'>
                                        <button  style='' type='button' class='btn btn-lg btn-info loading'><i class='fa fa-search'></i> Ver ativos</button>
                                        </a>";
                             }else{
                                  echo "<a href='erg/app_index.php?filtro=todos'>
                                        <button  style='' type='button' class='btn btn-lg btn-info loading'><i class='fa fa-search'></i> Ver todos</button>
                                        </a>";
                             }
                          ?>

                          <a href="erg/app_FORM.php">
                              <button  style="" type="button" class="btn btn-lg btn-success">Novo</button>
                          </a>
                      </div>


                    </div>
                    <div class="row" style="margin-bottom:10px">

                      <div class="col-xs-12">

                        <div class="form-group">
                          <select class="form-control select2" id="pesquisa_rua"  style="width: 100%; height:100%">
                              <?
                                  if($_GET['filtro']=="todos")
                                  {
                                      echo "<option>Filtro \"TODOS\" selecionado</option>";
                                  }else {
                                      echo $option_ruas;
                                  }
                              ?>
                          </select>
                        </div>

                      </div>
                    </div>


                            <?  if(pg_num_rows($rs))
                            {
                                                      echo
                                                           "<div class='table-responsive'>
                                      											<table class='table table-hover mb-none'>
                                      												<thead>
                                                                  <tr>
                                          														<th>Placa</th>
                                                                      <th>Vaga</th>
                                                                      <th>Entrada</th>
                                                                      <th>Tempo</th>
                                                                      <th>Logradouro</th>
                                                                  </tr>
                                      												</thead>
                                      												<tbody id='table_body'>";
                                        while($dados = pg_fetch_assoc($rs))
                                        {
                                          $data  = formataData($dados['timestamp'],1);
                                          $dtAux = explode(" ",$data);
                                          $hora  = $dtAux[1];

                                          $diff = floor((strtotime($agora['datatimesrv']) - strtotime($dados['timestamp']))/60);

                                          $class = "success";

                                          if($dados['closed']!="t" && $dados['notified']!="t")
                                          {
                                            if($diff >= 0                      && $diff < $dados['time_warning']){ $class = "success"; $stats['no_prazo']++; $total++;    }
                                            if($diff >= $dados['time_warning'] && $diff < $dados['time']        ){ $class = "warning"; $stats['prox_do_fim']++; $total++; }
                                            if($diff >= $dados['time']                                          ){ $class = "danger";  $stats['expirado']++; $total++;    }
                                          }else{
                                            if($dados['closed']  =="t"){ $class = "primary"; $diff = floor((strtotime($dados['closed_timestamp'])   - strtotime($dados['timestamp']))/60); $stats['baixado']++;$total++;}
                                            if($dados['notified']=="t"){ $class = "dark";    $diff = floor((strtotime($dados['notified_timestamp']) - strtotime($dados['timestamp']))/60); $stats['notificado']++;$total++;}
                                          }


                                          echo "<tr id='".$dados['id']."' class='".$class."' onClick=\"go('erg/app_FORM.php?id=".$dados['id']."');\">";
                                          //echo "<td><b>".$dados['id']."</b></td>";
                                          echo "<td>".$dados['licence_plate']."</td>";
                                          //echo "<td>".$dados['brand']." ".$dados['model']." ".$dados['color']."</td>";
                                          //echo "<td>".$dados['parking_description']."</td>";
                                          echo "<td>".$dados['parking_code']." <sup>".$dados['time']."min</sup></td>";
                                          //echo "<td>".$dados['street_name']."</td>";
                                          echo "<td>".$hora."</td>";
                                          echo "<td><b>".$diff." min</b></td>";
                                          //echo "<td>Alerta: ".$dados['time_warning']." min</td>";
                                          echo "<td>".$dados['street_name']."</td>";

                                          /*
                                          echo "<td class='actions text-center'>
                                                  <a href='erg/app_FORM.php?id=".$dados['id']."' class='mb-xs mt-xs mr-xs btn btn-default' style='margin-top:15px'><i class='fa fa-pencil'></i></a>
                                                </td>";
                                          */
                                          echo "</tr>";
                                        }

                                                echo "</tbody>
                                      											</table></div>";
                            }else{
                              echo "
                                      <div class='table-responsive'>
                                       <table class='table table-hover mb-none'>
                                         <thead>
                                             <tr>
                                                 <th>Placa</th>
                                                 <th>Vaga</th>
                                                 <th>Entrada</th>
                                                 <th>Tempo</th>
                                             </tr>
                                         </thead>
                                         <tbody id='table_body'>
                                            <tr><td colspan='5' class='text-center'>
                                                  <div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhuma registro de estacionamento rotativo ativo para a data de hoje.</div>
                                            </td></tr>
                                        </tbody>
                                      </table>
                                    </div>";

                            }
                            ?>
									</div>

									<footer class="panel-footer text-center">
                      <?="<h4><b>".$agora['data']." - ".pg_num_rows($rs)." Registros</b></h4>";?>
                      <h5 class="text-center"><span class="text-muted"></span><strong><?=$_SESSION['name']?></strong><br><small><?=$_SESSION['company_acron'];?> - <?=$_SESSION['company_name'];?></small></h5>
                  </footer>




								</section>

</section>

<script>

$(".select2").select2(
    {
        language: {
             noResults: function(term) {
                 return "Logradouro não encontrado.";
            }
        }
    }
);

$("#pesquisa_rua").change(function(){
        var street_id = $(this).children("option:selected").val();
        $("#wrap").load("erg/app_index.php?filtro_rua="+street_id);
  });

$(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});

var pesquisou = false;

$('#pesquisa_placa').keyup(function(){
    $(this).val($(this).val().toUpperCase());
    var query = $(this).val();
    if(query.length >= 1)
    {
      $("#table_body").load("erg/app_pesquisa_vaga.php?origem=pesquisa_placa_index&query="+query);
      pesquisou = true;
    }else {
      if(pesquisou){$("#wrap").load("erg/app_index.php");}
    }
});


$("#pesquisa").keyup(function(){
  var query = $(this).val();
  if(query.length >= 1)
  {
    $("#table_body").load("erg/app_pesquisa_vaga.php?origem=pesquisa_vaga_index&query="+query);
    pesquisou = true;
  }else {
    if(pesquisou){$("#wrap").load("erg/app_index.php");}
  }
});
      $("#pesquisa").click(function(){ if(pesquisou){$("#wrap").load("erg/app_index.php");}});
$("#pesquisa_placa").click(function(){ if(pesquisou){$("#wrap").load("erg/app_index.php");}});
    $("#bt_refresh").click(function(){ $("#bt_refresh_icon").addClass('fa-spin'); $("#wrap").load("erg/app_index.php");});

function go(url)
{
  //alert("Clicou, URL: "+url);
  $('#wrap').load(url);
  return false;
}
// $(".loading").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde</small>");});
// $(".loading2").click(function(event){ $(this).addClass("disabled").html("<i class=\"fa fa-spinner fa-spin\"></i>");});

(function( $ ) {

	'use strict';

  /*
  $('.modal-basic').click(function() {
    var id_remover = $(this).attr("remover_id");



    $('.modal-basic').magnificPopup({
      type: 'inline',
  		fixedContentPos: false,
  		fixedBgPos: true,
  		overflowY: 'auto',
  		closeBtnInside: true,
  		preloader: false,
  		midClick: true,
  		removalDelay: 300,
  		mainClass: 'my-mfp-slide-bottom',
  		modal: true,
      callbacks: {
        beforeClose: function()
        {
            $.ajax({
              method: "POST",
              url: "usuarios/sqls.php",
              data: { id: id_remover, acao: "remover" }
            }).done(function( msg ) {
                $("#"+id_remover).fadeOut("slow");
              });

        }
      }
  	});
  });
*/
$(".modal-basic").click(function(){
    var ID = $(this).attr('remover_id');
    $('.modal-confirm').attr('remover_id',ID);
});

	$('.modal-basic').magnificPopup({
    type: 'inline',
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 300,
		mainClass: 'my-mfp-slide-bottom',
		modal: true
	});

  	$(document).on('click', '.modal-dismiss', function (e) {
  		e.preventDefault();
  		$.magnificPopup.close();
      $('.modal-confirm').removeAttr('remover_id');
  	});


  	$(document).on('click', '.modal-confirm', function (e) {
      var remover_id = $(this).attr('remover_id');
      var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15};
      e.preventDefault();
  		$.magnificPopup.close();

      $.ajax({
        method: "POST",
        url: "usuarios/sqls.php",
        data: { id: remover_id, acao: "remover" }
      }).done(function( msg ) {
          //alert("REMOVIDO !!!! ID: "+remover_id);
          $("#"+remover_id).fadeOut("slow");

          var notice = new PNotify({
                title: 'Sucesso',
                text:  'Registro #'+remover_id+' removido.',
                type:  'success',
                addclass: 'stack-bottomright',
                stack: stack_bottomright,
                hide: true,
                delay: 1000,
                closer: true
              });

        });
  	});

  }).apply( this, [ jQuery ]);
</script>
