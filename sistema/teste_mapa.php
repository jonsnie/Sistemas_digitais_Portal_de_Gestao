<?
error_reporting(E_ALL);
session_start();
//header("Content-Type: text/html; charset=ISO-8859-1",true);
?>
<style>
  #mapid
  {
    position: absolute;
    width: 800px;
    height: 480px;
    z-index: 10000;

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
            Teste de integração de mapa.
            <div id="mapid">[Área do mapa]</div>

        </div>
  </div>
</section>
<?
    $latlon[0]="-26.301033";
    $latlon[1]="-48.840862";
    $zoommap = 15;
?>
<script>


var mymap = L.map('mapid').setView([51.505, -0.09], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 15
}).addTo(mymap);

</script>
