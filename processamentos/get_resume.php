<?
require_once("../libs/php/conn.php");
require_once("../libs/php/funcoes.php");
$agora = now();

system("clear");
echo "\n#==========================================#";
echo "\n|    GET DATA AND IMPORT TO RESUME TABLE   |";
echo "\n|    Modules: Roterizador, radares, waze   |";
echo "\n#==========================================#";

echo "\n\n> Init proc:";
echo "\n> Reference date: ".$agora['mes']."/".$agora['ano'];

//////////////////////////////////////////////////////////////////////
// GET ROTERIZADOR DATAS

echo "\n> Getting roterizador equipments:    ";
$sql = "SELECT
         ( SELECT COUNT ( * ) FROM sepud.rot_equipments ) AS qtd_equipamentos,
         ( SELECT COUNT ( * ) FROM sepud.rot_data_sensors
            WHERE datetime BETWEEN '".$agora['ano']."-".$agora['mes']."-01'
                               AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']."') AS qtd_leituras_sensores";

$res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
$DadosRoterizador=pg_fetch_assoc($res);
  $infos['roterizador']['resumo']['equipamentos']=$DadosRoterizador['qtd_equipamentos'];
  $infos['roterizador']['resumo']['eventos']=$DadosRoterizador['qtd_leituras_sensores'];

echo "done.";

//////////////////////////////////////////////////////////////////////
// ROTERIZADOR EVENTS

echo "\n> Getting roterizador events:        ";
$sql = "SELECT
           COUNT( * ) AS qtd,	description
         FROM
           sepud.rot_data_sensors
         WHERE
           datetime BETWEEN '".$agora['ano']."-".$agora['mes']."-01' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']."'
         GROUP BY
           description
         ORDER BY
           description ASC;";

  $res=pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
  while($d = pg_fetch_assoc($res))
  {
  $infos['roterizador']['eventos'][$d['description']] = $d['qtd'];
  }
echo "done.";
////////////////////////////////////////////////////////////////////////////
// GET RADARS  RESUME

echo "\n> Getting radars equipments:         ";

$sql = "SELECT count(*)as qtd FROM radars.equipments";
$res = pg_query($conn_neogrid,$sql);
$RadarQTD = pg_fetch_assoc($res);
$infos['radares']['resumo']['equipamentos']=$RadarQTD['qtd'];

echo "done.";

echo "\n> Getting radars imported files:     ";

$sql = "SELECT count(*) as qtd FROM radars.equipment_files WHERE pubdate BETWEEN '".$agora['ano']."-".$agora['mes']."-01' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']."'";
$res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
$RadarQTDfiles = pg_fetch_assoc($res);
$infos['radares']['resumo']['registros']=$RadarQTDfiles['qtd'];

echo "done.";

/////////////////////////////////////////////////////////////////////////////
// GET WAZE DATA

echo "\n> Getting waze alerts:               ";
$sql = "SELECT count(*) as qtd FROM waze.alerts
         WHERE pub_utc_date BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00'
                                AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59'";

 $res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
 $wazeQTDalerts = pg_fetch_assoc($res);
 $infos['waze']['resumo']['alertas'] = $wazeQTDalerts['qtd'];

 echo "done.";

 echo "\n> Getting waze jams:                 ";

 $sql = "SELECT count(*) as qtd FROM waze.jams WHERE pub_utc_date BETWEEN '2018-11-01 00:00:00' AND '2018-11-30 23:59:59'";
 $res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
 $wazeQTDjams = pg_fetch_assoc($res);
 $infos['waze']['resumo']['congestionamentos'] = $wazeQTDjams['qtd'];

 echo "done.";
//////
echo "\n--------------------------------";
echo "\n> Prepering insertions";
echo "\n> Removing old data:                 ";
$sql = "DELETE FROM sepud.resume WHERE ref_month = '".$agora['mes']."' AND ref_year = '".$agora['ano']."'";
pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
echo "done.";

echo "\n> Insert new data:                   ";
foreach($infos as $modulo => $tipo)
{
  if(isset($tipo['resumo'])){
    foreach($tipo['resumo'] as $dado => $val)
    {
      unset($sql);
      $sql = "INSERT INTO sepud.resume (field, int_value, type, module, ref_month, ref_year)VALUES('".$dado."','".$val."','resumo','".$modulo."','".$agora['mes']."', '".$agora['ano']."')";
      pg_query($conn_neogrid,$sql);
    }
  }
  if(isset($tipo['eventos'])){
    foreach($tipo['eventos'] as $dado => $val)
    {
      unset($sql);
      $sql = "INSERT INTO sepud.resume (field, int_value, type, module, ref_month, ref_year)VALUES('".$dado."','".$val."','evento','".$modulo."','".$agora['mes']."', '".$agora['ano']."')";
      pg_query($conn_neogrid,$sql);
    }
  }
}
echo "done.";
echo "\n> End proc.\n\n";
//print_r($infos);
?>
