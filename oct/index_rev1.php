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

 if(isset($dados)){
   if(count($dados)==1){ $txt_oc_abertas  = "<b>1</b> ocorrência aberta"; }else{ $txt_oc_abertas  = "<b>".count($dados)."</b> ocorrências abertas";}
 }else{ $txt_oc_abertas  = "Nenhuma ocorrência aberta";}


 if(isset($dados_baixados)){
   if(count($dados_baixados)==1){ $txt_oc_baixadas = "<b>1</b> ocorrência fechada."; }else{ $txt_oc_baixadas = "<b>".count($dados_baixados)."</b> ocorrências fechadas."; }
 }else{ $txt_oc_baixadas = "Nenhuma ocorrência baixada.";}


 if(!isset($dados) && !isset($dados_baixados)){ $txt_oc_abertas = "Nenhuma ocorrência gerada neste dia."; unset($txt_oc_baixadas); }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Ocorrências de trânsito <sup>(Rev 1)</sup></h2>
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
									<header class="panel-heading" style="height:70px">
                    <span class="text-muted"> Data de referência: </span><b><?=$data_atual;?></b>
                    <br><small class="text-muted"><?=$txt_oc_abertas;?><?=($txt_oc_baixadas!=""?", ".$txt_oc_baixadas:"");?>
                        </small>
                    <div class="panel-actions" style='margin-top:0px;'>


  <?

    echo "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modalFiltro'>
          <i class='fa fa-search'></i>
          </button>";

if(!$bt_filtro_placa){ ?>
  <a href="oct/index0.php?filtro_data=<?=$anterior;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play fa-rotate-180"></i></button>
  </a>
<? }else{
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play fa-rotate-180'></i></button>";
}


