<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");

  //exit();

  $ruas = array(1017, 2379, 2247, 2855, 997, 3047, 1001, 3646, 2600, 1963, 3651, 3064, 3264, 2758, 2377, 4, 706);
  //print_r($ruas);

  //exit();

  // cria vagas
  /*
  for($i = 1; $i <= 1000; $i++)
  {

      echo $sql = "INSERT INTO sepud.eri_parking (id, name, active, id_street, id_parking_type)
              values ($i, '".str_pad($i,4,"0",STR_PAD_LEFT)."',
                      True,
                      '".$ruas[rand(0,16)]."',
                    '".rand(10,15)."')";

                    echo "---".rand(10,15)."---";


      pg_query($sql)or die("<br>Erro ID: ".$i);

      echo ", ok !<br>";
  }
*/
//cria simulação para alocacao de vagas

for($i = 0; $i<=500;$i++)
{
      $id_user    = 1;
      $id_parking = rand(1,1000);
      $placa      = chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

      echo "<br>".str_pad($i,4,"0",STR_PAD_LEFT)." - ";
      echo "Vaga: ".$id_parking.", placa: ".$placa;

              $agora     = now();
              //$timestamp = $agora['datatimesrv'];

              $timestamp = $agora['ano']."-".$agora['mes']."-".$agora['dia']." ".rand(6,$agora['hora']).":".$agora['min'].":".$agora['seg'];

      echo " - ".$timestamp;
              //checagem se a vaga não esta ocupada//
              //if($multi_parking == "false") //Se a vaga não permite multiplos veiculos, baixa o anterior (caso exista)
              //{
                  $sql = "UPDATE sepud.eri_schedule_parking
                          SET closed = true, closed_timestamp = '".$agora['datatimesrv']."'
                          WHERE id_parking = '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
                  pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
            //  }

              //Se a placa estiver ativa em outra vaga, também realiza a baixa
              $sql = "UPDATE sepud.eri_schedule_parking
                      SET closed = true, closed_timestamp = '".$agora['datatimesrv']."'
                      WHERE licence_plate = '".$placa."' AND id_parking != '".$id_parking."' AND closed_timestamp is null AND timestamp >= '".$agora['datasrv']." 00:00:00'";
              pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);


              $sql = "INSERT INTO sepud.eri_schedule_parking
                                (id_parking,
                                 timestamp,
                                 id_user,
                                 licence_plate)
                     VALUES
                               (".$id_parking.",
                                '".$timestamp."',
                                ".$id_user.",
                                '".$placa."')";
              pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

              echo ", ok!";
 }


?>
