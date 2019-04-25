<?
	session_start();
	$error = $_SESSION['error'];
	session_destroy();

	switch($_GET["param"])
	{
			case "portal":
				$param 		 = "portal";
				$titulo    = "Portal";
				$subtitulo = "Gestão dos sistemas digitais";
				$modulo    = "Portal";
				break;
			case "rot":
				$param     = "rot";
				$titulo    = "ROT";
				$subtitulo = "Registro de Ocorrências de Trânsitos";
				$modulo    = "ROT";
				break;
			case "smart":
				$param 		 = "smart";
				$titulo    = "SmartMobility";
				$subtitulo = "Aplicações";
				$modulo    = "Smartmobility";
				break;
			case "dados":
				$param 		 = "dados";
				$titulo    = "Dados abertos";
				$subtitulo = "Mobilidade Urbana";
				$modulo    = "Opendata";
				break;
			case "erg":
					$param 		 = "estacionamento";
					$titulo    = "Estacionamento";
					$subtitulo = "Rotativo Público";
					$modulo    = "ERG";
					break;
			default:
				header("Location: index3.php");
				break;

	}
?>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html>
<head>
	<title>Portal Digital</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
   <!--Made with love by Mutiullah Samim -->

	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<!--Custom styles-->
	<style>
  /* Made with love by Mutiullah Samim*/

@import url('https://fonts.googleapis.com/css?family=Numans');

html,body{
/*background-image: url('http://getwallpapers.com/wallpaper/full/a/5/d/544750.jpg');*/
/*background-image: url("assets/images/PanoramicaJoinvilleCentro.jpg");*/
background-image: url("assets/images/joinvillenoite00.jpg");

background-size: cover;
background-repeat: no-repeat;
height: 100%;
font-family: 'Numans', sans-serif;
}

.container{
height: 100%;
align-content: center;
}

.card{
height: 370px;
margin-top: auto;
margin-bottom: auto;
width: 400px;
background-color: rgba(0,0,0,0.5) !important;
}

.social_icon span{
font-size: 60px;
margin-left: 10px;
color: #FFC312;
}

.social_icon span:hover{
color: white;
cursor: pointer;
}

.card-header h3{
color: white;
}

.social_icon{
position: absolute;
right: 20px;
top: -45px;
}

.input-group-prepend span{
width: 50px;
background-color: #FFC312;
color: black;
border:0 !important;
}

input:focus{
outline: 0 0 0 0  !important;
box-shadow: 0 0 0 0 !important;

}

.remember{
color: white;
}

.remember input
{
width: 20px;
height: 20px;
margin-left: 15px;
margin-right: 5px;
}

.login_btn{
color: black;
background-color: #FFC312;
/*width: 100px;*/
}

.login_btn:hover{
color: black;
background-color: white;
}

.links{
color: white;
}

.links a{
margin-left: 4px;
color: #CCCCCC;
text-decoration: none;
}
.links a:hover{
margin-left: 4px;
color: white;
text-decoration: none;
}
  </style>
</head>
<body>
<?
	if(isset($error)){
			echo "<div id='error' style='background:white;position:absolute;opacity:0.5;color:red;border-radius: 5px;margin:10px;padding:10px'>
						<i>".$error."</i></div>";
}
?>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3><?=$titulo;?></h3><h6 style="color:white"><small><?=$subtitulo;?></small></h6>
<!--
				<div class="d-flex justify-content-end social_icon">
      		<span><i class="fab fa-facebook-square"></i></span>
					<span><i class="fab fa-google-plus-square"></i></span>
					<span><i class="fab fa-twitter-square"></i></span>
    		</div>
-->
    	</div>
			<div class="card-body">
				<form action="auth/autenticar.php" method="post">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-user"></i></span>
						</div>
						<input name="username" type="text" class="form-control" placeholder="username">

					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="fas fa-key"></i></span>
						</div>
						<input name="password" type="password" class="form-control" placeholder="password">
					</div>
<!--
  				<div class="row align-items-center remember">
						<input type="checkbox">Lembrar
					</div>
-->
    			<div class="form-group">
						<input type="hidden" name="modulo" value="<?=$modulo;?>" />
						<input type="submit" value="Entrar" class="btn float-right login_btn">
						&nbsp;<a href="/" class="btn btn-outline-warning links">Voltar</a>
					</div>
				</form>
			</div>
			<div class="card-footer">
<!--
							<div class="d-flex justify-content-center links">
								<a href="#">Solicitar acesso ao sistema.</a>
							</div>
-->
							<div class="d-flex justify-content-center links">
								<a class="btn btn-outline-warning" href="#">Solicitar acesso</a>
								<a class="btn btn-outline-warning" href="#">Esqueci minha senha</a>
							</div>
						</div>

    	</div>
    </div>


	</div>
</div>
</body>
</html>
<script>
$("#error").fadeOut(10000, "linear");
</script>
