<?
session_start();
require_once("../libs/php/conn.php");
require_once("../libs/php/funcoes.php");

$filtro = ($_GET['filtro']!=""?$_GET['filtro']:"todos");
$class_filtro[$filtro] = "active";


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
					<div class="inner-toolbar clearfix">
						<ul>
<!--
							<li>
								<button type="button" class="btn btn-primary"><i class="fa fa-pause m-none"></i> Pause</button>
							</li>
-->
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

					<section class="panel">
						<div class="panel-body">
<?
	if($filtro != "todos"){  $sql_filtro = " WHERE tipo = '".$filtro."'";}
	$sql = "SELECT * FROM log ".$sql_filtro." ORDER BY data DESC";
	$rs  = mysql_query($sql)or die(mysql_error());
	while($aux = mysql_fetch_assoc($rs)){ $d[] = $aux; }
	unset($sql,$rs);
	if(count($d)){
?>

<table class="table table-striped table-no-more table-bordered  mb-none">
	<thead>
		<tr>
			<th><span class="text-weight-normal text-sm">Tipo</span></th>
			<th><span class="text-weight-normal text-sm">data</span></th>
			<th><span class="text-weight-normal text-sm">IP</span></th>
			<th><span class="text-weight-normal text-sm">Mensagem</span></th>
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
								echo "<td>".$icon."</td>";
								echo "<td>".formataData($d[$i]['data'],1)."</td>";
								echo "<td>".$d[$i]['ip']."</td>";
								echo "<td>".$d[$i]['mensagem']."</td>";
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
