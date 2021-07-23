<?php
	//ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//SET le header : important sinon le notification server ne marche pas
	header("Content-Type: text/event-stream\n\n");
	

	function sendMsg($object) {
		echo 'id: 12345' . PHP_EOL;
		echo ('data: ' . $object) . PHP_EOL;
		echo 'retry: 1000' . PHP_EOL;
		echo PHP_EOL;
		ob_flush();
		flush();
	}

	$myteam = $_SESSION['teamname'];

	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');

	//On recupère le gameid avec le gamecode
	$req0 = $bdd->prepare("
	SELECT game_state, buzzer_state, buzzing_team, answer_sound, answer_img FROM games WHERE game_code = :game_code;
	");
	$req0->execute(array(
		'game_code' => $gamecode));

	//Inscription des données récupérées dans le tableau fetch
	$donnees0 = $req0->fetch();
	$req0->closeCursor();

	//On recupère le gameid avec le gamecode
	$req1 = $bdd->prepare("
	SELECT can_buzz FROM teams WHERE team_name = :team_name;
	");
	$req1->execute(array(
		'team_name' => $myteam));

	//Inscription des données récupérées dans le tableau fetch
	$donnees1 = $req1->fetch();
	$req1->closeCursor();

	//Récupération des résultats de la requête
	$fetched_gamestate = trim($donnees0['game_state']);	
	$fetched_buzzer_state = trim($donnees0['buzzer_state']);
	$fetched_buzzing_team = trim($donnees0['buzzing_team']);
	$fetched_answer_sound = trim($donnees0['answer_sound']);
	$fetched_answer_img = trim($donnees0['answer_img']);
	$fetched_can_buzz = trim($donnees1['can_buzz']);

	if($fetched_buzzer_state){		
		
		$global_storage["gamestate"] = $fetched_gamestate;
		$global_storage["buzzstate"] = $fetched_buzzer_state;
		$global_storage["buzzingteam"] = $fetched_buzzing_team;
		$global_storage["canbuzz"] = $fetched_can_buzz;
		
		//Gestion du son de réponse (bonne, mauvaise ou aucun)
		$global_storage["answersound"] = $fetched_answer_sound;
		
		//Gestion de l'image de réponse (à la demande du gamemaster uniquement), retourne l'id de l'img à afficher
		$global_storage["answerimg"] = $fetched_answer_img;
		
		$globalJSON = json_encode($global_storage);
		
		sendMsg($globalJSON);

	}


?>