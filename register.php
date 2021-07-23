<?php
	//ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( 'config.php' );
	
	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//On inclue la page des modales (non dédiées aux images)
	include_once($root . '/includes/modal.php');	

	//Si une session existe, l'utilisateur a déjà créé son équipe, il ne peut plus en créer
	if($_SESSION['teamname']){

		//Redirection vers la page de game
		header('Location: /game.php');
	}
	else{
		//Récupère le code d'une game disponible
		//Je tente la connexion BDD
		include_once($root . '/scripts/bdd_connect.php');
		
		
		//Gestion des boutons de confirmation et du code de la partie
		$gamestate = $gamecode;
		$confirm_button_state = '<button id="confirmteam" class="registerbtn" name="submit" disabled>Confirmer et jouer ></button>';
		$confirm_button_join_state = '<button id="confirmplayer" class="registerbtn" name="submit" disabled>Confirmer et jouer ></button>';
				
	}
	
?>

<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv=Cache-control content=no-cache>
		<meta http-equiv=Expires content=-1>
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">

		<link rel="stylesheet" href="css/register.css?<?php echo filemtime('css/register.css') ?>"/>
		<link rel="stylesheet" href="css/pop_ups.css?<?php echo filemtime('css/pop_ups.css') ?>"/>

		<link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
		<title>
		Inscription de l'équipe
		</title>
	</head>
	<body>
		<?php echo $mymodal; ?>
		<section id="section">
			<div class="article_sub" id="choose">
				<div class="option_wrapper" id="addteam">
					<div class="article_other">Créer une équipe</div>
					<img class="choice" src="img/addteam.png?<?php echo filemtime('img/addteam.png') ?>"></img>
				</div>
				<div class="option_wrapper"  id="jointeam">
					<div class="article_other">Rejoindre une équipe</div>
					<img class="choice" src="img/jointeam.png?<?php echo filemtime('img/jointeam.png') ?>"></img>
				</div>
			</div>
			<div class="article_sub" id="completeform">

				<label for="teamname"><b>Nom de l'équipe</b></label>
				<div class="input-wrapper" id="wrapper-team'">
					<input class="init" type="text" placeholder="Choisir un nom d'équipe" name="teamname" maxlength="14" data-state="" required>
				</div>
				<label for="player1"><b>Team Leader</b></label>
				<div class="input-wrapper" id="wrapper-player1">				
					<input class="init" type="text" placeholder="Entrer le nom du team leader" name="playerleader" maxlength="14" data-state="" required>
				</div>
				
				
				
				<p class="forgot_credentials">En validant, tu t'engages à respecter les règles du jeu !</p>
				<?php echo $confirm_button_state ?>
				
				
				<button class="registerbtn grey" name="back2select_fromTeamform">
				< Retour à la sélection
				</button>
				
				<button onclick="window.location.href = './index.php';" class="registerbtn grey">
					< Accueil
				</button>
				
			</div>
			<div class="article_sub" id="complete_player_form">
				
				<label for="teamname"><b>Quelle équipe rejoindre ?</b></label>
					<select id="select_team" list="team_list" type="text" placeholder="Chercher..." data-list-filter="^">
					</select>
				<label for="player1"><b>Nom Joueur</b></label>
				<div class="input-wrapper" id="wrapper-player1">				
					<input class="init" type="text" placeholder="Entrer votre nom de joueur" name="playerme" maxlength="14" data-state="" required>
				</div>
				
				
				
				<p class="forgot_credentials">En validant, tu t'engages à respecter les règles du jeu !</p>
				<?php echo $confirm_button_join_state ?>
				
				<button class="registerbtn grey" name="back2select_fromPlayerform">
				< Retour à la sélection
				</button>
							
				<button onclick="window.location.href = './index.php';" class="registerbtn grey">
					< Accueil
				</button>
				
			</div>			
		</section>
		<footer>
		
			<p class="forgot_credentials">Code de la session</p>
			<p class="session_code">
				<?php echo($gamestate); ?>
			</p>
		
		</footer>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="js/register.js?<?php echo filemtime('js/register.js') ?>"></script>
		<script src="js/confirm_team.js?<?php echo filemtime('js/confirm_team.js') ?>"></script>
	</body>
</html>