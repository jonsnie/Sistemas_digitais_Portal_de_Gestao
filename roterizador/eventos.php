<?
  session_start();
  require_once("../libs/php/conn.php");
  require_once("../libs/php/funcoes.php");
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Eventos</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Roterizador</span></li>
        <li><span class='text-muted'>Eventos</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<?
  $sql = "SELECT
          	*
          FROM
          	leituras
          WHERE
          	posicao NOT IN ( '0.00,0.00', '0.000000,0.000000' )
          ORDER BY
          	datahora DESC
          LIMIT 50;";
  $rs=mysqli_query($connsystem,$sql);



?>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>
                      <a href="#" ic-get-from="#" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-search"></i> Filtros</button>
                      </a>

                      <a href="#" ic-get-from="#" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-cogs"></i> Configurações</button>
                      </a>

                      <a href="#" ic-get-from="#" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-file-text-o"></i> Carregar arquivo de dados</button>
                      </a>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none">
												<thead>
													<tr>
														<th>#</th>
                            <th>Eqp.</th>
                            <th>Evento</th>
														<th>Descrição</th>
                            <th>Valor</th>
                            <th class='text-center'>Data/Hora</th>
                            <th class='text-center'><i class='fa fa-bars'></i></th>
													</tr>
												</thead>
												<tbody>
<?
  while($d = mysqli_fetch_array($rs,MYSQLI_ASSOC))
  {
    if(utf8_encode($d['descricao_evento']) == "Posição GPS"){$d['valor'] = $d['posicao'];}
    //array2utf8($d);
    echo "<tr id='".$d['id']."'>";
    echo "<td class='text-muted'><small><sup>".$d['id']."</sup></small></td>";
    echo "<td class=''>".utf8_encode($d['id_equipamento'])."</td>";
    echo "<td class=''>".utf8_encode($d['id_evento'])."</td>";
    echo "<td class=''>".utf8_encode($d['descricao_evento'])."</td>";
    echo "<td class=''>".utf8_encode($d['valor'])."</td>";

        echo "<td class='text-center'>".formataData($d['datahora'],1)."</td>";
    //echo "<td class='text-center'>".$d['ultima_atividade']."</td>";

    echo "<td class='actions text-center'>
            <a href='#'  ic-get-from='#' ic-target='#wrap'><i class='fa fa-map-marker'></i></a>
            <a href='#'  ic-get-from='#' ic-target='#wrap'><i class='fa fa-search'></i></a>
            <a href='#'  ic-get-from='#' ic-target='#wrap'><i class='fa fa-history'></i></a>
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
        //url: "usuarios/sqls.php",
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
