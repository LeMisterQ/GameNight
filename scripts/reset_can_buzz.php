<?php
	//ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');	
	
	//Je récupère l'array JSON directement
	$register_resetinfo = $_POST['resetinfo'];

	//Si le JSON est bien un JSON, on continue le traitement de l'info reçue
	if (empty($register_resetinfo)) {
		//Si le contenu est vide, on retourne un échec
		$success_msg['status'] = 'error: initial AJAX request was empty';
	}
	else {
	
		//Check si on a bien un teamname, si oui on continue
		if (array_key_exists('teamname', $register_resetinfo)) {
	
			$teamname = $register_resetinfo['teamname'];
			
			if($teamname != "all teams"){

				//On fait l'update de la team en BDD
				$req0 = $bdd->prepare("
				UPDATE teams SET can_buzz = 1 WHERE team_name = :team_name;
				");
				$req0->execute(array(
					'team_name' => $teamname));
					
				$req0->closeCursor();	
					
				$success_msg = ["status" => "success", "message" => "OK"];

			}
			else{
				//On fait l'update de la team en BDD
				$req1 = $bdd->prepare("
				UPDATE teams SET can_buzz = 1;
				");
				$req1->execute();
					
				$req1->closeCursor();
				
				$success_msg = ["status" => "success", "message" => "OK"];
			}
		}
	}
	
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////


?>