<?
  error_reporting(0);
  session_start();
  require("../libs/php/funcoes.php");
  require("../libs/php/conn.php");
  $agora = now();

  if($_GET['filtro']=="dia"){
    $ontem = date('Y-m-d',strtotime("-1 days"));
    $filtro_sql = " EF.pubdate = '".$ontem."'";
    $txt_filtro = "Referência: ".formataData($ontem,1);
  }else{
    $filtro_sql = " EF.pubdate >= '".$agora['ano']."-".$agora['mes']."-01'";
    $txt_filtro = "Referência: ".$agora['mes_txt_c']."/".$agora['ano'];
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Dashboard</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Radares</span></li>
        <li><span class='text-muted'>Dashboard</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>
<div class="col-md-12">
								<section class="panel">
									<header class="panel-heading">
                    <div class="panel-actions" style='margin-top:-12px'>
                      <small class='text-muted'><?=$txt_filtro;?></small>
<!--
                      <a href="#" ic-get-from="clientes/FORM_novo_usuario.php" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-cogs"></i> Novo equipamento</button>
                      </a>
-->

<? if($_GET['filtro']=="dia"){   ?>
                      <a href="#" ic-get-from="radar/dashboard.php" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-search"></i> Acumulado mensal</button>
                      </a>
<? }else{ ?>
                      <a href="#" ic-get-from="radar/dashboard.php?filtro=dia" ic-target="#wrap">
                        <button type="button" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-search"></i> Último dia</button>
                      </a>
<? } ?>
                      <!--<a href="#" ic-get-from="sistema/logs.php" ic-target="#wrap" class="mb-xs mt-xs mr-xs btn btn-xs btn-primary"><i class="fa fa-user-plus"></i> Novo usuário !</a>-->
									  </div>
                  </header>
									<div class="panel-body">
										<div class="table-responsive">
											<table class="table table-hover mb-none">
												<thead>
														<th>Equipamento</th>
                            <th>Endereço</th>
                            <th class='text-center'>Contador</th>
                            <th class='text-center'><i class='fa fa-search'></i></th>
													</tr>
												</thead>
												<tbody>
<?

    $sql  = "SELECT  EF.equipment,  EQ.address,
 (SUM(F.speed_00_10) + 	SUM(F.speed_11_20) + 	SUM(F.speed_21_30) + 	SUM(F.speed_31_40) +
	SUM(F.speed_41_50) +	SUM(F.speed_51_60) +	SUM(F.speed_61_70) +	SUM(F.speed_71_80) +
	SUM(F.speed_81_90) +	SUM(F.speed_91_100)+	SUM(F.speed_100_up))
  AS contador_veiculos FROM radars.equipment_files as EF
LEFT JOIN radars.flows as F       ON F.equipment_files_id = EF.id
JOIN      radars.equipments as EQ ON EQ.equipment = EF.equipment
WHERE  $filtro_sql
GROUP BY EF.equipment, EQ.address
ORDER BY 	(SUM(F.speed_00_10) + SUM(F.speed_11_20) + 	SUM(F.speed_21_30) + 	SUM(F.speed_31_40) +
					 SUM(F.speed_41_50) +	SUM(F.speed_51_60) +	SUM(F.speed_61_70) +	SUM(F.speed_71_80) +
					 SUM(F.speed_81_90) +	SUM(F.speed_91_100)+	SUM(F.speed_100_up)) DESC";

    $res  = pg_query($conn_neogrid,$sql);

    while($d = pg_fetch_array($res))
    {

        echo "<tr id='".$d['equipment']."'>";
        echo "<td class=''>".$d['equipment']."</td>";
        echo "<td class=''>".$d['address']."</td>";
        echo "<td class='text-center'>".number_format($d['contador_veiculos'],0,'','.')."</td>";


        echo "<td class='actions text-center'>
                <a href='#' ic-get-from='radar/detalhado.php' ic-target='#wrap'>
                  <i class='fa fa-eye'></i>
                </a>
              </td>";

        echo "</tr>";
  }

?>
								  </tbody>
											</table>
										</div>
									</div>
<div class="panel-footer">
  <small class='text-muted pull-right'><?=$txt_filtro;?></small>
</div>

								</section>
							</div>



</section>
<script>

</script>
