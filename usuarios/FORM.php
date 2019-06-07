<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  if($_GET['id'] != "")
  {
    $acao = "atualizar";
    $sql  = "SELECT * FROM sepud.users WHERE id = '".$_GET['id']."'";
    $res  = pg_query($sql)or die("Erro ".__LINE__);
    $d    = pg_fetch_assoc($res);
    logger("Acesso","Perfil de usuário", "Acesso aos dados: [".$_GET["id"]."] - ".$d['name']);
  }

?>
				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Perfil do Usuário</h2>

						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
				        <li><span class='text-muted'>Configurações</span></li>
				        <li><a href="usuarios/index.php"><span>Usuários</span></a></li>
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



<div class="row">
  <div class="col-md-6 col-md-offset-3">

										<form id="userform" name="userform" class="form-horizontal" method="post" action="usuarios/FORM_sql.php" debug='0'>


                      <h4 class="mb-xlg">Informações Pessoais</h4>
											<fieldset>
												<div class="form-group">
													<label class="col-md-2 control-label" for="name">Nome</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="name" name="name" placeholder='Nome completo' value="<?=$d['name'];?>">
													</div>

												</div>
												<div class="form-group">
													<label class="col-md-2 control-label" for="phone">Telefone</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="phone" name="phone" placeholder='(xx) xxxxx-xxxx' value="<?=$d['phone'];?>">
													</div>
												</div>


                        <div class="form-group">
                          <label class="col-md-2 control-label" for="cargo">Orgão</label>
                          <div class="col-md-10">
                            <select class="form-control" id="id_company" name="id_company">
                                <?
                                    $sql = "SELECT * FROM sepud.company ORDER BY name ASC";
                                    $res = pg_query($sql)or die();
                                    while($comp = pg_fetch_assoc($res))
                                    {
                                        if($comp['id'] == $d['id_company']){ $sel = "selected"; }else{ $sel = "";}
                                        echo "<option value='".$comp['id']."' ".$sel.">".$comp['name']."</option>";
                                    }
                                ?>
                            </select>
                          </div>
                        </div>


                        <div class="form-group">
                          <label class="col-md-2 control-label" for="area">Setor</label>
                          <div class="col-md-10">
                            <input type="text" class="form-control" id="area" name="area" placeholder="Setor" value="<?=$d['area'];?>">
                          </div>
                        </div>

                        <div class="form-group">
													<label class="col-md-2 control-label" for="job">Cargo</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="job" name="job" placeholder="Cargo" value="<?=$d['job'];?>">
													</div>
												</div>

                        <div class="form-group">
													<label class="col-md-2 control-label" for="observation">Observações</label>
													<div class="col-md-10">
                            <textarea class="form-control" name="observation" id="observation"><?=$d['observation'];?></textarea>
												  </div>
												</div>
											</fieldset>

											<hr class="dotted tall">
											<h4 class="mb-xlg">Informações de acesso</h4>
											<fieldset class="mb-xl">

												<div class="form-group">
													<label class="col-md-2 control-label" for="email">E-mail</label>
													<div class="col-md-10">
														<input type="text" class="form-control" id="email" name="email" placeholder='Endereço de e-mail' value="<?=$d['email'];?>">
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-2 control-label" for="senha">Senha</label>
													<div class="col-md-5">
														<input type="password" class="form-control" id="senha" name="senha" placeholder='Nova senha' value="">
													</div>
													<div class="col-md-5">
														<input type="password" class="form-control" id="senha_repete" name="senha_repete"  placeholder='Repita nova senha'>
													</div>
												</div>

                        <div class="form-group">
                          <label class="col-md-2 control-label" for="active">Status</label>
                          <div class="col-md-10">
                            <select class="form-control" id="active" name="active">

                                <option value="t" <?=($d['active']=="t"?"selected":"");?>>Ativo</option>
                                <option value="f" <?=($d['active']=="f"?"selected":"");?>>Inativo</option>

                            </select>
                          </div>
                        </div>

											</fieldset>


											<div class="panel-footer"  style="margin-top:20px;height:60px;margin-bottom:10px;">
												<div class="row pull-right">
													<!--<div class="col-md-9 col-md-offset-3">-->
														<div class="col-md-12">

<? if($acao != "atualizar")
{
              echo "<input type='hidden' name='acao' value='inserir' />";
              echo "<button type='submit' class='btn btn-primary pull-right loading'>Inserir</button>";
}else {
              echo "<input type='hidden' name='acao' value='atualizar' />";
              echo "<input type='hidden' name='id' value='".$_GET['id']."' />";
              echo "<a href='usuarios/index.php'><button type='button' class='btn btn-default loading'>Voltar</button></a>&nbsp;";
              echo "<button type='submit' class='btn btn-primary loading'>Atualizar</button>";
}
?>
  												</div>
												</div>
											</div>

										</form>
</div>
</div>


									</div>



                </div>




							</div>
						</div>

					<!-- end: page -->
				</section>
<script>
$(".loading").click(function(){ $(this).html("<i class=\"fa fa-spinner fa-spin\"></i> <small>Aguarde.</small>"); });
</script>
