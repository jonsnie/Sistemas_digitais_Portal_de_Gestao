<aside id="sidebar-left" class="sidebar-left">

  <div class="sidebar-header">
    <div class="sidebar-title">
      Menu do Sistema:
    </div>
    <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
      <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
    </div>
  </div>

  <div class="nano">
    <div class="nano-content">
      <nav id="menu" class="nav-main" role="navigation">
        <ul class="nav nav-main">
          <li class="nav-active">
            <a href="index_sistema.php" ajax="false">
              <i class="fa fa-home" aria-hidden="true"></i><span>Home</span>
            </a>

            <li class="nav-parent">
          	     <a><i class="fa fa-align-left" aria-hidden="true"></i><span>Convênios</span></a>
          			 <ul class="nav nav-children">
          						<li class="nav-parent">
          						    <a>Radares</a>
          							  <ul class="nav nav-children">
              								  <li><a style="cursor:pointer" href="radar/index.php">Equipamentos</a></li>
          				        </ul>
          						</li>
                      <li class="nav-parent">
          						    <a>WAZE</a>
          							  <ul class="nav nav-children">
                                <li><a href="waze/index.php">Dashboard</a></li>
                                <li><a href="waze/mapa.php">Mapa</a></li>
          				        </ul>
          						</li>
          				</ul>
          	   </li>

<? /* ?>
          <li class="nav-parent">
            <a><i class="fa fa-bus" aria-hidden="true"></i><span>Radares</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="radar/index.php"      ic-target="#wrap">Equipamentos</a></li>
              <!--<li><a href="#" ic-get-from="radar/dashboard.php"  ic-target="#wrap">Dashboard</a></li>-->
              <!--<li><a href="#" ic-get-from="#"                    ic-target="#wrap">Mapa</a></li>-->

            </ul>
          </li>

          <li class="nav-parent">
            <a><i class="fa fa-road" aria-hidden="true"></i><span>Waze</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="waze/index.php"      ic-target="#wrap">Dashboard</a></li>
              <li><a href="#" ic-get-from="waze/mapa.php"       ic-target="#wrap">Mapa</a></li>

            </ul>
          </li>

<? */ ?>

          <li class="nav-parent">
            <a><i class="fa fa-bank" aria-hidden="true"></i><span>Aplicações</span></a>
            <ul class="nav nav-children">
              <!--<li><a href="erg/index.php">Estacionamento Rotativo</a></li>-->
              <li><a href="oct/ocorrencias.php">Ocorrências de Trânsito</a></li>


<?  if($_SESSION['id']==1 || $_SESSION['id']==108 || $_SESSION['id']==109||$_SESSION['id']==110){ ?>
  <li class="nav-parent">
    <a><span>Sistema de gestão</span></a>
    <ul class="nav nav-children">
        <li><a href="oct/index.php">Sistema</a></li>
        <li><a href="oct/ocorrencias.php">Ocorrências</a></li>
        <li><a href="oct/dashboard.php">Evolução mensal</a></li>
        <li><hr></li>
        <li><a href="#">Diário administrativo</a></li>
        <li><a href="#">Turnos</a></li>
        <li><a href="#">Evolução diária</a></li>
        <li><a href="#">Configurações</a></li>
    </ul>
  </li>



<?  } ?>
<li class="nav-parent">
  <a><span>SERP</span></a>
  <ul class="nav nav-children">
      <li><a href="erg/index.php">Dashboard</a></li>
      <li><a href="erg/rel_autuados.php">Autuações</a></li>
<?  if($_SESSION['id']==1 || $_SESSION['id']==35){ ?>
      <li><a href="erg/vagas.php">vagas</a></li>
<? } ?>
  </ul>
</li>

            </ul>
          </li>


<!--

          <li class="nav-parent">
            <a><i class="fa fa-database" aria-hidden="true"></i><span>OpenData Mobilidade</span></a>
            <ul class="nav nav-children">
              <li><a href="sistema/conteudo0.php">Convênios <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Relatórios <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Configurações <sup><small>(Em Desenvolvimento)</small></sup></a></li>
              <li><a href="sistema/conteudo0.php">Auditoria <sup><small>(Em Desenvolvimento)</small></sup></a></li>
            </ul>
          </li>
-->
<?  if($_SESSION['id']==1 || $_SESSION['id']==35){ ?>
          <li class="nav-parent">
            <a><i class="fa fa-cogs" aria-hidden="true"></i><span>Configurações</span></a>
            <ul class="nav nav-children">

              <li><a href="usuarios/index.php">Usuários</a></li>

<!--
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Servidores</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Serviços</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Variaveis de sistema</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Logs do sistema</a></li>
-->
<? if($_SESSION["id"]==1){?>
              <li><a href="sistema/logviewer.php">Logs do sistema</a></li>
              <li><a href="sistema/teste.php">Desenvolvimento</a></li>
<? } ?>
            </ul>
          </li>
<? } ?>

        </ul>
      </nav>



    </div>

  </div>

</aside>
