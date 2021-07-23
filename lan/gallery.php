<?php
//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (!isset($_SESSION['pseudo']) OR !isset($_SESSION['email'])){
	
	//On inclue la page de please connect car pas de session
	include_once('includes/please_connect.php');

	}
	else {
	
		//On inclue la bdd et on va chercher le chemin de l'avatar de l'utilisateur
		include_once('includes/bdd_connect.php');
	
?>	
	
	<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
	
	<?php include_once('includes/header_main.php'); ?>
	<link rel="stylesheet" href="css/pop_ups.css?<?php echo filemtime('css/mainpage.css') ?>"/>
		<body>

		<?php 
		include_once('includes/profile_navbar.php');
		?>
				
			<main class="boite_principale" >
				<section id="section0">
					<div id="article_0"></div>
					<div id="article_header">
						<?php
						include('includes/lan_name.php');
						echo('<br>');
						include_once('includes/lan_event_number.php');
						?>
						<p id="demo"></p>
					</div>
				</section>
				<header>
					<div id="main_gal" class="article_other">Gallerie photo des anciennes LAN</div>
					<div class="article_sub">N'hésites pas à contribuer !</div>						
				</header>
				<?php
					include_once('includes/gallery_list.php');
				?>
				<header>
				</header>				
			</main>
		</body>
		<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
		<script src="js/view_photo_video.js?<?php echo filemtime('js/view_photo_video.js') ?>"></script>		
		<script src="js/display_confirm_pop_up.js?<?php echo filemtime('js/display_confirm_pop_up.js') ?>"></script>
	</html>
	
<?php
	}
?>
