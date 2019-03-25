<?
  session_start();
  require_once("../libs/php/funcoes.php");
  require_once("../libs/php/conn.php");



$id_oc = $_GET["id_oc"];
$dir   = "uploads/".$id_oc;

logger("Inserção","OCT - imagens", "Ocorrência n.".$id_oc);

if(!file_exists($dir)){  mkdir($dir, 0777, true);}

if (isset($_FILES['files']) && !empty($_FILES['files'])) {
    $no_files = count($_FILES["files"]['name']);
    for($i = 0; $i < $no_files; $i++)
    {
        if($_FILES["files"]["error"][$i] > 0)
        {
            echo "Error: " . $_FILES["files"]["error"][$i] . "<br>";
        }else
        {
          /*
            if (file_exists('uploads/' . $_FILES["files"]["name"][$i])) {
                echo 'File already exists : uploads/' . $_FILES["files"]["name"][$i];
            } else {
                move_uploaded_file($_FILES["files"]["tmp_name"][$i], 'uploads/' . $_FILES["files"]["name"][$i]);
                echo 'File successfully uploaded : uploads/' . $_FILES["files"]["name"][$i] . ' ';
            }
          */
          $agora    = now();
          $aux      = explode(".",$_FILES["files"]["name"][$i]);
          $extensao = ".".end($aux);
/*
          $sqlS     = "SELECT currval('sepud.oct_rel_events_images_id_seq') AS seq";
          $res      = pg_query($sqlS)or die("Erro ".__LINE__);
          $seq      = pg_fetch_assoc($res);
*/
  //        $nome_arq = "oc_".str_pad($_GET['id_oc'],8,"0",STR_PAD_LEFT)."_".str_pad($seq['seq'],8,"0",STR_PAD_LEFT).$extensao;

          $sql = "INSERT INTO sepud.oct_rel_events_images(
						              id_events, image, path, timestamp)
                  VALUES (
                          ".$id_oc.",
	                       ('OC_' || lpad('".$id_oc."'::text, 8,'0') || '_' || lpad(currval('sepud.oct_rel_events_images_id_seq')::text,8,'0')  || '".$extensao."'),
	                        '".$dir."',
	                '".$agora['datatimesrv']."')RETURNING image";

          $res = pg_query($sql)or die("Erro ".__LINE__);
          $arq = pg_fetch_assoc($res);



          move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dir."/".$arq['image']);
          echo "<i class='fa fa-check text-success'></i> ".$arq['image']."<br>";

        }
    }
} else {
    echo 'Pelo menos um arquivo deve ser enviado';
}

?>
