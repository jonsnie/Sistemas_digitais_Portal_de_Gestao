<?
  session_start();
  require_once("../libs/php/conn.php");

	if($_GET['id'] != "")
	{
    	$acao = "atualizar";
    	$sql  = "SELECT * FROM usuarios WHERE id = '".$_GET['id']."'";
		$rs   = mysql_query($sql)or die(mysql_error());
		$d    = mysql_fetch_assoc($rs);
    if($d['foto'] !="")
    {
      $imgsrc 	= "usuarios/foto_ver.php?id=".$_GET['id'];
	  $tem_foto = true;
    }else
    {
      $imgsrc   = "assets/images/icon-user-default.png";
	  $tem_foto = false;
    }
    unset($sql,$rs, $d['foto']);
	}
?>

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Perfil do Usuário</h2>
						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="#" ic-get-from="usuarios/index.php" ic-target="#wrap"><span>Usuários</span></a></li>
								<li><span class='text-muted'>Perfil do usuário</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->

					<div class="row">
						<div class="col-md-4 col-lg-3">

							<section class="panel">
								<div class="panel-body">
									<div class="thumb-info mb-md">
										<img src="<?=$imgsrc;?>" class="rounded img-responsive" alt="<?=$d['nome']." ".$d['sobrenome'];?>">
										<div class="thumb-info-title">
											<span class="thumb-info-inner"><?=$d['nome']." ".$d['sobrenome'];?></span>
											<span class="thumb-info-type"><?=$d['cargo'];?></span>
										</div>
									</div>

<!--
									<div class="widget-toggle-expand mb-md">
										<div class="widget-header">
											<h6>Profile Completion</h6>
											<div class="widget-toggle">+</div>
										</div>
										<div class="widget-content-collapsed">
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
													60%
												</div>
											</div>
										</div>
										<div class="widget-content-expanded">
											<ul class="simple-todo-list">
												<li class="completed">Update Profile Picture</li>
												<li class="completed">Change Personal Information</li>
												<li>Update Social Media</li>
												<li>Follow Someone</li>
											</ul>
										</div>
									</div>

									<hr class="dotted short">

									<h6 class="text-muted">Sobre</h6>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam quis vulputate quam. Interdum et malesuada</p>
									<div class="clearfix">
										<a class="text-uppercase text-muted pull-right" href="#">(View All)</a>
									</div>
-->
									<hr class="dotted short">

									<div class="social-icons-list">
									<!--<a rel="tooltip" data-placement="bottom" target="_blank" href="http://www.facebook.com" data-original-title="Facebook"><i class="fa fa-facebook"></i><span>Facebook</span></a>
										<a rel="tooltip" data-placement="bottom" href="http://www.twitter.com" data-original-title="Twitter"><i class="fa fa-twitter"></i><span>Twitter</span></a>
										<a rel="tooltip" data-placement="bottom" href="http://www.linkedin.com" data-original-title="Linkedin"><i class="fa fa-linkedin"></i><span>Linkedin</span></a> -->

                    <div class='pull-right' style='margin-top:-5px'>
                      <form id="uploadForm"  name="uploadForm" action="usuarios/foto_upload.php" method="post" rel='no_ajax' debug='0'>
                          
						  <span id="foto_loading" class='hide'><i class="fa fa-spin fa-spinner text-danger"></i> <span class='text-muted'>Carregando foto</span></span>

						  <button type="button" id="bt_foto" class="mb-xs mt-xs mr-xs btn btn-xs btn-default"><i class="fa fa-camera text-muted"></i></button>
                          <input name="id"  id="id" type="hidden" value="<?=$_GET['id'];?>" />
                          <input name="userImage" id="userImage" type="file" class='hide'/>
						  <input name="acao" id="acao" type="hidden" value='inserir'/>

					  <? if($tem_foto){ ?>
						  		<button id="bt_foto_remover" class="mb-xs mt-xs mr-xs modal-basic btn btn-xs btn-default" href="#modalHeaderColorWarning"><i class='fa fa-trash-o text-muted'></i></button>
					  <? } ?>
					  </form>
					  
					  		  
                      
					  <script type="text/javascript">
                      $("#bt_foto").click(function() {
                        $("#userImage").click();
                      });
					  
                        $(document).ready(function (e){
                            $("#uploadForm").on('change',(function(e){
								$("#bt_foto").addClass("hide");
								$("#bt_foto_remover").addClass("hide");
								$("#foto_loading").removeClass("hide");
                                e.preventDefault();
                                $.ajax({
                                  url: "usuarios/foto_upload.php",
                                  type: "POST",
                                  data:  new FormData(this),
                                  contentType: false,
                                  cache: false,
                                  processData:false,
                                  success: function(data)                                    { $("#wrap").html(data);                                },
                                    error: function()                                        { $("#wrap").html("Erro no envio do arquivo de foto."); }
                                });
                          }));
                        });
                      </script>



                    </div>

                  </div>


								</div>
							</section>
