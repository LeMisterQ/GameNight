<?php
	//ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');
	
	$myteam = $_SESSION['teamname'];
	
	function sendMsg($id, $msg) {
		echo "id: $id" . PHP_EOL;
		echo "data: $msg" . PHP_EOL;
		echo PHP_EOL;
		ob_flush();
		flush();
	}
	
	//Je récupère l'array JSON directement
	$register_buzzingteam = $_POST['buzzingteam'];

	//Si le JSON est bien un JSON, on continue le traitement de l'info reçue
	if (empty($register_buzzingteam)) {
		//Si le contenu est vide, on retourne un échec
		$success_msg['status'] = 'error: initial AJAX request was empty';
	}
	else {
		
		//Utiliser un notification SSE pour transmettre à tous celui qui a buzzé
		//Permet de desactiver le buzzer de tous les autres coté client
		//Permet de faire clignoter la team avec la main coté client
		
		//On recupère le gameid avec le gamecode
		$req0 = $bdd->prepare("
		SELECT buzzing_team FROM games WHERE game_code = :game_code;
		");
		$req0->execute(array(
			'game_code' => $gamecode));

		//Inscription des données récupérées dans le tableau fetch
		$donnees0 = $req0->fetch();
		$req0->closeCursor();

		//Récupération des résultats de la requête
		$fetched_buzzing_team = trim($donnees0['buzzing_team']);	
		
		if($fetched_buzzing_team == "none"){
			
			//On inscrit la team en BDD		

			//On recupère le gameid avec le gamecode
			$req = $bdd->prepare("
			UPDATE games SET buzzing_team = :buzzing_team WHERE game_code = :game_code;
			UPDATE games SET buzzer_state = 'closed' WHERE game_code = :game_code;
			");
			$req->execute(array(
				'buzzing_team' => trim($register_buzzingteam["buzz"]),
				'game_code' => $gamecode));
				
			$req->closeCursor();
			
			//On desactive le buzzing car il a été successful au moins 1 fois
			$req1 = $bdd->prepare("
			UPDATE teams SET can_buzz = 0 WHERE team_name = :team_name;
			");
			$req1->execute(array(
				'team_name' => $myteam));
			
			$req1->closeCursor();
			
			$success_msg = ["status" => "success", "message" => "OK"];
			
		}
		else{

			$success_msg = ["status" => "failure", "message" => $fetched_buzzing_team];
		}
		
	}
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////


?>