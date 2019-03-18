  <?
    session_start();
    require("../libs/php/funcoes.php");
    require("../libs/php/conn.php");

      logger("Acesso","WAZE - Mapa");
  ?>
  <style>
  iframe {
      display: block;       /* iframes are inline by default */
      background: #000;
      border: none;         /* Reset default border */
      height: 85vh;        /* Viewport-relative units */
      width: 85vw;
      margin-top: -80px;
  }
  </style>
  <section role="main" class="content-body">
    <iframe src="https://embed.waze.com/pt-BR/iframe?zoom=12&lat=-26.294688&lon=-48.848253"></iframe>
  </section>
