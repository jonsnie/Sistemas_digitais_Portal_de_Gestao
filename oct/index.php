<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");



  logger("Acesso","OCT", "Sistema - página inicial");

  $agora = now();
  $sql   = "SELECT * FROM sepud.oct_workshift WHERE id_company = ".$_SESSION['id_company']." AND closed is null";
  $res   = pg_query($sql)or die("Erro ".__LINE__);

  if(pg_num_rows($res))
  {
        $turno = pg_fetch_assoc($res);
        $ano   = substr($turno['opened'],0,4);

        $sql = "SELECT
                    U.name as nome, U.id as id_user,
                    F.plate, F.model, F.brand,
                    WP.*
                  FROM
                            sepud.oct_rel_workshift_persona WP
                       JOIN sepud.users                      U ON U.id = WP.id_person
                  LEFT JOIN sepud.oct_fleet                  F ON F.id = WP.id_fleet
                  WHERE
                    WP.id_shift =  '".$turno['id']."'";
        $resRecursos = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
        while($d = pg_fetch_assoc($resRecursos))
        {
          //Veiculo, agrupa pela placa do veículo
          if($d['plate']!="")
          {
            $viaturasDados[$d['plate']]['modelo'] = $d['brand']." ".$d['model'];
            $viaturasDados[$d['plate']]['ocupantes'][] = array("uid" => $d['id_user'], "nome" => $d['nome'], "tipo" => $d['type'], "e_motorista" => $d['is_driver']);
          }

          if($d['type']=="Central de atendimento")
          {
            $centralDados[] = $d;
          }

          if($d['type']=="Coordenação" || $d['type']=="Direção")
          {
            $coordenacaoDados[] = $d;
          }

          /*
          (
          [nome] => Administrador Geral do Sistema
          [plate] => QHD7884
          [model] => Lander
          [brand] => Yamaha
          [id_shift] => 33
          [id_person] => 10
          [id_fleet] => 2
          [opened] => 2019-05-27 16:12:00
          [closed] =>
          [type] => Agente de campo
          [is_driver] => t
          */
        }
  }
?>

