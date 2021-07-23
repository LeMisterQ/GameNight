<?php

	ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( 'config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//On inclue la page des modales (non dédiées aux images)
	include_once($root . '/includes/modal.php');

	//Si pas de variables de session à ce moment là, direction Accueil
	if(empty($_SESSION['teamname'])){

		//Redirection vers la page d'acceuil
		header('Location: /index.php');
	}
	else{
		//Création dynamique des tableaux de teams
		//Récupération du gameid et de la team en session

		$myteam = $_SESSION['teamname'];
		$meplayer = $_SESSION['player'];

		//Je tente la connexion BDD
		include_once($root . '/scripts/bdd_connect.php');

		//On recupère le gameid avec le gamecode
		$req0 = $bdd->prepare("
		SELECT gameid FROM games WHERE game_code = :game_code;
		");
		$req0->execute(array(
			'game_code' => $gamecode));

		//Inscription des données récupérées dans le tableau fetch
		$donnees0 = $req0->fetch();
		$req0->closeCursor();

		//Récupération des résultats de la requête
		$fetched_gameid = $donnees0['gameid'];

		//On récupère chaque team présente dans la game
		$req1 = $bdd->prepare("
		SELECT teamid, team_points, team_name FROM teams WHERE team_game = :team_game AND team_name = :myteam;
		");
		$req1->execute(array(
			'team_game' => $fetched_gameid,
			'myteam' => $myteam));

		//Inscription des données récupérées dans le tableau fetch
		$donnees1 = $req1->fetch();
		$req1->closeCursor();

		$current_teamid = $donnees1['teamid'];
		$current_teamname = $donnees1['team_name'];
		$current_teampoints = $donnees1['team_points'];
		$current_playerdiv = "";

		//On recupère les joueurs de chaque team
		$req2 = $bdd->prepare("
		SELECT player_name FROM players WHERE player_team = :player_team;
		");
		$req2->execute(array(
			'player_team' => $current_teamid));

		//On loop dans chaque ligne récupérée pour faire les opérations nécéssaires
		while ($donnees2 = $req2->fetch())
		{
			//On construit le div de chaque joueur récupéré
			if($donnees2['player_name'] == $meplayer){
				$current_playerdiv .= '<div id="me" class="playername">' . $donnees2['player_name'] . '</div>';
			}
			else{
				$current_playerdiv .= '<div class="playername">' . $donnees2['player_name'] . '</div>';
			}
		}

		$req2->closeCursor();

		//Construction de la page
		if(isMobileDevice()){

		$points_container = '<div class="points_holder"><div class="points_container">' . $current_teampoints . '</div><div class="points_container pts">pts</div></div>';
			
			$buzzer_section = '
				<section class="buzzer">
					<div class="toptitle">
						<h1>
							<div class="pink">Game</div>
							<br>
							<div class="cyan">Night</div>
						</h1>
					</div>
					<div class="buzzer_container">
						<div class="clicked">
							BUZZ HERE
						</div>
						<div class="teamturn">
						</div>
					</div>
					<div class="footer_container">
					</div>
				</section>
				';
			$mobile_footer = '
				<section class="mobile_footer">
					<h1 class="alternative"><div class="pink">QUIT</div></h1>
					<div class="img_footer_container">
						<img class="coktail" src="img/coktail.png?' . filemtime('img/coktail.png') . '"></img>
						<img class="coktail" src="img/coktail2.png?' . filemtime('img/coktail.png') . '"></img>
					</div>
				</section>
			';
		}
		else{

		$points_container = '<div class="points_container">' . $current_teampoints . '<div class="points_container pts">pts</div></div>';
		
		$buzzer_section = '
		<section class="buzzer">
			<div class="toptitle">
				<h1>
					<div class="pink">Game</div>
					<br>
					<div class="cyan">Night</div>
				</h1>
			</div>
			<div class="buzzer_container">
				<div class="clicked">
					BUZZ HERE
				</div>
				<div class="teamturn">
				</div>
			</div>
			<div class="footer_container">
			<img class="coktail" src="img/coktail.png?' . filemtime('img/coktail.png') . '"></img>
			<h1 class="alternative"><div class="pink">QUIT</div></h1>
			<img class="coktail" src="img/coktail2.png?' . filemtime('img/coktail2.png') . '"></img>
			</div>
		</section>
		';
		}
		
		//Table container de l'équipe qui vient de se créer (affichée en 1er)
		$team_container = '<div class="team_container myteam">' . $current_teamname . '</div>';
		$player_container = '<div class="player_container">' . $current_playerdiv . '</div>';
		$lowertable_div = '<div class="lowertable">' . $player_container . $points_container . '</div>';
		$table_container = '<div class="table_container">' . $team_container . $lowertable_div . '</div>';		

		$sectiontableaux1 = '<section class="tableaux">' . $table_container . '</section>';
		$sectiontableaux2 = '<section class="tableaux"></section>';

		//On génère le gameboard selon le device détecté
		if(isMobileDevice()){

			$gameboard = $buzzer_section . $sectiontableaux1 . $sectiontableaux2 . $mobile_footer;
		}
		else {

			$gameboard = $sectiontableaux1 . $buzzer_section . $sectiontableaux2;
		}

		//On appelle le notify server pour le Buzzer State pour qu'il prévienne les pages clients
		$output = shell_exec($root . '/buzz_notification.php');

	}
?>

<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv=Cache-control content=no-cache>
		<meta http-equiv=Expires content=-1>

		<link rel="stylesheet" href="css/game.css?<?php echo filemtime('css/game.css'); ?>"/>
		<link rel="stylesheet" href="css/pop_ups.css?<?php echo filemtime('css/pop_ups.css') ?>"/>

		<link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
		<title>
		Game en cours!
		</title>
	</head>
	<body>
			<main class="container_main">
				<?php echo $mymodal; ?>
				<?php include_once($root . '/includes/modal_img.php'); ?>
				<?php include_once($root . '/includes/modal_video.php'); ?>
				<?php echo $gameboard; ?>
			</main>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="js/ws_client.js?<?php echo filemtime('js/ws_client.js') ?>"></script>

	</body>
</html>