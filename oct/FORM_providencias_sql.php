<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

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



      $sql = "INSERT INTO sepud.oct_rel_events_providence
                          (id_owner,
                           id_vehicle,
                           id_victim,
                           id_hospital,
                           id_company_requested,
                           observation,
                           id_event,
                           id_providence)
             VALUES
	                      ( ".$_SESSION['id'].",
                          $id_vehicle,
                          $id_victim,
                          $id_hospital,
                          $id_company,
                          '".$description."',
                          '".$id."',
                          '".$id_providence."');";
      pg_query($sql)or die("<div align='center'>Erro ".__LINE__."<br>".$sql."<br></br><a class='btn' href='oct/FORM_providencias.php?id=".$id."'>Voltar</a></div>");

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
      echo "Ação: REMOVER";
      //$sql = "DELETE FROM sepud.oct_vehicles WHERE id = '".$veic_sel."'";
      //pg_query($sql)or die("Erro ".__LINE__);
      //header("Location: FORM_veiculo.php?id=".$id);
      exit();
    }

?>