<!--
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>

									<h2 class="panel-title">
										<span class="va-middle">Informações Gerais:</span>
									</h2>
								</header>
								<div class="panel-body">
									<div class="content">
											. . .
									</div>
								</div>
							</section>
-->
<!--
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>

									<h2 class="panel-title">
										<span class="label label-primary label-sm text-weight-normal va-middle mr-sm">198</span>
										<span class="va-middle">Friends</span>
									</h2>
								</header>
								<div class="panel-body">
									<div class="content">
										<ul class="simple-user-list">
											<li>
												<figure class="image rounded">
													<img src="assets/images/!sample-user.jpg" alt="Joseph Doe Junior" class="img-circle">
												</figure>
												<span class="title">Joseph Doe Junior</span>
												<span class="message truncate">Lorem ipsum dolor sit.</span>
											</li>
											<li>
												<figure class="image rounded">
													<img src="assets/images/!sample-user.jpg" alt="Joseph Junior" class="img-circle">
												</figure>
												<span class="title">Joseph Junior</span>
												<span class="message truncate">Lorem ipsum dolor sit.</span>
											</li>
											<li>
												<figure class="image rounded">
													<img src="assets/images/!sample-user.jpg" alt="Joe Junior" class="img-circle">
												</figure>
												<span class="title">Joe Junior</span>
												<span class="message truncate">Lorem ipsum dolor sit.</span>
											</li>
											<li>
												<figure class="image rounded">
													<img src="assets/images/!sample-user.jpg" alt="Joseph Doe Junior" class="img-circle">
												</figure>
												<span class="title">Joseph Doe Junior</span>
												<span class="message truncate">Lorem ipsum dolor sit.</span>
											</li>
										</ul>
										<hr class="dotted short">
										<div class="text-right">
											<a class="text-uppercase text-muted" href="#">(View All)</a>
										</div>
									</div>
								</div>
								<div class="panel-footer">
									<div class="input-group input-search">
										<input type="text" class="form-control" name="q" id="q" placeholder="Search...">
										<span class="input-group-btn">
											<button class="btn btn-default" type="submit"><i class="fa fa-search"></i>
											</button>
										</span>
									</div>
								</div>
							</section>
-->
<!--
							<section class="panel">
								<header class="panel-heading">
									<div class="panel-actions">
										<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
										<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
									</div>

									<h2 class="panel-title">Popular Posts</h2>
								</header>
								<div class="panel-body">
									<ul class="simple-post-list">
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="assets/images/post-thumb-1.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Nullam Vitae Nibh Un Odiosters</a>
												<div class="post-meta">
													 Jan 10, 2013
												</div>
											</div>
										</li>
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="assets/images/post-thumb-2.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Vitae Nibh Un Odiosters</a>
												<div class="post-meta">
													 Jan 10, 2013
												</div>
											</div>
										</li>
										<li>
											<div class="post-image">
												<div class="img-thumbnail">
													<a href="#">
														<img src="assets/images/post-thumb-3.jpg" alt="">
													</a>
												</div>
											</div>
											<div class="post-info">
												<a href="#">Odiosters Nullam Vitae</a>
												<div class="post-meta">
													 Jan 10, 2013
												</div>
											</div>
										</li>
									</ul>
								</div>
							</section>
-->

						</div>
						<div class="col-md-8 col-lg-6">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="<?=($_GET['tab']=="visao" || $_GET['tab']== ""? "active":"");?>">
										<a href="#visao" data-toggle="tab">Visão Geral</a>
									</li>
									<li class="<?=($_GET['tab']=="dados"?"active":"");?>">
										<a href="#dados" data-toggle="tab">Informações</a>
									</li>
                <li class="<?=($_GET['tab']=="permissoes"?"active":"");?>">
                  <a href="#permissoes" data-toggle="tab">Permissões</a>
                </li>
                <li class="<?=($_GET['tab']=="cofre"?"active":"");?>">
                  <a href="#cofre" data-toggle="tab">Cofre</a>
                </li>
                </ul>
								<div class="tab-content">
									<div id="visao" class="tab-pane <?=($_GET['tab']=="visao" || $_GET['tab']== ""? "active":"");?>">

