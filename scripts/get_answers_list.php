<?php
	//ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');

	if(isset($_GET['answerlist']) && $_GET['answerlist'] == "asked"){
		
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

		//On fait l'update de la team en BDD
		$req1 = $bdd->prepare("
		SELECT answer_img FROM answers WHERE answers_game = :answers_game;
		");
		$req1->execute(array(
			'answers_game' => $fetched_gameid));

		//Déclaration du tableau de stockage temporaire des infos
		$answers_storage = [];

		while($donnees1 = $req1->fetch()){

			//Je créé une variable de stockage des réponses que j'inclue à la réponse JSON, et que je vide à nouveau à chaque début de loop
			//Cela me permet de construire un JSON sans réponse redondante

			$answers_storage[] = $donnees1["answer_img"];

		}

		$req1->closeCursor();

		$success_msg["answers"] = $answers_storage;
		$success_msg["status"] = "success";
		$success_msg["message"] = "OK";

	}
	else{
		$success_msg["status"] = "failure";
		$success_msg["message"] = "GET value could not be retrieved";
	}
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////


?>