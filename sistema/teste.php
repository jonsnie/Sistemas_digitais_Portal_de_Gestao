<?
session_start();
require_once("../libs/php/funcoes.php");
require_once("../libs/php/conn.php");
?>
<style>
.select2-selection__rendered {
line-height: 32px !important;
}

.select2-selection {
height: 34px !important;
}
</style>
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


  <div class="row">
    <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="tipo_oc">Ocorrência:</label>

                    <?

                      if(isset($_SESSION["company_id"]))
                      {
                          $sql = "SELECT T.* FROM sepud.oct_event_type T
                                  JOIN sepud.oct_rel_event_type_company R ON R.id_event_type = T.id AND R.id_company = '".$_SESSION["company_id"]."'
                                  WHERE T.active = true
                                  ORDER BY T.name ASC";
                      }else {
                          $sql = "SELECT * FROM sepud.oct_event_type WHERE active = true ORDER BY name ASC";
                     }

                      $res = pg_query($conn_neogrid,$sql)or die("Erro ".__LINE__."<br>".$sql);
                      while($d = pg_fetch_assoc($res))
                      {
                          $vet[$d['type']][] = $d;

                        //if($dados['id_event_type'] == $d['id']){ $sel = "selected"; }else{ $sel = ""; }
                        //echo "<option value='".$d['id']."' $sel>".$d['name']."</option>";
                      }
                      ?>
                      <select id="tipo_oc" name="tipo_oc" class="form-control changefield select2">
                      <?
                      foreach($vet as $type => $d)
                      {
                        echo "<optgroup label='".$type."'>";
                          for($i = 0; $i < count($d); $i++)
                          {
                            if($d[$i]['name_acron'] != ""){ $acron = $d[$i]['name_acron']." - ";}else{$acron = "";}
                            if($dados['id_event_type'] == $d[$i]['id']){ $sel = "selected"; }else{ $sel = ""; }
                            echo "<option value='".$d[$i]['id']."' $sel>[".$d[$i]['id']."] ".$acron.$d[$i]['name']."</option>";
                          }
                        echo "</optgroup>";
                      }
                    ?>
                  </select>
          </div>
    </div>

        <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="victim_inform">Vítima(s) info.:</label>
                      <select id="victim_inform" name="victim_inform" class="form-control changefield">
                        <?
                          for($i = 0; $i <= 100; $i++)
                          {
                            if($dados['victim_inform'] == $i){ $sel = "selected"; }else{ $sel = ""; }
                            echo "<option value='".$i."' $sel>".$i."</option>";
                          }
                        ?>
                      </select>
              </div>
        </div>
  </div>

<div class="row">
  <div class="col-sm-12" style="background:#FFFFF0">

        <table>
        </table>

  </div>
</div>
</section>
<script>
  $('.select2').select2();
</script>