<?
/*
<div class="input-group file">
<input id="uploadBox" type="text" class="form-control" onClick="$('#arquivo_fisico').click();" placeholder="Procurar Arquivo">
<span class="input-group-addon"><i class="fa fa-folder-o"></i></span>
</div>
  <!--
    <div id="uploadBox" class="well well-sm text-center" onClick="$('#fileToUpload').click();">
        <h5>Selecione o Arquivo<br><small>(Tamano máximo por arquivo: 2Gb)</small></h5>
    </div>
-->

   <input class="hide" id="arquivo_fisico" name="arquivo_fisico" type="file"
   onchange="$('#uploadBox').val($(this).val().split('\\').pop());$('#progbar_info').html($(this).val().split('\\').pop());">

*/
?>
                    <?
                      echo "<pre>Variaveis:<br>$"."_"."GET: <br>";
                      print_r($_GET);
                      echo "<br>$"."_"."POST:<br>";
                      print_r($_POST);
                      echo "<br>Database returns:<br>";
                      print_r($d);
                      echo "</pre>";
                    ?>

<!--
                    <h4 class="mb-md">Atualização de Status</h4>
										<section class="simple-compose-box mb-xlg">
											<form method="get" action="/">
												<textarea name="message-text" data-plugin-textarea-autosize placeholder="What's on your mind?" rows="1"></textarea>
											</form>
											<div class="compose-box-footer">
												<ul class="compose-toolbar">
													<li>
														<a href="#"><i class="fa fa-camera"></i></a>
													</li>
													<li>
														<a href="#"><i class="fa fa-map-marker"></i></a>
													</li>
												</ul>
												<ul class="compose-btn">
													<li>
														<a class="btn btn-primary btn-xs">Post</a>
													</li>
												</ul>
											</div>
										</section>
-->

<!--
										<h4 class="mb-xlg">Linha do tempo</h4>
										<div class="timeline timeline-simple mt-xlg mb-md">
											<div class="tm-body">
												<div class="tm-title">
													<h3 class="h5 text-uppercase">November 2013</h3>
												</div>
												<ol class="tm-items">
													<li>
														<div class="tm-box">
															<p class="text-muted mb-none">7 months ago.</p>
															<p>
																It's awesome when we find a good solution for our projects, Porto Admin is <span class="text-primary">#awesome</span>
															</p>
														</div>
													</li>
													<li>
														<div class="tm-box">
															<p class="text-muted mb-none">7 months ago.</p>
															<p>
																What is your biggest developer pain point?
															</p>
														</div>
													</li>
													<li>
														<div class="tm-box">
															<p class="text-muted mb-none">7 months ago.</p>
															<p>
																Checkout! How cool is that!
															</p>
															<div class="thumbnail-gallery">
																<a class="img-thumbnail lightbox" href="assets/images/projects/project-4.jpg" data-plugin-options='{ "type":"image" }'>
																	<img class="img-responsive" width="215" src="assets/images/projects/project-4.jpg">
																	<span class="zoom">
																		<i class="fa fa-search"></i>
																	</span>
																</a>
															</div>
														</div>
													</li>
												</ol>
											</div>
										</div>
-->


                  </div>



									<div id="dados" class="tab-pane <?=($_GET['tab']=="dados"?"active":"");?>">

										<form id="userform" name="userform" class="form-horizontal" method="post" action="usuarios/sqls.php" debug='0'>

                      <input type="hidden" id="id" name="id" value="<?=$_GET['id'];?>" />
                      <input type="hidden" id="tab" name="tab" value="dados" />
                      <input type="hidden" id="acao" name="acao" value="<?=$acao;?>" />

                      <h4 class="mb-xlg">Informações Pessoais</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileFirstName">Nome</label>
													<div class="col-md-4">
														<input type="text" class="form-control" id="nome" name="nome" value='<?=$d['nome'];?>' placeholder='Nome'>
													</div>
													<div class="col-md-4">
														<input type="text" class="form-control" id="sobrenome" name="sobrenome" value='<?=$d['sobrenome'];?>' placeholder='Sobrenome'>
													</div>
												</div>