?>
  <? if($bt_prox || $bt_filtro_placa){ ?>
  <a href="oct/index0.php?filtro_data=">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success">Ir para hoje</button>
  </a>
<? if(!$bt_filtro_placa){ ?>
  <a href="oct/index0.php?filtro_data=<?=$proximo;?>">
    <button type="button" class="mb-xs mt-xs mr-xs btn btn-success"><i class="fa fa-play"></i></button>
  </a>
<? }else {
  echo "<button type='button' class='mb-xs mt-xs mr-xs btn btn-success disabled'><i class='fa fa-play'></i></button>";
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

                    <div class="table-responsive">

                      <table class="table mb-none">

<?
echo "<tr><td colspan='10' class='success'>Ocorrências ativas</td><tr>";
if(isset($dados) && count($dados))
{
    for($i = 0; $i < count($dados); $i++)
    {

      $dt_abertura = formataData($dados[$i]['date'],1);
      $dt_chegada  = formataData($dados[$i]['arrival'],1);
      $qtd_prov    = ($dados[$i]['qtd_providencias'] > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'>".$dados[$i]['qtd_providencias']."</span>":"-");
      $qtd_fotos   = ($dados[$i]['qtd_fotos']        > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'><b>".$dados[$i]['qtd_fotos']."</b> <sup><i class='fa fa-camera'></i></sup></span>":"-");

      if($providencias[$dados[$i]["id"]]["adm"]==1){  $prov_adm = "<span class='text-center mb-xs mt-xs mr-xs btn btn-success'>ADM</span>"; }
      else{                                           $prov_adm = "<span class='text-center text-muted'>-</span>";}
      if($providencias[$dados[$i]["id"]]["guin"]==1){ $prov_gui = "<span class='text-center mb-xs mt-xs mr-xs btn btn-info'>GUI</span>";    }
      else{                                           $prov_gui = "<span class='text-center text-muted'>-</span>";}


    ?>
                          <tr>
                              <td rowspan="2" style="vertical-align: middle;" class="text-center success"><?=$dados[$i]['id'];?></td>
                              <td colspan="8"><b><?=$dados[$i]['event_type'];?></b></td>
                            </tr>
                            <tr>
                              <td><small><i class="text-muted">Logradouro:</i></small><br><?=$dados[$i]['street_name'];?></td>
                              <td><small><i class="text-muted">Data de abertura:</i></small><br><?=$dt_abertura;?></td>
                              <td><small><i class="text-muted">Origem:</i></small><br><?=$dados[$i]['company_acron'];?></td>
                              <td><small><i class="text-muted">Status:</i></small><br><?=$dados[$i]['status'];?></td>

                              <td style="vertical-align: middle;" class="text-center"><?=$prov_adm;?></td>
                              <td style="vertical-align: middle;" class="text-center"><?=$prov_gui;?></td>
                              <td style="vertical-align: middle;" class="text-center"><?=$qtd_fotos;?></td>
                              <td style="vertical-align: middle;" class="text-center"><a href='oct/FORM.php?id=<?=$dados[$i]['id'];?>' class='mb-xs mt-xs mr-xs btn btn-default loading2'><i class='fa fa-pencil'></i></a></td>
                            </tr>
    <? }
}else {
  echo "<tr><td class='success'>&nbsp;</td>
            <td colspan='9' class='text-center'>
                <div class='alert alert-warning'>
                    Nenhuma ocorrênca aberta para esta data.<br><b>".$data_atual."</b>
                </div>
            </td>
        </tr>";
}

echo "<tr><td colspan='10' class='warning'>Ocorrências terminadas</td><tr>";
if(isset($dados_baixados) && count($dados_baixados))
{
      $dados = $dados_baixados;
      for($i = 0; $i < count($dados); $i++)
      {

        $dt_abertura = formataData($dados[$i]['date'],1);
        $dt_chegada  = formataData($dados[$i]['arrival'],1);
        $qtd_prov    = ($dados[$i]['qtd_providencias'] > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'>".$dados[$i]['qtd_providencias']."</span>":"-");
        $qtd_fotos   = ($dados[$i]['qtd_fotos']        > 0 ? "<span class='text-center mb-xs mt-xs mr-xs btn btn-default disabled'><b>".$dados[$i]['qtd_fotos']."</b> <sup><i class='fa fa-camera'></i></sup></span>":"-");

        if($providencias[$dados[$i]["id"]]["adm"]==1){  $prov_adm = "<span class='text-center mb-xs mt-xs mr-xs btn btn-success'>ADM</span>"; }
        else{                                           $prov_adm = "<span class='text-center text-muted'>-</span>";}
        if($providencias[$dados[$i]["id"]]["guin"]==1){ $prov_gui = "<span class='text-center mb-xs mt-xs mr-xs btn btn-info'>GUI</span>";    }
        else{                                           $prov_gui = "<span class='text-center text-muted'>-</span>";}


      ?>
      <tr>
          <td rowspan="2" style="vertical-align: middle;" class="text-center warning"><?=$dados[$i]['id'];?></td>
          <td colspan="8"><b><?=$dados[$i]['event_type'];?></b></td>
        </tr>
        <tr>
          <td><small><i class="text-muted">Logradouro:</i></small><br><?=$dados[$i]['street_name'];?></td>
          <td><small><i class="text-muted">Data de abertura:</i></small><br><?=$dt_abertura;?></td>
          <td><small><i class="text-muted">Origem:</i></small><br><?=$dados[$i]['company_acron'];?></td>
          <td><small><i class="text-muted">Status:</i></small><br><?=$dados[$i]['status'];?></td>

          <td style="vertical-align: middle;" class="text-center"><?=$prov_adm;?></td>
          <td style="vertical-align: middle;" class="text-center"><?=$prov_gui;?></td>
          <td style="vertical-align: middle;" class="text-center"><?=$qtd_fotos;?></td>
          <td style="vertical-align: middle;" class="text-center"><a href='oct/FORM.php?id=<?=$dados[$i]['id'];?>' class='mb-xs mt-xs mr-xs btn btn-default loading2'><i class='fa fa-pencil'></i></a></td>
        </tr>

      <? }
      }else{
      echo "<tr><td class='warning'>&nbsp;</td>
                <td colspan='9' class='text-center'>
                    <div class='alert alert-warning'>
                        Nenhuma ocorrênca fechada ou cadastrada para esta data.<br><b>".$data_atual."</b>
                    </div>
                </td>
            </tr>";
    }

      ?>


                      </table>





										</div>
									</div>
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
                        <form id="form_filtro" action="oct/index0.php" method="post">
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

</script>
