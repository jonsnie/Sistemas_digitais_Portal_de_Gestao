  <?
    session_start();
    require_once("../libs/php/conn.php");
    require_once("../libs/php/funcoes.php");
  ?>
  <section role="main" class="content-body">
      <header class="page-header">
        <h2>Mapa do Waze</h2>
        <div class="right-wrapper pull-right" style='margin-right:15px;'>
          <ol class="breadcrumbs">
            <li><a href="index_sistema.php"><i class="fa fa-home"></i></a></li>
            <li><span class='text-muted'>Waze</span></li>
            <li><span class='text-muted'>Mapa</span></li>
          </ol>
        </div>
      </header>
      <section class="panel">
        <header class="panel-heading">
          <div class="panel-actions" style='margin-top:-12px'></div>
        </header>
        <div class="panel-body">
          <iframe src="https://embed.waze.com/pt-BR/iframe?zoom=12&lat=-26.294688&lon=-48.848253"
            width="1000" height="400"></iframe>
        </div>
      </section>
  </section>