<!--
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileLastName">Sobrenome</label>
													<div class="col-md-8">
														<input type="text" class="form-control" id="profileLastName" value='<?=$d['sobrenome'];?>'>
													</div>
												</div>
-->
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileAddress">E-mail</label>
													<div class="col-md-8">
														<input type="text" class="form-control" id="email" name="email" value='<?=$d['email'];?>'>
													</div>
												</div>
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileCompany">Telefones</label>
													<div class="col-md-4">
														<input type="text" class="form-control" id="celular" name="celular" placeholder='Celular' value='<?=$d['celular'];?>'>
													</div>
													<div class="col-md-4">
														<input type="text" class="form-control" id="tefelone" name="telefone" placeholder='Pessoal' value='<?=$d['telefone'];?>'>
													</div>
												</div>

                        <div class="form-group">
													<label class="col-md-3 control-label" for="cargo">Cargo</label>
													<div class="col-md-8">
														<input type="text" class="form-control" id="cargo" name="cargo" value='<?=$d['cargo'];?>'>
													</div>
												</div>

                        <div class="form-group">
													<label class="col-md-3 control-label" for="obs">Observações</label>
													<div class="col-md-8">
                            <textarea class="form-control" name="obs" id="obs"><?=$d['obs'];?></textarea>
												  </div>
												</div>
											</fieldset>

<!--
											<hr class="dotted tall">
											<h4 class="mb-xlg">About Yourself</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-3 control-label" for="profileBio">Biographical Info</label>
													<div class="col-md-8">
														<textarea class="form-control" rows="3" id="profileBio"></textarea>
													</div>
												</div>
												<div class="form-group">
													<label class="col-xs-3 control-label mt-xs pt-none">Public</label>
													<div class="col-md-8">
														<div class="checkbox-custom checkbox-default checkbox-inline mt-xs">
															<input type="checkbox" checked="" id="profilePublic">
															<label for="profilePublic"></label>
														</div>
													</div>
												</div>
											</fieldset>
-->
											<hr class="dotted tall">
											<h4 class="mb-xlg">Informações de acesso<br><small><sup class='text-muted'>(Armazenadas de forma criptografada)</sup></small></h4>
											<fieldset class="mb-xl">

												<div class="form-group">
													<label class="col-md-3 control-label" for="username">Usuário</label>
													<div class="col-md-8">
                            <?
                              if($d['username']!=""){ $placeholder = "Usuário já possui login de acesso.";}
                              else                  { $placeholder = "Login para acesso ao sistema.";     }
                            ?>
														<input type="text" class="form-control" id="username" name="username" placeholder='<?=$placeholder;?>'>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-3 control-label" for="profileNewPassword">Senha</label>
													<div class="col-md-4">
														<input type="password" class="form-control" id="senha" name="senha" placeholder='Nova senha'>
													</div>
													<div class="col-md-4">
														<input type="password" class="form-control" id="senha_repete" name="senha_repete"  placeholder='Repita nova senha'>
													</div>
												</div>


											<div class="form-group">
												<label class="control-label col-md-3">É Superadmin ?</label>
												<div class="col-md-2">
													<div class="switch switch-sm switch-success">
														<input type="checkbox" name="e_superadmin" value='sim' <?=($d['e_superuser']==1?"checked='checked'":'');?>  /> Sim
													</div>
												</div>

											<label class="control-label col-md-3">Ativo</label>
												<div class="col-md-2">
													<div class="switch switch-sm switch-success">
														<input type="checkbox" name="ativo" data-plugin-ios-switch <?=($d['ativo']==1?"checked='checked'":'');?> />
													</div>
												</div>

											</div>



                      
									</fieldset>


											<div class="panel-footer">
												<div class="row">
													<!--<div class="col-md-9 col-md-offset-3">-->
														<div class="col-md-12">
														<button type="submit" class="btn btn-primary pull-right">Atualizar</button>
													</div>
												</div>
											</div>

										</form>

									</div>

                  <div id="permissoes" class="tab-pane <?=($_GET['tab']=="permissoes"?"active":"");?>">
                      Permissões do sistema<br>(Em desenvolvimento)
                  </div>

                  <div id="cofre" class="tab-pane <?=($_GET['tab']=="cofre"?"active":"");?>">
                      Cofre pessoal para armazenamento de informações criptgrafadas.<br>(Em desenvolvimento)
                  </div>

                </div>




							</div>
						</div>
						<div class="col-md-12 col-lg-3 text-right">

							<h4 class="mb-md">Estatísticas<h4>
							<ul class="simple-card-list mb-xlg">
								<li class="primary">
									<h3>0/0</h3>
									<p>Chamados abertos/atendidos</p>
								</li>
								<li class="primary">
									<h3>0</h3>
									<p>Clientes captados</p>
								</li>
								<li class="primary">
									<h3>R$ 0,00</h3>
									<p>Comissão mensal</p>
								</li>
							</ul>
