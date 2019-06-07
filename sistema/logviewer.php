<?
session_start();
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");

$filtro = ($_GET['filtro']!=""?$_GET['filtro']:"todos");
$class_filtro[$filtro] = "active";

logger("Acesso","Logs");

?>
				<section role="main" class="content-body has-toolbar">
					<header class="page-header">
						<h2>Visualizador de Log</h2>
						<div class="right-wrapper pull-right" style='margin-right:15px;'>
							<ol class="breadcrumbs">
								<li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
								<li><span class='text-muted'>Configurações</span></li>
								<li><span class='text-muted'>Visualização de logs</span></li>
							</ol>
						</div>
					</header>

					<!-- start: page -->
<!--
					<div class="inner-toolbar clearfix">
						<ul>
							<li class="right">
								<ul class="nav nav-pills nav-pills-primary">
									<li>
										<label>Filtros:</label>
									</li>
									<li class="<?=$class_filtro['todos'];?>">
										<a href="#" ic-get-from="sistema/logviewer.php" ic-target="#wrap">Todos</a>
									</li>
									<li class="<?=$class_filtro['DEBUG'];?>">
										<a href="#" ic-get-from="sistema/logviewer.php?filtro=DEBUG" ic-target="#wrap">Debug</a>
									</li>
									<li class="<?=$class_filtro['INFO'];?>">
										<a href="#" ic-get-from="sistema/logviewer.php?filtro=INFO" ic-target="#wrap">Info</a>
									</li>
									<li class="<?=$class_filtro['WARNING'];?>">
										<a href="#" ic-get-from="sistema/logviewer.php?filtro=WARNING" ic-target="#wrap">Warning</a>
									</li>
									<li class="<?=$class_filtro['DANGER'];?>">
										<a href="#" ic-get-from="sistema/logviewer.php?filtro=DANGER" ic-target="#wrap">Danger</a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
-->
					<section class="panel">
						<div class="panel-body">
<?
	//if($filtro != "todos"){  $sql_filtro = " WHERE tipo = '".$filtro."'";}
	$sql = "SELECT U.name, L.id_user, L.timestamp, L.ip, L.module, L.action, L.obs
					FROM sepud.logs L
					LEFT JOIN sepud.users U ON U.id = L.id_user
					--WHERE id_user <> 1
					--WHERE L.obs like '%Baixou o%'
					ORDER BY L.timestamp DESC
					LIMIT 250";
	$rs  = pg_query($sql)or die("Erro ".__LINE__);
	while($aux = pg_fetch_assoc($rs)){ $d[] = $aux; }
	unset($sql,$rs);
	if(count($d)){
?>

<table class="table table-striped table-no-more table-bordered  mb-none">
	<thead>
		<tr>
			<th width="300px"><span class="text-weight-normal text-sm">Usuário</span></th>
			<th width="180px"><span class="text-weight-normal text-sm">data</span></th>
	<!--		<th><span class="text-weight-normal text-sm">IP</span></th>-->
			<th width="180px"><span class="text-weight-normal text-sm">Módulo</span></th>
			<th width="250px"><span class="text-weight-normal text-sm">Ação</span></th>
			<th><span class="text-weight-normal text-sm">Detalhamento</span></th>
		</tr>
	</thead>
	<tbody class="log-viewer">
	  <?
						for($i=0;$i<count($d);$i++)
						{
							switch($d[$i]['tipo'])
							{
								case "DEBUG":
										$icon = "<i class='fa fa-bug fa-fw text-muted text-md va-middle'></i><span class='va-middle'>Debug</span>";
										break;
								case "INFO":
										$icon = "<i class='fa fa-info fa-fw text-info text-md va-middle'></i><span class='va-middle'>Info</span>";
										break;
								case "WARNING":
										$icon = "<i class='fa fa-warning fa-fw text-warning text-md va-middle'></i><span class='va-middle'>Warning</span>";
										break;
								case "DANGER":
										$icon = "<i class='fa fa-times-circle fa-fw text-danger text-md va-middle'></i><span class='va-middle'>Danger</span>";
										break;
								default:
										$icon = "";
							}
							echo "<tr>";
								echo "<td>".$d[$i]['name']."</td>";
								echo "<td>".formataData($d[$i]['timestamp'],1)."</td>";
							//	echo "<td>".$d[$i]['ip']."</td>";
								echo "<td>".$d[$i]['module']."</td>";
								echo "<td>".$d[$i]['action']."</td>";
								echo "<td>".($d[$i]['obs']!="Null"?$d[$i]['obs']:"")."</td>";
							echo "</tr>";
						}
	echo "</tbody>
				</table>";
	}else
	{
		echo "<tr><td colspan='4'>
		<div class='alert alert-warning text-center col-md-6 col-md-offset-3'>
			Nenhum log encontrado no sistema.
		</div>";
	}
		?>


 </div>
</section>
