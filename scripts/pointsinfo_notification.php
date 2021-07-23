<?php
	//ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//La variable teamname est accessible grace à l'include du team_constructor.php

	//SET le header : important sinon le SSE ne fonctionne pas
	header("Content-Type: text/event-stream\n\n");

	function sendMsg($object) {
		echo 'id: 99' . PHP_EOL;
		echo ('data: ' . $object) . PHP_EOL;
		echo 'retry: 5000' . PHP_EOL;
		echo PHP_EOL;
		ob_flush();
		flush();
	}
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');

	//On check directement si la team existe déjà en BDD,
	//Si oui, on refuse l'accès à la page

	$req = $bdd->prepare("
	SELECT game_state, gameid FROM games WHERE game_code = :game_code;
	");
	$req->execute(array(
		'game_code' => $_SESSION['gamecode']));

	//Inscription des données récupérées dans le tableau fetch
	$donnees = $req->fetch();
	$req->closeCursor();

	//Récupération des résultats de la requête
	$fetched_gamestate = $donnees['game_state'];
	$fetched_gameid = $donnees['gameid'];

	//On récupère chaque team présente dans la game
	$req1 = $bdd->prepare("
	SELECT team_points, team_name FROM teams WHERE team_game = :team_game;
	");
	$req1->execute(array(
		'team_game' => $fetched_gameid));

	
	//Inscription des données récupérées dans le tableau fetch
	while ($donnees1 = $req1->fetch())
	{
		
		$current_teamname = $donnees1['team_name'];
		$current_teampoints = $donnees1['team_points'];
		$current_players = [];

		//Tableau de stockage a transformer en JSON
		$team_storage["teamname"] = $current_teamname;
		$team_storage["points"] = $current_teampoints;
		$global_storage[] = $team_storage;
		$team_storage = [];

	}
	
	$req1->closeCursor();
	
	//On delcare un global storage	
	$global_storage["gamestate"] = $fetched_gamestate;
	$globalJSON = json_encode($global_storage);
	
	sendMsg($globalJSON);

?>