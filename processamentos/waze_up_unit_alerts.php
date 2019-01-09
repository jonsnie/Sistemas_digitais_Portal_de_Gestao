<?
require_once("../libs/php/conn.php");
require_once("../libs/php/funcoes.php");
$agora = now();

system("clear");
echo "\n#==========================================#";
echo "\n|   Atualiza campos databela alerts_unit   |";
echo "\n#==========================================#";

echo "\n\n> Init proc:";
//echo "\n> Reference date: ".$agora['mes']."/".$agora['ano'];

//////////////////////////////////////////////////////////////////////
// GET ROTERIZADOR DATAS
for($i = 0; $i < 1000;$i++)
{
unset($sql,$c,$uuid,$d,$dados,$sqlU,$uuidv,$uuid_arr);
$c = 0;

echo "\n> Getting waze data:    ";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql = "SELECT * FROM waze.alerts_unit WHERE pub_utc_date is null LIMIT 500";
$res   =  pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
echo "\n> Executando passo ".$i."\n";
echo "> montando vetor: ";
while($d = pg_fetch_assoc($res)){
  $dados[] = $d;
  $uuid_arr[] = "'".$d['uuid']."'";
}
echo count($uuid_arr)." regs encontrados.\n";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$uuid = implode(",",$uuid_arr);
echo "> selecionando dados: ";
$sqlG = "SELECT distinct(uuid), pub_utc_date FROM waze.alerts WHERE uuid in (".$uuid.")";
$resG = pg_query($conn_neogrid,$sqlG)or die("Erro ".__LINE__);
echo pg_num_rows($resG)." regs. encontrados.\n";
echo "> limpando chaves duplicadas no vetor: ";
while($d = pg_fetch_assoc($resG)){  $uuidv[$d['uuid']] = $d['pub_utc_date'];}
echo count($uuidv)." regs no vetor.";
echo "\n> Executando atualizacao:\n";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
foreach($uuidv as $uid => $data)
{
  $sqlU = "UPDATE waze.alerts_unit SET pub_utc_date = '".$data."' WHERE uuid = '".$uid."'";
  pg_query($conn_neogrid, $sqlU) or die("Erro ".__LINE__);
  echo $c++;
  echo "\r";
}
/*
$sqlU = "UPDATE waze.alerts_unit SET pub_utc_date = '".$d['pub_utc_date']."' WHERE uuid = '".$d['uuid']."'";
pg_query($conn_neogrid, $sqlU) or die("Erro ".__LINE__);
echo $c++;
echo "\r";
*/
/*
  $sqlG = "SELECT pub_utc_date FROM waze.alerts WHERE uuid = '".$d['uuid']."' LIMIT 1";
  $resG = pg_query($conn_neogrid,$sqlG) or die("Erro ".__LINE__);
  $aux  = pg_fetch_assoc($resG);
  $sqlU = "UPDATE waze.alerts_unit SET pub_utc_date = '".$aux['pub_utc_date']."' WHERE uuid = '".$d['uuid']."'";
          pg_query($conn_neogrid, $sqlU) or die("Erro ".__LINE__);;
  echo ".";
 */

/*
  $sqlU = "	SELECT
          			min(A.datafile_id) as init_data_file,
          			max(A.datafile_id) as end_data_file,
          			(SELECT to_timestamp(start_time_millis/1000) FROM waze.data_files WHERE id = min(A.datafile_id)) as time_start,
          			(SELECT to_timestamp(start_time_millis/1000) FROM waze.data_files WHERE id = max(A.datafile_id)) as time_end,
          			((SELECT start_time_millis FROM waze.data_files WHERE id = max(A.datafile_id)) -
          			(SELECT  start_time_millis FROM waze.data_files WHERE id = min(A.datafile_id)))/1000 as alive_time
          		FROM waze.alerts A
          		WHERE A.uuid = '".$d['uuid']."'";
  $res2 = pg_query($conn_neogrid,$sqlU);
  if(pg_num_rows($res2))
  {
    $extrainfo = pg_fetch_assoc($res2);
    $extrainfo['uuid'] = $d['uuid'];
    print_r($extrainfo);
  }
*/


//print_r($dados);
}
echo "\ndone.\n\n";
exit();
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

  //$res=pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
  while($d = pg_fetch_assoc($res))
  {
  $infos['roterizador']['eventos'][$d['description']] = $d['qtd'];
  }
