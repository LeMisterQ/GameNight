<?php
	//ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');
	
	//On détruit la team en BDD			

			//On recupère le gameid avec le gamecode
			$req = $bdd->prepare("
			UPDATE games SET buzzing_team = 'none' WHERE game_code = :game_code;
			UPDATE games SET buzzer_state = 'open' WHERE game_code = :game_code;
			");
			$req->execute(array(
				'game_code' => $gamecode));
		
	$success_msg = ["status" => "success", "message" => "OK"];
	
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	
?>