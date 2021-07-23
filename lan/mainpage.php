<?php
ini_set('display_errors',1);

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
	
		<body>
		<div class="modal_lan"><span class="close">&times;</span></div>
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
					<div class="article_other">Salut
					<?php
							echo $_SESSION['pseudo'];
					?> 
					! La forme ?</div>
					<hr>
					<div class="article_other">Liste des jeux</div>
					<div class="article_sub">Niveau Platine visé</div>						
				</header>
				<?php
					include_once('includes/games_list.php');
					include_once('includes/lan_location.php');
					include_once('includes/other_activities_n_warnings.php');
				?>							
			</main>
			<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
			<script src="js/lan_actions.js?<?php echo filemtime('js/lan_actions.js') ?>"></script>
			
		</body>
	</html>
	
<?php
	}
?>
