<?
  header("Content-Type: text/plain");
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  $id = $_GET['id'];
  $sql   = "SELECT
              P.id    as parking_id,
              P.name  as parking_code,
              PT.type as parking_type,
              PT.time as parking_time,
              PT.time_warning as parking_time_warning,
              U.NAME,
              U.id_company,
              C.NAME AS company_name,
              C.acron AS company_acron,
              S.name as street_name,
              SP.*
            FROM
              sepud.eri_schedule_parking SP
              JOIN sepud.users U ON U.ID = SP.id_user
              JOIN sepud.company C ON C.ID = U.id_company
              JOIN sepud.eri_parking P ON P.id = SP.id_parking
              JOIN sepud.eri_parking_type PT ON PT.id = P.id_parking_type
              JOIN sepud.streets S ON S.id = P.id_street
              WHERE
                SP.id = '".$id."'";
  $res   = pg_query($conn_neogrid,$sql)or die("Error ".__LINE__);
  $dados = pg_fetch_assoc($res);

  logger("Acesso","SERP - Impressão","ID: ".$dados['id'].", VAGA: ".$dados['parking_code'].", PLACA: ".$dados['licence_plate']);

/*
<style type='text/css'>
body {
   color:#000000;
   background-color:#ffffff;
   font-family:courier, courier new, serif; }
</style>
*/
/*
  $data_entrada = explode(" ",formataData($dados['timestamp'],1));

  echo "&nbsp;&nbsp;&nbsp;Prefeitura de Joinville"; echo "<br>";
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DETRANS"; echo "<br>";
  echo "&nbsp;&nbsp;SISTEMA DE ESTACIONAMENTO"; echo "<br>";
  echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ROTATIVO PÚBLICO       "; echo "<br>";
  echo "_______________________________"; echo "<br><br>";
  echo "&nbsp;&nbsp;RECIBO DE OCUPAÇÃO DE VAGA"; echo "<br><br>";
  echo "&nbsp;&nbsp;&nbsp;VAGA: ".$dados['parking_code']." (".$dados['parking_time']."min)"; echo "<br>";
  echo "&nbsp;&nbsp;&nbsp;&nbsp;RUA: ".$dados['street_name']; echo "<br>";
  echo "&nbsp;&nbsp;PLACA: ".$dados['licence_plate']; echo "<br>";
  echo "ENTRADA: ".$data_entrada[0]; echo "<br>";
  echo "&nbsp;&nbsp;&nbsp;HORA: ".$data_entrada[1]; echo "<br>";

  if($dados['closed_timestamp']  !=""){
      $data_baixado = explode(" ",formataData($dados['closed_timestamp'],1));
      echo "BAIXADO: ".$data_baixado[0]; echo "<br>";
      echo "&nbsp;&nbsp;&nbsp;HORA: ".$data_baixado[1]; echo "<br>";
  }
  if($dados['notified_timestamp']!=""){
    $data_notif = explode(" ",formataData($dados['notified_timestamp'],1));
    echo "_______________________________";
    echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VEICULO NOTIFICADO<br>";
    echo "DATA: ".$data_notif[0]; echo "<br>";
    echo "HORA: ".$data_notif[1]; echo "<br>";
  }

  echo "_______________________________"; echo "<br>";
  echo "&nbsp;&nbsp;www.joinville.sc.gov.br"
  */

  $data_entrada = explode(" ",formataData($dados['timestamp'],1));

  echo " Prefeitura de Joinville       "; echo "\n";
  echo "        DETRANS                "; echo "\n";
  echo "SISTEMA DE ESTACIONAMENTO      "; echo "\n";
  echo "     ROTATIVO PÚBLICO          "; echo "\n";
  echo "_______________________________"; echo "\n\n";
  echo "RECIBO DE OCUPAÇÃO DE VAGA     "; echo "\n\n";
  echo "VAGA:    ".$dados['parking_code']." (".$dados['parking_time']."min)"; echo "\n";
  echo "RUA:     ".$dados['street_name'];   echo "\n";
  echo "PLACA:   ".$dados['licence_plate']; echo "\n";
  echo "ENTRADA: ".$data_entrada[0];        echo "\n";
  echo "HORA:    ".$data_entrada[1];        echo "\n";

  if($dados['closed_timestamp']  !=""){
      $data_baixado = explode(" ",formataData($dados['closed_timestamp'],1));
      echo "BAIXADO: ".$data_baixado[0]; echo "\n";
      echo "HORA:    ".$data_baixado[1]; echo "\n";
  }
  if($dados['notified_timestamp']!=""){
    $data_notif = explode(" ",formataData($dados['notified_timestamp'],1));
    echo "_______________________________";
    echo "\nVEICULO NOTIFICADO\n";
    echo "DATA: ".$data_notif[0]; echo "\n";
    echo "HORA: ".$data_notif[1]; echo "\n";
  }

  echo "_______________________________"; echo "\n";
  echo "www.joinville.sc.gov.br"

/*
<script>
//window.print();
//window.close();
</script>
*/
?>
