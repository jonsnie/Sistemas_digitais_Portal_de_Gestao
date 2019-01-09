<?
	session_start();
	$error = $_SESSION['error'];
	session_destroy();
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
	<title>Sistemas Digitais</title>
   <!--Made with love by Mutiullah Samim -->

	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
	<!--Custom styles-->
	<style>
  /* Made with love by Mutiullah Samim*/

html,body{
/*background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg');*/
/*background-image: url("assets/images/PanoramicaJoinvilleCentro.jpg");*/
background-image: url("assets/images/joinville_rev0.jpg");

background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Lato', sans-serif;
}

.titulo
{
color: white;
margin-top: 50px;
}

  </style>
</head>
<body>

<div class="container">
<? /*
	<div class="row justify-content-center align-items-center">
				<div class="col-sm text-center">
							<div class="row">
										<div class="col-sm" style="background-color:#FF0000;height:100px">COLUNA 00.0</div>
										<div class="col-sm" style="background-color:#00FF00">COLUNA 00.1</div>
						  </div>
							<div class="row">
										<div class="col-sm"  style="background-color:#0000FF;height:100px">COLUNA 00.2</div>
										<div class="col-sm"  style="background-color:#FF00FF">COLUNA 00.3</div>
						  </div>
				</div>
				<div class="col-sm text-center align-top"  style="background-color:#FFFF00">
					COLUNA 01<br>AAA
					COLUNA 01<br>AAA
					COLUNA 01<br>AAA
				</div>
	</div>
*/ ?>
	<div class="row">
		<div class="col-sm">
			<img style="" src="assets/images/logo.png">
		</div>
		<div class="col-sm">
			<img class="float-right" style="margin-top:10px" src="https://www.joinville.sc.gov.br/wp-content/uploads/2017/07/logoPMJ2x.png">
		</div>
	</div>
	<div` class="row justify-content-center align-items-center">
	    <div class="col-sm text-center">
				<div class="row">
						<div class="col-sm">
								<h4 class="titulo">Mobilidade - Dados abertos</h4>
						</div>
				</div>
				<div class="row">
						<div class="col-sm">
												<div class="card">
													<h5 class="card-header text-muted">Trânsito</h5>
													<div class="card-body">
													<h5 class="card-title"><b>Alertas de eventos de trânsito</b></h5>
													<p class="card-text text-muted"><small>Alertas de incidentes e perigo no trânsitro na cidade de Joinville, relatório mensal agrupado dia a dia</small></p>
													</div>
													<div class="card-footer">
													<a href="#" class="btn btn-outline-info">Acessar</a> <a href="#" class="btn btn-outline-info">Exemplo</a>
													</div>
												</div>
						</div>
						<div class="col-sm">
											<div class="card">
												<h5 class="card-header text-muted">Trânsito</h5>
												<div class="card-body">
												<h5 class="card-title"><b>Registros de congestionamentos</b></h5>
												<p class="card-text text-muted"><small>Eventos de congestionamento na cidade de Joinville, relatório mensal agrupado dia a dia</small></p>
												</div>
												<div class="card-footer">
												<a href="#" class="btn btn-outline-info">Acessar</a> <a href="#" class="btn btn-outline-info">Exemplo</a>
												</div>
											</div>
						</div>
				</div>

				<div class="row" style="margin-top:10px">
						<div class="col-sm">
												<div class="card">
													<h5 class="card-header text-muted">Trânsito</h5>
													<div class="card-body">
													<h5 class="card-title"><b>Contador de tráfego</b></h5>
													<p class="card-text text-muted"><small>Contador de tráfego dos radares da cidade de Joinville, relatório mensal agrupado dia a dia</small></p>
												</div>
												<div class="card-footer">
													<a href="#" class="btn btn-outline-info">Acessar</a> <a href="#" class="btn btn-outline-info">Exemplo</a>
													</div>
												</div>
						</div>
						<div class="col-sm">
											<div class="card">
												<h5 class="card-header text-muted">Trânsito</h5>
												<div class="card-body">
												<h5 class="card-title"><b>Acidentes</b></h5>
												<p class="card-text text-muted"><small>Registro de acidentes na cidade de Joinville, relatório mensal agrupado dia a dia</small></p>
											</div>
											<div class="card-footer">
												<a href="#" class="btn btn-outline-info">Acessar</a> <a href="#" class="btn btn-outline-info">Exemplo</a>
												</div>
											</div>
						</div>
				</div>



	    </div>

	    <div class="col-sm text-center">
				<div class="row" style="min-height:600px">
						<div class="col-sm">
								<h4 class="titulo">Aplicações</h4>
								<a href="index2.php?param=rot" class="btn btn-outline-success btn-lg btn-block">Registro de ocorrência de trânsito</a>
								<a href="index2.php?param=smart" class="btn btn-outline-success btn-lg btn-block">Smart Mobility</a>
								<a href="index2.php?param=portal" class="btn btn-outline-success btn-lg btn-block">Portal de gestão</a>
						</div>
				</div>
	    </div>

	  </div>
</div>
</body>
</html>
<script>

</script>