<section role="main" class="content-body">
  <header class="page-header">
    <h2>Registro de Ocorrências de Trânsito e Segurança</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Aplicações</span></li>
        <li><span class='text-muted'>Gestão do sistema</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>



  <div class="col-md-12">
        <section class="panel box_shadow">
            <header class="panel-heading" style="height:70px">
                <?=$_SESSION['company_acron']." - ".$_SESSION['company_name'];?><br>
                <span class="text-muted"><small><i>Data atual:</i></small> <b><?=$agora['data'];?></b></span>
                <div class="panel-actions"></div>
            </header>
            <div class="panel-body">

              <div class="row">
                <div class="col-sm-6">
                                  <?
                                      if(pg_num_rows($res))
                                      {
                                      ?>

                                      <section class='panel panel-horizontal'>
                             <header class='panel-heading bg-success' style='width:150px'>
                               <div class='panel-heading-icon'>
                                 <i class='fa fa-cogs'></i>
                               </div>
                             </header>

                             <div class='panel-body p-lg'>
                                    <table class='table table-condensed'>
                                      <thead>
                                          <tr>
                                            <th>Turno</th>
                                            <th>Data</th>
                                          </tr>
                                      </thead>

                                      <tbody>
                                              <tr>
                                                  <td><b><?=str_pad($turno['id'],5,"0",STR_PAD_LEFT);?></b></td>
                                                  <td><?="<b>".formataData($turno['opened'],1)."</b> (".$turno['period'].")";?></td>
                                              <tr>
                                              <tr><td colspan='2'><?=($turno['observation']==""?"<span class='text-muted'><small>Nenhuma observação.</small>":"<span class='text-muted'>Observações: </span>".$turno['observation']);?></td></tr>
                                      </tbody>
                                      <tfoot>
                                              <tr class="text-center">
                                                <td colspan="2" class="text-center">
                                                    <a href='oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar'><button id='bt_fechar_turno'    type='button' class='btn btn-sm btn-warning'>Fechar</button></a>
                                                    <a href='oct/turno.php?id=<?=$turno['id'];?>'><button id='bt_atualizar_turno' type='button' class='btn btn-sm btn-primary'>Atualizar</button></a>
                                                </td>
                                              </tr>
                                      </tfoot>
                                    </table>

                             </div>
                           </section>

                           <? }else{ ?>
                                    <section class='panel panel-horizontal'>
                                        <header class='panel-heading bg-default' style='width:150px'>
                                            <div class='panel-heading-icon'><i class='fa fa-cogs'></i></div>
                                        </header>
                                        <div class='panel-body p-lg text-center'>
                                            <a href='oct/turno.php'>
                                              <button type='button' class='mb-xs mt-xs mr-xs btn btn-primary'>Abrir novo turno de trabalho</button>
                                            </a>
                                        </div>
                                    </section>
                          <?  } ?>
                </div>

                <div class="col-sm-6">
                              <section class='panel panel-horizontal'>
                              <header class='panel-heading bg-info' style='width:150px'>
                              <div class='panel-heading-icon'>
                              <i class='fa fa-phone-square'></i>
                              </div>
                              </header>

                              <div class='panel-body p-lg'>

                                <table class='table table-condensed'>
                                <thead><tr><th>Central de atendimento</th></tr></thead>
                                <tbody>
                                  <tr>
                                    <td style='vertical-align: middle;'>
                                      <?
                                          if(isset($centralDados))
                                          {
                                            echo "<table class='table table-condensed'>";
                                            for($i=0;$i<count($centralDados);$i++)
                                            {
                                              echo "<tr><td>".$centralDados[$i]['nome']."</td></tr>";
                                            }
                                            echo "</table>";
                                          }else {
                                            echo "<small class='text-muted'>Nenhum recurso associado.</small>";
                                          }
                                      ?>
                                    </td>
                                  </tr>
                                </tbody>
                                </table>

                              </div>
                              </section>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                                <section class='panel panel-horizontal'>
                                <header class='panel-heading bg-info' style='width:150px'>
                                <div class='panel-heading-icon'>
                                <i class='fa fa-users'></i>
                                </div>
                                </header>

                                <div class='panel-body p-lg'>

                                      <table class='table table-condensed'>
                                      <thead><tr><th>Viaturas designadas e agentes de campo</th></tr></thead>
                                      <tbody>
                                        <tr>
                                          <td style="vertical-align: middle;">
                                            <?
                                                if(isset($viaturasDados))
                                                {
                                                    echo "<table class='table table-condensed'><tbody>";
                                                      foreach ($viaturasDados as $placa => $infos) {
                                                          echo "<tr class='warning'>";
                                                            echo "<td colspan='2'><b>".$placa." - ".$infos['modelo']."</b></td>";
                                                          echo "</tr>";
                                                          echo "<tr>";
                                                            for($i=0; $i<count($infos['ocupantes']);$i++)
                                                            {
                                                              echo "<tr>";
                                                                echo "<td>".$infos['ocupantes'][$i]['nome']."</td>";
                                                                echo "<td>".$infos['ocupantes'][$i]['e_motorista']."</td>";
                                                              echo "</tr>";
                                                            }
                                                          echo "</tr>";

                                                      }
                                                    echo "</tbody></table>";
                                                }else {
                                                  echo "<small class='text-muted'>Nenhum recurso associado.</small>";
                                                }
                                            ?>
                                          </td>
                                        </tr>
                                      </tbody>
                                      </table>

                                </div>
                                </section>
                </div>

                <div class="col-sm-6">
                                  <section class='panel panel-horizontal'>
                                  <header class='panel-heading bg-info' style='width:150px'>
                                  <div class='panel-heading-icon'>
                                  <i class='fa fa-user'></i>
                                  </div>
                                  </header>

                                  <div class='panel-body p-lg'>

                                        <table class='table table-condensed'>
                                        <thead><tr><th>Coordenação e direção</th></tr></thead>
                                        <tbody>
                                          <tr>
                                            <td style='vertical-align: middle;'>
                                              <?
                                                  if(isset($coordenacaoDados))
                                                  {
                                                    echo "<table class='table table-condensed'>";
                                                    for($i=0;$i<count($coordenacaoDados);$i++)
                                                    {
                                                      echo "<tr><td>".$coordenacaoDados[$i]['nome']."</td>";
                                                      echo "<td>".$coordenacaoDados[$i]['type']."</td></tr>";
                                                    }
                                                    echo "</table>";
                                                  }else {
                                                    echo "<small class='text-muted'>Nenhum recurso associado.</small>";
                                                  }
                                              ?>
                                            </td>
                                          </tr>
                                        </tbody>
                                        </table>

                                  </div>
                                  </section>
                </div>
              </div>





            </div><!--<div class="panel-body">-->
        </section><!--<section class="panel">-->
  </div><!--<div class="col-md-12">-->

</section>

<script>
$("#bt_fecsssshar_turno").click(function(){
  var url = "oct/turno_sql.php?id=<?=$turno['id'];?>&acao=fechar";
  $("#wrap").load(url);
});
</script>