echo "done.";
////////////////////////////////////////////////////////////////////////////
// GET RADARS  RESUME

echo "\n> Getting radars equipments:         ";

$sql = "SELECT count(*)as qtd FROM radars.equipments";
//$res = pg_query($conn_neogrid,$sql);
$RadarQTD = pg_fetch_assoc($res);
$infos['radares']['resumo']['equipamentos']=$RadarQTD['qtd'];

echo "done.";

echo "\n> Getting radars imported files:     ";

$sql = "SELECT count(*) as qtd FROM radars.equipment_files WHERE pubdate BETWEEN '".$agora['ano']."-".$agora['mes']."-01' AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']."'";
//$res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
$RadarQTDfiles = pg_fetch_assoc($res);
$infos['radares']['resumo']['registros']=$RadarQTDfiles['qtd'];

echo "done.";

/////////////////////////////////////////////////////////////////////////////
// GET WAZE DATA

echo "\n> Getting waze alerts:               ";
$sql = "SELECT count(*) as qtd FROM waze.alerts
         WHERE pub_utc_date BETWEEN '".$agora['ano']."-".$agora['mes']."-01 00:00:00'
                                AND '".$agora['ano']."-".$agora['mes']."-".$agora['ultimo_dia']." 23:59:59'";

 //$res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
 $wazeQTDalerts = pg_fetch_assoc($res);
 $infos['waze']['resumo']['alertas'] = $wazeQTDalerts['qtd'];

 echo "done.";

 echo "\n> Getting waze jams:                 ";

 $sql = "SELECT count(*) as qtd FROM waze.jams WHERE pub_utc_date BETWEEN '2018-11-01 00:00:00' AND '2018-11-30 23:59:59'";
 //$res = pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
 $wazeQTDjams = pg_fetch_assoc($res);
 $infos['waze']['resumo']['congestionamentos'] = $wazeQTDjams['qtd'];

 echo "done.";
//////
echo "\n--------------------------------";
echo "\n> Prepering insertions";
echo "\n> Removing old data:                 ";
$sql = "DELETE FROM sepud.resume WHERE ref_month = '".$agora['mes']."' AND ref_year = '".$agora['ano']."'";
//pg_query($conn_neogrid,$sql) or die("\n\nERROR SQL EXECUTION, line: ".__line__."\n\n");
echo "done.";

echo "\n> Insert new data:                   ";
foreach($infos as $modulo => $tipo)
{
  if(isset($tipo['resumo'])){
    foreach($tipo['resumo'] as $dado => $val)
    {
      unset($sql);
      $sql = "INSERT INTO sepud.resume (field, int_value, type, module, ref_month, ref_year)VALUES('".$dado."','".$val."','resumo','".$modulo."','".$agora['mes']."', '".$agora['ano']."')";
      //pg_query($conn_neogrid,$sql);
    }
  }
  if(isset($tipo['eventos'])){
    foreach($tipo['eventos'] as $dado => $val)
    {
      unset($sql);
      $sql = "INSERT INTO sepud.resume (field, int_value, type, module, ref_month, ref_year)VALUES('".$dado."','".$val."','evento','".$modulo."','".$agora['mes']."', '".$agora['ano']."')";
      //pg_query($conn_neogrid,$sql);
    }
  }
}
echo "done.";
echo "\n> End proc.\n\n";
//print_r($infos);
?>
