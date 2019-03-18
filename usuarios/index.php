<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");
  logger("Acesso","Usuários");
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Listagem de usuários do sistema</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Configurações</span></li>
        <li><span class='text-muted'>Usuários</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


<?
  $sql = "SELECT
          	C.name as company_name, C.acron as company_acron,
          	U.*
          FROM
          	   sepud.users   U
          JOIN sepud.company C ON C.id = U.id_company
          ORDER BY U.id DESC";
  $rs  = pg_query($conn_neogrid,$sql);
  if(!pg_num_rows($rs))
  {
    echo "<div class='col-md-12'>
    								<section class='panel'>
                    <header class='panel-heading'>
                    Realizar primeiro de cadastro de usuário:
                      <div class='panel-actions'>
                        <button type='button' class='mb-xs mt-xs mr-xs btn btn-xs btn-primary'><i class='fa fa-user-plus'></i> Novo Usuário</button>
                      </div>
                    </header>
                      <div class='panel-body'>
                        <div class='alert alert-warning col-md-6 col-md-offset-3 text-center'><strong>Aviso: </strong> Nenhum usuário cadastrado no sistema.</div>
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
                      <a href="usuarios/FORM_novo_usuario.php">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário</button>
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
														<th>Nome</th>
														<!--<th>E-mail</th>-->
                            <th class='text-center'>Orgão</th>
                            <!--<th class='text-center'>Setor</th>
                            <th class='text-center'>Função</th>-->
                            <th class='text-center'>Ativo</th>
                            <th class='text-center'>Em ativação</th>
                            <th class='text-center'>Último acesso</th>
                            <th class='text-center'><i class='fa fa-cogs'></i></th>
													</tr>
												</thead>
												<tbody>
<?
  while($d = pg_fetch_assoc($rs))
  {
    //array2utf8($d);
    echo "<tr id='".$d['id']."'>";
    echo "<td class='text-muted'>".$d['id']."</td>";
    echo "<td>".$d['name']."</td>";
    //echo "<td>".$d['email']."</td>";
    echo "<td class='text-center'>".$d['company_acron']."</td>";
    //echo "<td class='text-center'>".$d['area']."</td>";
    //echo "<td class='text-center'>".$d['job']."</td>";

    echo "<td class='text-center'>";
      echo ($d['active'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";
    echo "<td class='text-center ".($d['in_ativaction'] == 't'?"warning":"")."'>";
      echo ($d['in_ativaction'] == 't'?"<i class='fa fa-check-square-o'></i>":"<i class='fa fa-square-o'></i>");
    echo "</td>";

    echo "<td class='text-center'>".formataData($d['ultimo_acesso'],1)."</td>";

    echo "<td class='actions text-center'>
            <a href='usuarios/FORM.php?id=".$d['id']."'><i class='fa fa-pencil'></i></a>
            <a href='usuarios/FORM_sql.php?acao=remover&id=".$d['id']."' class='delete-row'><i class='fa fa-trash-o'></i></a>
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


<script>

</script>
