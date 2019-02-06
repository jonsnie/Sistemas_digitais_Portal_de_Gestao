<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    $datafinal = $data." ".$hora;

    if($acao == "inserir" && $userid != "")
    {
      if(isset($_POST['condicoes']))
      {
        $condicoes = "'".json_encode(array("condicoes" => $_POST['condicoes']))."'";
      }else {
        $condicoes = "Null";
      }

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
                     event_conditions)
              VALUES ('".$datafinal."',
                      '".$description."',
                      '".$endereco."',
                      '".$endereco_complemento."',
                      '".$coordenadas."',
                      '".$tipo_oc."',
                      'Em deslocamento',
                      '".$victim_inform."',
                      '".$userid."',
                         $condicoes) returning id";

        $res = pg_query($sql) or die("Erro ".__LINE__);
        $aux = pg_fetch_assoc($res);
        header("Location: FORM.php?id=".$aux['id']);
        exit();
    }


    if($acao == "atualizar" && $userid != "" && $id != "")
    {
/*
      echo "<pre class='text-center'>";
        print_r($_POST);
      echo "</pre>";
*/
      if(isset($_POST['condicoes']))
      {
        $condicoes = "'".json_encode(array("condicoes" => $_POST['condicoes']))."'";
      }else {
        $condicoes = "Null";
      }

      echo "<div class='text-center'><hr>".$condicoes."<hr></div>";

        $sql = "UPDATE sepud.oct_events SET
                     date               = '".$datafinal."',
                     description        = '".$description."',
                     address_reference  = '".$endereco."',
                     address_complement = '".$endereco_complemento."',
                     geoposition        = '".$coordenadas."',
                     id_event_type      = '".$tipo_oc."',
                     status             = '".$status."',
                     victim_inform      = '".$victim_inform."',
                     event_conditions   =    $condicoes
               WHERE id                 = '".$id."'";

        pg_query($sql) or die("Erro ".__LINE__);
        header("Location: FORM.php?id=".$id);
        exit();
    }



if($_GET['status_acao'] == "atualizar" && $_GET['id'] != "")
{
    $agora = now();
    switch ($_GET['status_alterar']) {
      case 'd':
        $var_status = "Em deslocamento";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
      case 'a':
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', arrival = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      case 'e':
        $var_status = "Encaminhamento";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
      case 'f':
        $var_status = "Ocorrência terminada";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."', active = false, closure = '".$agora['datasrv']." ".$agora['hms']."' WHERE id = '".$_GET['id']."'";
        break;
      default:
        $var_status = "Em atendimento";
        $sqlU = "UPDATE sepud.oct_events SET status = '".$var_status."' WHERE id = '".$_GET['id']."'";
        break;
    }

     pg_query($sqlU)or die("<span class='text-center'>Erro ".__LINE__."<br>".$sqlU);
     header("Location: FORM.php?id=".$_GET['id']);
}
?>