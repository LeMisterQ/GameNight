<?php
	//ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');	
	
	if(isset($_GET['answerimg'])){
		
		$answerimg = $_GET['answerimg'];
		
		//On fait l'update de la team en BDD
		$req1 = $bdd->prepare("
		UPDATE games SET answer_img = :answer_img WHERE game_code = :game_code;
		");
		$req1->execute(array(
			'answer_img' => $answerimg,
			'game_code' => $gamecode));
			
		$req1->closeCursor();
			
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