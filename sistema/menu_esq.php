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
            <a href="index_sistema.php">
              <i class="fa fa-home" aria-hidden="true"></i><span>Home</span>
            </a>

            <li class="nav-parent">
          	     <a><i class="fa fa-align-left" aria-hidden="true"></i><span>Convênios</span></a>
          			 <ul class="nav nav-children">
          						<li class="nav-parent">
          						    <a>Radares</a>
          							  <ul class="nav nav-children">
              								  <li><a style="cursor:pointer" ic-get-from="radar/index.php"      ic-target="#wrap">Equipamentos</a></li>
          				        </ul>
          						</li>
                      <li class="nav-parent">
          						    <a>WAZE</a>
          							  <ul class="nav nav-children">
                                <li><a href="#" ic-get-from="waze/index.php"      ic-target="#wrap">Dashboard</a></li>
                                <li><a href="#" ic-get-from="waze/mapa.php"       ic-target="#wrap">Mapa</a></li>
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
              <li><a href="#" ic-get-from="oct/dashboard.php" ic-target="#wrap">Dashboard</a></li>
              <li><a href="#" ic-get-from="oct/index.php"     ic-target="#wrap">Ocorrências de Trânsito</a></li>
            </ul>
          </li>




          <li class="nav-parent">
            <a><i class="fa fa-database" aria-hidden="true"></i><span>OpenData Mobilidade</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="sistema/conteudo0.php"  ic-target="#wrap">Convênios</a></li>
              <li><a href="#" ic-get-from="sistema/conteudo0.php"  ic-target="#wrap">Relatórios</a></li>
              <li><a href="#" ic-get-from="sistema/conteudo0.php"  ic-target="#wrap">Configurações</a></li>
              <li><a href="#" ic-get-from="sistema/conteudo0.php"  ic-target="#wrap">Auditoria</a></li>
            </ul>
          </li>

          <li class="nav-parent">
            <a><i class="fa fa-cogs" aria-hidden="true"></i><span>Configurações</span></a>
            <ul class="nav nav-children">
              <li><a href="#" ic-get-from="usuarios/index.php" ic-target="#wrap">Usuários</a></li>
<!--
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Servidores</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Serviços</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Variaveis de sistema</a></li>
              <li><a href="#" ic-get-from="#" ic-target="#wrap">Logs do sistema</a></li>
-->
              <li><a href="#" ic-get-from="sistema/teste.php" ic-target="#wrap">Desenvolvimento</a></li>
            </ul>
          </li>
        </ul>
      </nav>



    </div>

  </div>

</aside>
