<?
  session_start();
  $acao = "inserir";

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
						<div class="col-md-12 col-lg-12">

							<div class="tabs">
								<ul class="nav nav-tabs tabs-primary">
									<li class="active">
										<a href="#dados" data-toggle="tab">Dados</a>
									</li>
                </ul>
								<div class="tab-content">
									<div id="dados" class="tab-pane active">

										<form id="userform" name="userform" class="form-horizontal" method="post" action="usuarios/sqls.php" debug='0'>
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
													<label class="col-md-3 control-label" for="obs">Obserções</label>
													<div class="col-md-8">
                            <textarea class="form-control" name="obs" id="obs"><?=$d['obs'];?></textarea>
												  </div>
												</div>
											</fieldset>

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
												<div class="col-md-9">
													<div class="switch switch-sm switch-success">
														<input type="checkbox" name="e_superadmin" value='sim'  /> Sim
													</div>
												</div>
											</div>

											</fieldset>
											<div class="panel-footer">
												<div class="row">
													<!--<div class="col-md-9 col-md-offset-3">-->
														<div class="col-md-12">
														<button type="submit" class="btn btn-primary pull-right">Inserir</button>
													</div>
												</div>
											</div>

										</form>

									</div>

                 

                </div>




							</div>
						</div>

					<!-- end: page -->
				</section>
