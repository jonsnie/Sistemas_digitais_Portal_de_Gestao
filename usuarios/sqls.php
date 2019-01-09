<?
  session_start();
  require_once("../libs/php/conn.php");


if($_POST['acao'] == "remover")
{
  $sqlR = "DELETE FROM usuarios WHERE id = '".$_POST['id']."'";
  @mysql_query($sqlR);
}

if($_POST['acao'] == "inserir")
{
  extract($_POST);
  if( ($senha != $senha_repete) ||
       $senha == ""             ||
       $nome  == ""             || 
       $email == ""             || 
       $username == ""
    ){ 
      header("Location: ../usuarios/FORM_novo_usuario.php");
    }else
    {
        /*
        
          [tab] => dados
          [acao] => inserir
          [nome] => aa
          [sobrenome] => aaa
          [email] => aa
          [celular] => aa
          [telefone] => aa
          [cargo] => a
          [obs] => aaa
          [username] => aa
          [senha] => a
          [senha_repete] => a
          [e_superadmin] => sim
        
        echo "<pre class='text-center'>";
        print_r($_POST);
        echo "</pre>";
        exit();
        */
        $e_superadmin = ($e_superadmin == "sim"? "'1'" : "'0'");
        $sqlI = "INSERT INTO usuarios (nome, username, password, email, telefone, celular, sobrenome, cargo, obs, ativo, e_superuser) 
        VALUES ('".$nome."',  md5('".$username."'), md5('".$senha."'), '".$email."',
                '".$telefone."', '".$celular."', '".$sobrenome."', '".$cargo."', '".$obs."', 1, ".$e_superadmin.")";
        $res = mysql_query($sqlI)or die("<p class='text-center><br><br><br><br>".mysql_error()."</p>");
        $last_id = mysql_insert_id();
        header("Location: ../usuarios/FORM.php?id=".$last_id."&tab=".$_POST['tab']);
        exit();
  }
}

if($_POST['acao'] == "remover_foto")
{
  extract($_POST);
  echo "<pre class='text-center'>";
  echo $sql = "UDPDATE usuarios SET foto = null, foto_header = null WHERE id = '".$id."'";
  msql_query($sql)or die(mysql_error());
  echo "</pre>";
  exit();
  header("Location: ../usuarios/FORM.php?id=".$id);
}

if($_POST['acao'] == "atualizar")
{
  extract($_POST);
  if($_POST['username']!=""){ $usernameSQL = " , username = md5('".$_POST['username']."')"; }

  $sqlU = "UPDATE usuarios
           SET nome     = '$nome',     sobrenome = '$sobrenome', email = '$email',
               telefone = '$telefone', celular    = '$celular',  obs   = '$obs',
               cargo    = '$cargo'
               $usernameSQL
           WHERE id = '$id'";
  mysql_query($sqlU)or die(mysql_error());
  header("Location: ../usuarios/FORM.php?id=".$id."&tab=".$tab);
}





?>
