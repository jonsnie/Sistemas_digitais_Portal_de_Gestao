<?
    session_start();
    require_once("../libs/php/funcoes.php");
    require_once("../libs/php/conn.php");

    extract($_POST);

    if($acao=="inserir" && trim($email) != "" && trim($senha) != "" && ($senha == $senha_repete))
    {

/*

      echo "<div class='text-center'>";
        print_r_pre($_POST);
      echo "</div>";



    [name] => Anabela da Jesus
    [phone] => 4791876457
    [id_company] => 6
    [area] => Administração
    [job] => Gerente de triagem
    [obs] => observacoes
    [username] => ana@gmail.com.br
    [senha] => 123
    [senha_repete] => 123
    [acao] => inserir
*/
      $sql =  "INSERT INTO sepud.users(
                      name,
                      email,
                      password,
                      area,
                      job,
                      active,
                      id_company,
                      observation,
                      phone,
                      in_ativaction)
              VALUES (
                     '".$name."',
                     '".$email."',
                     MD5('".$senha."'),
                    '".$area."',
                    '".$job."',
                    't',
                    '".$id_company."',
                    '".$observation."',
                    '".$phone."',
                    'f') RETURNING id";
       $res = pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);
       $aux = pg_fetch_assoc($res);

       logger("Inserção","Usuário", "Inseriu o usuário: [".$aux["id"]."] - ".$name);

       header("Location: FORM.php?id=".$aux['id']);
       exit();
    }


    if($acao=="atualizar" && trim($email) != "")
    {
        if($senha != ""){ $senha =   "password    = MD5('".$senha."'),"; }

        $sql =  "UPDATE sepud.users SET
                        name        = '".$name."',
                        email       = '".$email."',
                        ".$senha."
                        area        = '".$area."',
                        job         = '".$job."',
                        id_company  = '".$id_company."',
                        phone       = '".$phone."',
                        observation = '".$observation."',
                        active      = '".$active."'
                  WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__."<br>SQL: ".$sql);

      logger("Atualização","Usuário","Atualizou dados do usuário: [".$id."] - ".$name);

      header("Location: FORM.php?id=".$id);
      exit();
  }

    extract($_GET);

    if($acao == "remover" && $id != "")
    {
      $sql = "UPDATE sepud.users SET active = false WHERE id = '".$id."'";
      pg_query($sql)or die("Erro ".__LINE__);
      logger("Baixa","Usuário","Baixou o usuário: [".$id."]");
      header("Location: index.php");
      exit();
    }

    header("Location: FORM_novo_usuario.php");
    exit();
?>
