<?
  session_start();
  require_once("../libs/php/conn.php");
  require_once("../libs/php/funcoes.php");
?>
<style>
#mapid { height: 380px; }
</style>
<section role="main" class="content-body">
  <header class="page-header">
    <h2>Mapa</h2>
    <div class="right-wrapper pull-right" style='margin-right:15px;'>
      <ol class="breadcrumbs">
        <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
        <li><span class='text-muted'>Roterizador</span></li>
        <li><span class='text-muted'>Mapa</span></li>
      </ol>
      <!--<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>-->
    </div>
  </header>

<?




?>

  <!--    <div id="mapid"><img src="../assets/images/fundomapa.png"></div>-->

<iframe src="https://www.google.com/maps/@-26.2977942,-48.8451391,1596m" width="1050" height="550" frameborder="0"
style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);"></iframe>

</section>

<?
    $latlon[0]="-26.301033";
    $latlon[1]="-48.840862";
    $zoommap = 15;
?>

<script>









</script>
