<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);
    $agora = now();

    /*
    echo "<div align='center'>";
      print_r_pre($_POST);
    echo "<br></br><a href='oct/FORM_providencias.php?id=".$id."'>Voltar</a>";
    echo "</div>";
    */


    if($acao == "inserir" && isset($_SESSION['id']))
    {

      $id_vehicle    = ($id_vehicle    != "" ? $id_vehicle              :"Null");
      $id_victim     = ($id_victim     != "" ? $id_victim               :"Null");
      $id_hospital   = ($id_hospital   != "" ? $id_hospital             :"Null");
      $id_company    = ($id_company    != "" ? $id_company              :"Null");

      if($data != "" && $hora != "" && strlen($hora)==5 && strlen($data)==10){
        $dtsrv = formataData($data,4);
        if(strlen($hora)==5)
        {
          $dtsrv .= " ".$hora.":00";
        }
      }else {
        $dtsrv = $agora['datatimesrv'];
      }


      $sql = "INSERT INTO sepud.oct_rel_events_providence
                          (id_owner,
                           id_vehicle,
                           id_victim,
                           id_hospital,
                           id_company_requested,
                           observation,
                           id_event,
                           id_providence,
                            opened_date)
             VALUES
	                      ( ".$_SESSION['id'].",
                          $id_vehicle,
                          $id_victim,
                          $id_hospital,
                          $id_company,
                          '".$description."',
                          '".$id."',
                          '".$id_providence."',
                          '".$dtsrv."');";
      pg_query($sql)or die("<div align='center'>Erro ".__LINE__."<br>".$sql."<br></br><a class='btn' href='oct/FORM_providencias.php?id=".$id."'>Voltar</a></div>");

      logger("Inserção","OCT - Providências", "Ocorrência n.".$id);

      if($retorno_acao == "continuar"){ header("Location: FORM_providencias.php?id=".$id); }
      else                            { header("Location: FORM.php?id=".$id);              }
      exit();
    }

    if($acao == "atualizar")
    {
         $sql = "UPDATE sepud.oct_vehicles SET
                             description   = '".$description."',
                             observation   = '".$observation."',
                             licence_plate = '".$licence_plate."',
                             color         = '".$color."',
                             renavam       = '".$renavam."',
                             chassi        = '".$chassi."'
                WHERE  id = '".$veic_sel."'";
       //pg_query($sql)or die("Erro ".__LINE__);
       //if($retorno_acao == "continuar"){ header("Location: FORM_veiculo.php?id=".$id); }
       //else                            { header("Location: FORM.php?id=".$id);         }
       exit();
    }


    extract($_GET);
    if($acao == "remover")
    {
      $sql  = "SELECT * FROM sepud.oct_rel_events_providence WHERE id = '".$id_providence."'";
      $res  = pg_query($sql)or die("Erro ".__LINE__);
      $d    = pg_fetch_assoc($res);
      $prov = print_r($d,true);
      $sql = "DELETE FROM sepud.oct_rel_events_providence WHERE id = '".$id_providence."'";
      pg_query($sql)or die("Erro ".__LINE__);
      logger("Remoção","OCT - Providências", "Ocorrência n.".$id.", Dados: ".$prov);
      header("Location: FORM_providencias.php?id=".$id);
      exit();
    }

?>
