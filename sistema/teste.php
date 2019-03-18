<?
error_reporting(E_ALL);
session_start();
//header("Content-Type: text/html; charset=ISO-8859-1",true);
require_once("../libs/php/funcoes.php");
?>

<section role="main" class="content-body">

  <header class="page-header">
    <h2>Página para testes de scripts</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span>Configurações</span></li>
        <li><span>Desenvolvimento</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>


  <div class="row">
        <div class="col-md-12">


          <?

print_r_pre($_SESSION);
//echo "<hr>Variaveis de ambiente:<br>";
//print_r_pre($_SERVER);
//setenvs();
//putenv("DB_HOST=xyzaaaaa");
//echo "<hr>Variavel de ambiente setada: ";
//echo getenv("DB_HOST")."<br>";
//echo getenv("DB_PORT")."<br>";
//echo getenv("DB_NAME")."<br>";
echo "<hr>";


/*
          $post_data['login'] = getenv("RADAR_USER");
          $post_data['senha'] = getenv("RADAR_PASS");


          foreach ( $post_data as $key => $value) {
              $post_items[] = $key . '=' . $value;
          }

          $post_string = implode ('&', $post_items);

          $curl_connection = curl_init();

          $url = getenv("RADAR_URL");
          curl_setopt($curl_connection, CURLOPT_URL, $url);
          curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
          curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
          curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);

          curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, true);
          curl_setopt($curl_connection, CURLOPT_COOKIESESSION, true);



          curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

          //perform our request
          $result  = curl_exec($curl_connection);


          //show information regarding the request
          echo "<hr>";
          echo "<pre>".$post_string."<br>Retorno CURL:<br>".$result."<br>-- FIM --"."</pre>";
          echo "<hr>";
          echo "<pre>RESULTADO:<br>";
          print_r(curl_getinfo($curl_connection));
          echo "</pre>";
          echo curl_errno($curl_connection).'-'.curl_error($curl_connection);



exit();
          echo "<hr>";
          unset($post_data, $post_items, $post_string);

         $post_data = array("equipamento" => "FS551JOI",
                                "dataStr" => "18/11/2018",
                             "horaInicio" => "00",
                                "horaFim" => "23",
                                  "opcao" => 'excel',
                                 "exibir" => "on");

          foreach ($post_data as $key => $value) {  $post_items[] = $key . '=' . $value;     }

          $post_string = implode ('&', $post_items);

        echo "<pre>";
        echo $url = 'http://monitran.com.br/joinville/relatorios/fluxoVelocidadePorMinuto/gerar?'.$post_string;
        echo "</pre>";

        $curl_connection2 = curl_init();
        curl_setopt($curl_connection2, CURLOPT_URL, $url);
        curl_setopt($curl_connection2, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl_connection2, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        curl_setopt($curl_connection2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection2, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection2, CURLOPT_FOLLOWLOCATION, 1);
        $result2 = curl_exec($curl_connection2);
        echo "<pre><br>Retorno CURL:<br>".$result2."<br>-- FIM --"."</pre>";
        echo "<pre>RESULTADO:<br>";
        print_r(curl_getinfo($curl_connection2));
        echo "</pre>";
        echo curl_errno($curl_connection2).'-'.curl_error($curl_connection2);





        curl_close($curl_connection);

*/

        ?>
        </div>
  </div>
</section>
