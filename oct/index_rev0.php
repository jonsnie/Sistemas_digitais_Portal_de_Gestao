<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $agora        = now();
  $data_atual   = $agora['data'];
  $data_db      = $agora['datasrv'];
  $filtro_ativo = " OR  EV.active = 't'";
  $hoje         = time();


  if(isset($_POST['filtro_data']) && $_POST['filtro_data'] != "")
  {
    $_GET['filtro_data'] = date2mkt($_POST['filtro_data']);
  }

  if(isset($_POST['filtro_placaveiculo']) && $_POST['filtro_placaveiculo'] != "")
  {
      $sqlPa="SELECT distinct(id_events)
              FROM sepud.oct_vehicles
              WHERE
                  id_events is not null
              AND licence_plate = '".$_POST['filtro_placaveiculo']."'";
      $res = pg_query($sqlPa)or die("Erro ".__LINE__);
      while($dP = pg_fetch_assoc($res)){ $vetPlacas[] = $dP["id_events"];}
      $filtro_placa = implode(",",$vetPlacas);
      $bt_filtro_placa = true;
  }

  if(isset($_GET['filtro_data']) && $_GET['filtro_data'] != "")
  {
    $anterior   = $_GET['filtro_data'] - (24*3600);
    $proximo    = $_GET['filtro_data'] + (24*3600);
    $data_atual = date("d/m/Y", $_GET['filtro_data']);
    $data_db    = date("Y-m-d", $_GET['filtro_data']);
    $filtro_ativo = "";
  }else {
    $anterior = $hoje - (24*3600);
    $proximo  = $hoje + (24*3600);
  }

  if($proximo >= $hoje){ $proximo = $hoje; $bt_prox = false; $filtro_ativo = " OR  EV.active = 't'"; }else{ $bt_prox = true; }



  $sql = "SELECT
               EVT.name AS event_type,
               EV.id,
               EV.date,
               EV.arrival,
               EV.status,
               EV.victim_inform,
               EV.victim_found,
               EV.active,
               EV.address_reference,
               EV.id_street,
               C.name as company,
               C.acron as company_acron,
               S.name as street_name,
               (SELECT COUNT(*) FROM sepud.oct_victim                WHERE id_events = EV.ID) as vitimas_encontradas,
               (SELECT COUNT(*) FROM sepud.oct_rel_events_providence WHERE id_event  = EV.id) as qtd_providencias,
               (SELECT COUNT(*) FROM sepud.oct_rel_events_images     WHERE id_events = EV.id) as qtd_fotos
         FROM
               sepud.oct_events AS EV
         JOIN  sepud.oct_event_type AS EVT ON EV.id_event_type = EVT.id
         JOIN  sepud.users AS U ON U.id = EV.id_user
         JOIN  sepud.company AS C ON C.id = U.id_company
         LEFT JOIN sepud.streets AS S ON S.id = EV.id_street
         WHERE";

         if($filtro_placa != "")
         {
           $sql .= "    EV.id in (".$filtro_placa.")";
         }else {
           $sql .= "    (EV.date BETWEEN '".$data_db." 00:00:00' AND '".$data_db." 23:59:59') ".$filtro_ativo;
         }




    $sql .= "   ORDER BY EV.id DESC";
 $rs  = pg_query($conn_neogrid,$sql);
 $total_oc = 0;
 while($d = pg_fetch_assoc($rs))
 {
     if($d["qtd_providencias"] > 0){ $eventos_com_providencia[] = $d["id"]; }

     if($d['active']=='t'){ $dados[]          = $d; }
     else {                 $dados_baixados[] = $d; }

     $total_oc++;
 }

 if(isset($eventos_com_providencia) && count($eventos_com_providencia))
 {
   $aux  = implode(",", $eventos_com_providencia);
   $sqlP = "SELECT   P.id_event, P.id_vehicle, T.id, T.area, T.providence
            FROM     sepud.oct_rel_events_providence P, sepud.oct_providence T
            WHERE
                     P.id_event in (".$aux.")
                 AND P.id_providence = T.id
            ORDER BY P.id_event DESC";
   $resP = pg_query($sqlP)or die("Erro: ".__LINE__);
   while($p = pg_fetch_assoc($resP))
   {
     if($p["id"]==11 || $p["id"]==12) //ID 11/12 - Guinchamento
     {
        $providencias[$p["id_event"]]["guin"] = true;
     }
     if($p["id"]==25) //ID 25 - Prov. adminstrativa
     {
        $providencias[$p["id_event"]]["adm"] = true;
     }
   }

 }

 logger("Acesso","OCT", "Filtro data: ".$data_atual.", ".$total_oc." ocorrências listadas");
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Ocorrências de trânsito</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Ocorrências de trânsito</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?