<!--
							<h4 class="mb-md">Projects</h4>
							<ul class="simple-bullet-list mb-xlg">
								<li class="red">
									<span class="title">Porto Template</span>
									<span class="description truncate">Lorem ipsom dolor sit.</span>
								</li>
								<li class="green">
									<span class="title">Tucson HTML5 Template</span>
									<span class="description truncate">Lorem ipsom dolor sit amet</span>
								</li>
								<li class="blue">
									<span class="title">Porto HTML5 Template</span>
									<span class="description truncate">Lorem ipsom dolor sit.</span>
								</li>
								<li class="orange">
									<span class="title">Tucson Template</span>
									<span class="description truncate">Lorem ipsom dolor sit.</span>
								</li>
							</ul>

							<h4 class="mb-md">Messages</h4>
							<ul class="simple-user-list mb-xlg">
								<li>
									<figure class="image rounded">
										<img src="assets/images/!sample-user.jpg" alt="Joseph Doe Junior" class="img-circle">
									</figure>
									<span class="title">Joseph Doe Junior</span>
									<span class="message">Lorem ipsum dolor sit.</span>
								</li>
								<li>
									<figure class="image rounded">
										<img src="assets/images/!sample-user.jpg" alt="Joseph Junior" class="img-circle">
									</figure>
									<span class="title">Joseph Junior</span>
									<span class="message">Lorem ipsum dolor sit.</span>
								</li>
								<li>
									<figure class="image rounded">
										<img src="assets/images/!sample-user.jpg" alt="Joe Junior" class="img-circle">
									</figure>
									<span class="title">Joe Junior</span>
									<span class="message">Lorem ipsum dolor sit.</span>
								</li>
								<li>
									<figure class="image rounded">
										<img src="assets/images/!sample-user.jpg" alt="Joseph Doe Junior" class="img-circle">
									</figure>
									<span class="title">Joseph Doe Junior</span>
									<span class="message">Lorem ipsum dolor sit.</span>
								</li>
							</ul>
						</div>
-->
					</div>
					<!-- end: page -->
			<!-- Modal Warning -->
									<!--<a class="mb-xs mt-xs mr-xs modal-basic btn btn-warning" href="#modalHeaderColorWarning">Warning</a>-->
									

									<div id="modalHeaderColorWarning" class="modal-block modal-header-color modal-block-warning mfp-hide">
										<section class="panel">
											
											<div class="panel-body">
												<div class="modal-wrapper">
													<div class="modal-icon">
														<i class="fa fa-warning"></i>
													</div>
													<div class="modal-text">
														<h4>A foto será apagada permanentemente.</h4>
														<p>Esta operação não é reversível</p>
													</div>
												</div>
											</div>
											<footer class="panel-footer">
												<div class="row">
													<div class="col-md-12 text-right">
														<button class="btn btn-warning modal-confirm">Apagar</button>
														<button class="btn btn-default modal-dismiss">Cancelar</button>
													</div>
												</div>
											</footer>

										</section>
									</div>
<script>


(function( $ ) {
	'use strict';
	
	$('.modal-basic').magnificPopup({
		type: 'inline',
		preloader: false,
		modal: true
	});
	
	$(document).on('click', '.modal-dismiss', function (e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

	$(document).on('click', '.modal-confirm', function (e) {
      $("#bt_foto").addClass("hide");
	  $("#bt_foto_remover").addClass("hide");
	  var remover_id = $("#id").val();
      var stack_bottomright = {"dir1": "up", "dir2": "left", "firstpos1": 15, "firstpos2": 15};
      e.preventDefault();
  	  $.magnificPopup.close();
	  $.ajax({
        method: "POST",
        url: "usuarios/foto_upload.php",
        data: { id: remover_id, acao: "remover" },
		success: function(data){ $('#wrap').html(data);	}
      }).done(function(msg){
          var notice = new PNotify({
                title: 'Sucesso',
                text:  'Foto removida.',
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