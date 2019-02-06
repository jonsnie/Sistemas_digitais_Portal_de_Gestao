<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao == "inserir")
    {
      $sql = "INSERT INTO sepud.oct_vehicles
                          (description,
                           id_events,
                           observation,
                           licence_plate,
                           color)
                  VALUES ('".$description."',
                          '".$id."',
                          '".$observation."',
                          '".$licence_plate."',
                          '".$color."')";
      pg_query($sql)or die("Erro ".__LINE__);
      header("Location: FORM.php?id=".$id);
      exit();
    }

?>