?>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading" style="height:50px">
                    <span class="text-muted"> Data de referência: </span><b><?=$data_atual;?></b>
                    <div class="panel-actions" style='margin-top:-12px;'>


  <?

        echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalFiltro'>
              <i class='fa fa-search'></i>
              </button>";

  ?>
<? if(!$bt_filtro_placa){ ?>
  <a href="oct/index.php?filtro_data=<?=$anterior;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play fa-rotate-180"></i></button>
  </a>
<? }else {
  echo " <button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play fa-rotate-180'></i></button>";
}


?>
  <? if($bt_prox || $bt_filtro_placa){ ?>
  <a href="oct/index.php?filtro_data=">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success">Ir para hoje</button>
  </a>
<? if(!$bt_filtro_placa){ ?>
  <a href="oct/index.php?filtro_data=<?=$proximo;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play"></i></button>
  </a>
<? }else {
  echo " <button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
}


?>
<? }else{
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'>Ir para hoje</button>";
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
  } ?>


                      <a href="oct/FORM.php">
                        <button type="button" class="mb-xs mt-xs mr-xs btn  btn-danger"><i class="fa fa-exclamation-triangle"></i> Abrir nova ocorrência</button>
                      </a>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
<?
  if($_SESSION["id"]=="1")
  {
    /*
    echo "<pre>Post:<br>";
    print_r($_POST);
    echo "<hr>Get:<br>";
    print_r($_GET);
    echo "</pre>";
    */
  }
?>

                    <div class="table-responsive">
											<table class="table table-hover mb-none">
												<thead>
<!--
                          <tr>
                            <th colspan="2"></th>
                            <th colspan="2" class='text-center'>Datas</th>
                            <th colspan="2" class='text-center'>Vítimas</th>
                            <th colspan="3"></th>
                          <tr>
-->
    												<th>#</th>
														<th>Tipo</th>
                            <th >Logradouro</th>
														<th class='text-center'>Abertura</th>
                            <!--<th class='text-center'>Chegada</th>
                            <th class='text-center'>Informado</th>
                            <th class='text-center'>Encontrado</th>-->
                            <th class='text-center'>Origem</th>
                            <th class='text-center' colspan="3">Providências</th>
                            <th class='text-center'>Fotos</th>
                            <th class='text-center'>Status</th>

                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>

