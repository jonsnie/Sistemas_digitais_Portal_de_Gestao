<?
    error_reporting(E_ALL);
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    $datafinal = $data." ".$hora;


    if($acao == "inserir" && $userid != "" && $_SESSION['id_company'])
    {

      if($id_street     == ""){ $id_street    ="Null"; }else{ $id_street     = "'".$id_street."'";     }
      if($street_number == ""){ $street_number="Null"; }else{ $street_number = "'".$street_number."'"; }
      if($id_workshift  == ""){ $id_workshift ="Null"; }else{ $id_workshift  = "'".$id_workshift."'";  }

        $sql = "INSERT INTO sepud.oct_events
                    (date,
                     description,
                     address_reference,
                     address_complement,
                     geoposition,
                     id_event_type,
                     status,
                     victim_inform,
                     id_user,
                     id_street,
                     street_number,
                     id_company,
                     id_workshift)
              VALUES ('".$datafinal."',
                      '".$description."',
                      '".$endereco."',
                      '".$endereco_complemento."',
                      '".$coordenadas."',
                      '".$tipo_oc."',
                      'Em deslocamento',
                      '".$victim_inform."',
                      '".$userid."',
                      ".$id_street.",
                      ".$street_number.",
                      '".$_SESSION['id_company']."',
                      ".$id_workshift.") returning id";

        $res = pg_query($sql) or die("Erro ".__LINE__."SQL: ".$sql);
        $aux = pg_fetch_assoc($res);

        logger("Inserção","OCT", "Ocorrência n.".$aux['id']);

        header("Location: FORM.php?id=".$aux['id']);
        exit();
    }


    if($acao == "atualizar" && $userid != "" && $id != "")
    {
        if(isset($_POST['condicoes']))
        {
          $sqlCond = "DELETE FROM sepud.oct_rel_events_event_conditions WHERE id_events = '".$id."';";
          for($i = 0;$i < count($_POST['condicoes']); $i++)
          {
              $sqlCond .= " INSERT INTO sepud.oct_rel_events_event_conditions (id_events, id_event_conditions) VALUES ('".$id."', '".$_POST['condicoes'][$i]."');";
          }
        }

        if($id_street == "")    { $id_street="Null";     }else{ $id_street     = "'".$id_street."'"; }
        if($street_number == ""){ $street_number="Null"; }else{ $street_number = "'".$street_number."'"; }

        $sql = "UPDATE sepud.oct_events SET
                     date               = '".$datafinal."',
                     description        = '".$description."',
                     address_reference  = '".$endereco."',
                     address_complement = '".$endereco_complemento."',
                     geoposition        = '".$coordenadas."',
                     id_event_type      = '".$tipo_oc."',
                     status             = '".$status."',
                     victim_inform      = '".$victim_inform."',
                     id_street          = ".$id_street.",
                     street_number      = ".$street_number."
               WHERE id                 = '".$id."'";

        pg_query($sqlCond.$sql) or die("Erro ".__LINE__);

        logger("Atualização","OCT", "Ocorrência n.".$id);

        header("Location: FORM.php?id=".$id);
        exit();
    }


if($_GET['status_acao'] == "atualizar" && $_GET['id'] != "")
{
    $agora = now();
    switch ($_GET['status_alterar']) {
      case 'd':
        $var_status = "Em deslocamento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
      case 'a':
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, status = '".$var_status."', arrival = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      case 'e':
        $var_status = "Encaminhamento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null,  status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
      case 'f':
        $var_status = "Ocorrência terminada";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      default:
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET active = true, closure = null, status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
    }

     pg_query($sqlU)or die("<span class='text-center'>Erro ".__LINE__."<br>".$sqlU);

     logger("Atualização de status","OCT", "Ocorrência n.".$_GET['id']." - ".$var_status);

     header("Location: FORM.php?id=".$_GET['id']);
}
?>
