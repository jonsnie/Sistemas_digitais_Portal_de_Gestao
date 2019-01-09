<?php
//////////////////////////////////////////////////////////////////////////////
if(getenv("DB_HOST")==""){ setenvs(); }
function setenvs()
{
  $basedir = $_SERVER['DOCUMENT_ROOT'];
  $envfile = ".env";
  if(file_exists($basedir."/".$envfile))
  {
    $arq = file($basedir."/".$envfile);
    for($i=0;$i<count($arq);$i++)
    {
      putenv($arq[$i]);
    }
  }
}
//////////////////////////////////////////////////////////////////////////////
function print_r_pre($arr){ echo "<pre>"; print_r($arr); echo "</pre>";}
//////////////////////////////////////////////////////////////////////////////
function array2utf8(&$arr)
	{
		foreach($arr as $chave => $valor)
		{
			  if(!mb_check_encoding($valor, 'UTF-8'))
			  {
				  $arr[$chave] = utf8_encode($valor);
			  }
		}
	}
//////////////////////////////////////////////////////////////////////////////
function now($ret = "todos"){

	$mkt                = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('y'));
	$data['dia']        = date('d',$mkt);
	$data['mes']        = date('m',$mkt);
	$data['ano']        = date('Y',$mkt);
	$data['hora']       = date('H',$mkt);
	$data['min']        = date('i',$mkt);
	$data['seg']        = date('s',$mkt);
	$data['data']       = $data['dia'].'/'.$data['mes'].'/'.$data['ano'];
	$data['datasrv']    = $data['ano'].'-'.$data['mes'].'-'.$data['dia'];
	$data['hm']         = $data['hora'].':'.$data['min'];
	$data['hms']        = $data['hora'].':'.$data['min'].':'.$data['seg'];
	$data['dthm']       = $data['data']." ".$data['hm'];
	$data['dthms']      = $data['data']." ".$data['hm'].":".$data['seg'];
	$data['mkt']        = $mkt;
	$data['ultimo_dia'] = date('t',$mkt);

	switch($data['mes'])
	{
		case '01': $data['mes_txt_c'] = "Jan"; $data['mes_txt'] = "Janeiro";   break;
		case '02': $data['mes_txt_c'] = "Fev"; $data['mes_txt'] = "Fevereiro"; break;
		case '03': $data['mes_txt_c'] = "Mar"; $data['mes_txt'] = "Março";     break;
		case '04': $data['mes_txt_c'] = "Abr"; $data['mes_txt'] = "Abril";     break;
		case '05': $data['mes_txt_c'] = "Mai"; $data['mes_txt'] = "Maio";      break;
		case '06': $data['mes_txt_c'] = "Jun"; $data['mes_txt'] = "Junho";     break;
		case '07': $data['mes_txt_c'] = "Jul"; $data['mes_txt'] = "Julho";     break;
		case '08': $data['mes_txt_c'] = "Ago"; $data['mes_txt'] = "Agosto";    break;
		case '09': $data['mes_txt_c'] = "Set"; $data['mes_txt'] = "Setembro";  break;
		case '10': $data['mes_txt_c'] = "Out"; $data['mes_txt'] = "Outubro";   break;
		case '11': $data['mes_txt_c'] = "Nov"; $data['mes_txt'] = "Novembro";  break;
		case '12': $data['mes_txt_c'] = "Dez"; $data['mes_txt'] = "Dezembro";  break;
	}

if($ret == "todos"){ return $data;      }
else{				 return $data[$ret];}

}
//////////////////////////////////////////////////////////////////////////////
function formataData($data,$modelo)
{
$tmp = explode(" ",$data);

if($data != "" && $modelo != 4){
	$aux = explode("-",$tmp[0]);
	if($modelo == 1){ $data =  $aux[2]."/".$aux[1]."/".$aux[0]; }
	if($modelo == 2){ $data =  $aux[2]."-".$aux[1]."-".$aux[0]; }
	if($modelo == 3){ $data =  $aux[2].$aux[1].$aux[0]; }
}
if($data != "" && $modelo == 4){
	 // 0  1   2
	// DD/MM/YYYY
	$aux = explode("/",$tmp[0]);
 	$data =  $aux[2]."-".$aux[1]."-".$aux[0];
}
if($aux[1] != ""){  return $data." ".$tmp[1]; }
			 else{	return $data; 			  }

}
?>