<?

  if(!isset($dados) && !isset($dados_baixados))
  {
    echo "<tr><td colspan='10' class='text-center'><br><br>
              <div class='alert alert-warning'>
              Nenhuma ocorrênca aberta ou cadastrada para esta data.<br><b>".$data_atual."</b>
              </div>
          <br><br></td></tr>";
  }

  if(isset($dados))
  {
          for($i = 0; $i < count($dados); $i++)
          {

            $dt_abertura = formataData($dados[$i]['date'],1);
            $dt_chegada  = formataData($dados[$i]['arrival'],1);



            echo "<tr id='".$dados[$i]['id']."'>";
            echo "<td><b>".$dados[$i]['id']."</b></td>";


            echo "<td>".$dados[$i]['event_type']."</td>";


            echo "<td style='white-space:nowrap;'>".$dados[$i]['street_name']."</td>";

            echo "<td style='white-space:nowrap;'>".$dt_abertura."</td>";
          //  echo "<td class='text-center'>".$dt_chegada."</td>";
          //  echo "<td class='text-center'>".($dados[$i]['victim_inform']!=""?$dados[$i]['victim_inform']:"- - -")."</td>";
            //echo "<td class='text-center'>".($dados[$i]['victim_found']!=""?$dados[$i]['victim_found']:"- - -")."</td>";
        //echo "<td class='text-center'>".($dados[$i]['vitimas_encontradas']!=""?$dados[$i]['vitimas_encontradas']:"-")."</td>";
            echo "<td class='text-center'>".$dados[$i]['company_acron']."</td>";

            echo "<td class='text-center'>".($dados[$i]['qtd_providencias'] > 0 ? $dados[$i]['qtd_providencias']:"-")."</td>";





            if($providencias[$dados[$i]["id"]]["adm"]==1)
            {
                echo "<td class='text-center success'>ADM</td>";
            }else {
                echo "<td class='text-center text-muted'>-</td>";
            }

            if($providencias[$dados[$i]["id"]]["guin"]==1)
            {
                echo "<td class='text-center info'>GUI</td>";
            }else {
                echo "<td class='text-center text-muted'>-</td>";
            }


            echo "<td class='text-center'>".($dados[$i]['qtd_fotos'] > 0 ? $dados[$i]['qtd_fotos']:"-")."</td>";

            echo "<td class='text-center' style='white-space:nowrap;'>".$dados[$i]['status']."</td>";



            echo "<td class='actions text-center'>
                    <a href='oct/FORM.php?id=".$dados[$i]['id']."' class='mb-xs mt-xs mr-xs btn btn-xs btn-default loading2'><i class='fa fa-pencil'></i></a>
                  </td>";
            echo "</tr>";
          }
  }

  if(isset($dados_baixados) && count($dados_baixados))
  {
    echo "<tr><td colspan='11' class='warning'><h3 style='margin-top:-1px;margin-bottom:1px'>Ocorrências terminadas</td></tr>";
    for($i = 0; $i < count($dados_baixados); $i++)
    {

      $dt_abertura = formataData($dados_baixados[$i]['date'],1);
      $dt_chegada  = formataData($dados_baixados[$i]['arrival'],1);



      echo "<tr id='".$dados_baixados[$i]['id']."'>";
      echo "<td><b>".$dados_baixados[$i]['id']."</b></td>";


      echo "<td>".$dados_baixados[$i]['event_type']."</td>";

      echo "<td style='white-space:nowrap;'>".$dados_baixados[$i]['street_name']."</td>";
      echo "<td style='white-space:nowrap;'>".$dt_abertura."</td>";
      //echo "<td class='text-center'>".$dt_chegada."</td>";
      //echo "<td class='text-center'>".($dados_baixados[$i]['victim_inform']!=""?$dados_baixados[$i]['victim_inform']:"- - -")."</td>";
      //echo "<td class='text-center'>".($dados[$i]['victim_found']!=""?$dados[$i]['victim_found']:"- - -")."</td>";
      //echo "<td class='text-center'>".($dados_baixados[$i]['vitimas_encontradas']!=""?$dados_baixados[$i]['vitimas_encontradas']:"-")."</td>";
      echo "<td class='text-center'>".$dados_baixados[$i]['company_acron']."</td>";

      echo "<td class='text-center'>".($dados_baixados[$i]['qtd_providencias'] > 0 ? $dados_baixados[$i]['qtd_providencias']:"-")."</td>";

      if($providencias[$dados_baixados[$i]["id"]]["adm"]==1)
      {
          echo "<td class='text-center success'>ADM</td>";
      }else {
          echo "<td class='text-center text-muted'>-</td>";
      }

      if($providencias[$dados_baixados[$i]["id"]]["guin"]==1)
      {
          echo "<td class='text-center info'>GUI</td>";
      }else {
          echo "<td class='text-center text-muted'>-</td>";
      }



      echo "<td class='text-center'>".($dados_baixados[$i]['qtd_fotos'] > 0 ? $dados_baixados[$i]['qtd_fotos']:"-")."</td>";


      echo "<td class='text-center' style='white-space:nowrap;'>".$dados_baixados[$i]['status']."</td>";



      echo "<td class='actions text-center'>
              <a href='oct/FORM.php?filtro_data=".$_GET['filtro_data']."&id=".$dados_baixados[$i]['id']."' class='mb-xs mt-xs mr-xs btn btn-xs btn-default loading2'><i class='fa fa-pencil'></i></a>
            </td>";
      echo "</tr>";
    }

  }

?>
								  </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>



<!-- Modal Warning -->
								<!--	<a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="4">Remover 1</a>
                  <a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalRemover" remover_id="5">Remover 2</a>-->

									<div id="modalRemover" class="modal-block modal-header-color modal-block-warning mfp-hide">
										<section class="panel">
											<header class="panel-heading">
												<h2 class="panel-title">Atenção</h2>
											</header>
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-warning"></i>
													</div>
													<div class="modal-text">
														<h4>Você tem certeza que deseja remover este cadastro?</h4>
														<p>Esta operação é permanente.</p>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
                            <button class="btn btn-warning modal-confirm">Remover</button>
														<button class="btn btn-default modal-dismiss">Cancelar</button>
													</div>
												</div>
											</footer>
										</section>
									</div>



                  <!-- Modal FILTROS -->
                  <div class="modal fade"  id="modalFiltro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Filtros de pesquisa:</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top:-20px">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <form id="form_filtro" action="oct/index.php" method="post">
                        <div class="modal-body">
                          <? require_once("filtros.php"); ?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                          <button type="button" class="btn btn-primary" id="bt_submit">Filtrar</button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>

</section>

<script>

$("#bt_submit").click(function(){
    $('#modalFiltro').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $("#form_filtro").submit();
});
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
