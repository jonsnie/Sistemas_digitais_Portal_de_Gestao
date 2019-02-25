<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
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
                U.company,
                U.company_acron,
                (SELECT COUNT ( * ) FROM sepud.oct_victim WHERE id_events = EV.ID) as vitimas_encontradas
          FROM
                sepud.oct_events AS EV
          JOIN  sepud.oct_event_type AS EVT ON EV.id_event_type = EVT.id
          JOIN  sepud.users AS U ON U.id = EV.id_user
          WHERE
              --DATE BETWEEN '2018-12-01 00:00:00' AND '2018-12-31 23:59:59' OR
                EV.active = 't'";
  $rs  = pg_query($conn_neogrid,$sql);
  $total_oc = 0;
  while($d = pg_fetch_assoc($rs))
  {
      if($d['active']=='t'){
        $dados[] = $d;
      }

      $total_oc++;
  }
  if(!pg_num_rows($rs))
  {
    echo "<div class='col-md-12'>
    								<section class='panel'>
                    <header class='panel-heading'>
                      Abertura de ocorrência:
                      <div class='panel-actions'>
                      <a href='oct/FORM.php'>
                        <button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-danger'><i class='fa fa-exclamation-triangle'></i> Abrir nova ocorrência</button>
                      </a>
                      </div>
                    </header>
                      <div class='panel-body'>
                        <div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhuma ocorrência em aberto no sistema.</div>
                      </div>
                    </section>
          </div>";

  }else
  {
?>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>
                      <a href="oct/FORM.php">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-danger"><i class="fa fa-exclamation-triangle"></i> Abrir nova ocorrência</button>
                      </a>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
<?
?>

                    <div class="table-responsive">
											<table class="table table-hover mb-none">
												<thead>
                          <tr>
                            <th colspan="2"></th>
                            <th colspan="2" class='text-center'>Datas</th>
                            <th colspan="2" class='text-center'>Vítimas</th>
                            <th colspan="3"></th>
                          <tr>
														<th>#</th>
														<th>Tipo</th>
														<th class='text-center'>Abertura</th>
                            <th class='text-center'>Chegada</th>
                            <th class='text-center'>Informado</th>
                            <th class='text-center'>Encontrado</th>
                            <th class='text-center'>Origem</th>
                            <th class='text-center'>Status</th>

                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>

<?
  for($i = 0; $i < count($dados); $i++)
  {

    $dt_abertura = formataData($dados[$i]['date'],1);
    $dt_chegada  = formataData($dados[$i]['arrival'],1);



    echo "<tr id='".$dados[$i]['id']."'>";
    echo "<td><b>".$dados[$i]['id']."</b></td>";
    echo "<td>".$dados[$i]['event_type']."</td>";
    echo "<td>".$dt_abertura."</td>";
    echo "<td class='text-center'>".$dt_chegada."</td>";
    echo "<td class='text-center'>".($dados[$i]['victim_inform']!=""?$dados[$i]['victim_inform']:"- - -")."</td>";
    //echo "<td class='text-center'>".($dados[$i]['victim_found']!=""?$dados[$i]['victim_found']:"- - -")."</td>";
    echo "<td class='text-center'>".($dados[$i]['vitimas_encontradas']!=""?$dados[$i]['vitimas_encontradas']:"-")."</td>";
    echo "<td class='text-center'>".$dados[$i]['company_acron']."</td>";
    echo "<td class='text-center'>".$dados[$i]['status']."</td>";



    echo "<td class='actions text-center'>
            <a href='oct/FORM.php?id=".$dados[$i]['id']."' class='mb-xs mt-xs mr-xs btn btn-xs btn-default loading2'><i class='fa fa-pencil'></i></a>
          </td>";
    echo "</tr>";
  }

?>
								  </tbody>
											</table>
										</div>
									</div>
								</section>
							</div>

<? } ?>

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
</section>
<script>
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
