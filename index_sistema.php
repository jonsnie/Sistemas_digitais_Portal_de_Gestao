<?
	error_reporting(0);
	session_start();
	require("libs/php/sessao.php");
?>
<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">

		<title>SISTEMAS DIGITAIS</title>
		<meta name="keywords" content="GESTÃO CONTROLE CONVÊNIO" />
		<meta name="description" content="SISTEMA DE GESTÃO INTERNO">
		<meta name="author" content="">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="assets/vendor/font-awesome/css/font-awesome.css" />
		<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">-->
		<link rel="stylesheet" href="assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="assets/vendor/morris/morris.css" />
		<link rel="stylesheet" href="assets/vendor/pnotify/pnotify.custom.css" />





		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
			integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
			crossorigin=""/>

			<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
				integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
				crossorigin=""></script>

		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="assets/stylesheets/skins/default.css" />

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="assets/stylesheets/theme-custom.css">

		<!-- Head Libs -->
		<script src="assets/vendor/modernizr/modernizr.js"></script>


		<link  href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


		<style>
			.select2-selection__rendered {line-height: 32px !important;	}
			.select2-selection 					 {height: 34px !important;		  }
			.box_shadow
			{
			  -webkit-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  -moz-box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			  box-shadow: 10px 10px 56px -12px rgba(0,0,0,0.3);
			}
		</style>
	</head>
	<body>
		<section class="body">
					<? require_once("sistema/menu_top.php"); ?>
					<div class="inner-wrapper">
								  <?
										//if($_GET['modulo'] != "eri"){
											require_once("sistema/menu_esq.php");
										//}
									?>
								<div id="wrap">
									<?
									 	//if($_GET['modulo'] != "eri"){
											require_once("sistema/conteudo.php");
									//	}else {
										//	require_once("eri/index.php");
									//	}
									?>
								</div>
					</div>
		</section>
		<!-- Vendor -->
		<script src="assets/vendor/jquery/jquery.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
		<script src="assets/vendor/flot/jquery.flot.js"></script>
		<script src="assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
		<script src="assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
		<script src="assets/vendor/gauge/gauge.js"></script>
		<script src="assets/vendor/snap-svg/snap.svg.js"></script>
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="assets/vendor/jqvmap/jquery.vmap.js"></script>
		<script src="assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
		<script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
		<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>
		<script src="assets/vendor/pnotify/pnotify.custom.js"></script>
		<script src="assets/vendor/intercooler/intercooler-0.4.8.js"></script>
		<script src="assets/vendor/jquery-mockjax/jquery.mockjax.js"></script>
		<script src="assets/vendor/gauge/gauge.js"></script>

		<script src="http://oss.maxcdn.com/jquery.form/3.50/jquery.form.min.js"></script>



		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>

		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>

		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>


		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


		<!--<script src="assets/javascripts/jquery-html5-uploader/jquery.html5uploader.js"></script>-->


		<!-- Examples -->
		<!--<script src="assets/javascripts/dashboard/examples.dashboard.js"></script>-->



<script>
		(function( $ ) {
			'use strict';

			var stack_bar_top = {"dir1": "down", "dir2": "right", "push": "top", "spacing1": 0, "spacing2": 0};
		<? if( $_SESSION['system_messages']['titulo'] != ""){ ?>
					var notice = new PNotify({
						title: '<?=$_SESSION['system_messages']['titulo']?>',
						text: '<?=$_SESSION['system_messages']['texto'];?>',
						type: '<?=$_SESSION['system_messages']['tipo'];?>',
						addclass: 'stack-bar-top',
						stack: stack_bar_top,
						width: "100%",
						shadow: true,
						hide: true,
						delay: 1000,
						closer: true
					});
		<? } ?>
		}).apply( this, [ jQuery ]);


		$(document.body).on('click', 'a' ,function(event)
		{
			var url    = $(this).attr('href');
			var ajax   = $(this).attr('ajax');
			var alvo   = $(this).attr('alvo');

			if(url != "#" && (typeof ajax === "undefined"))
			{
				//$('#wrap').load(url);
				//$('#wrap').load(url,function(){}).hide().fadeIn();
				$('#wrap').load(url);
				return false;
			}
		});






		$(document.body).on('submit', 'form', function(event)
		{
				var rel   = $(this).attr('rel');
				var alvo  = $(this).attr('alvo');
				var debug = $(this).attr('debug');
        if(debug == 1){
            alert("Formulario: "+$(this).attr('name')+"\nRel: "+ $(this).attr('rel')+"\nalvo: "+ $(this).attr('alvo')+"\nMetodo: "+$(this).attr("method")+"\nURL: "+ $(this).attr("action")+"\nDados:"+$(this).serialize());
        }

				if(rel != "no_ajax")
				{

					$.ajax(
					{
						type: $(this).attr("method"),
						url:  $(this).attr("action"),
						cache: false,
						data: $(this).serialize(),
						beforeSend: function(){	},
						success: function(data)
						{
              //if(debug == 1){ alert("Enviado com sucesso !"); }
							if($("#id").val() != ""){ var show_id = '#'+$("#id").val();}
							if(typeof alvo == "undefined"){ $('#wrap').html(data);  }
							else		  		   		  			  { $('#'+alvo).html(data); }
						},
            error: function(){ }
					});
				return false;
				}
		});

</script>

	</body>
</html>
<? unset($_SESSION['system_messages']); ?>
