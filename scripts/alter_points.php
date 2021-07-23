<?php
	//ini_set('display_errors',1);
	
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( '../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	//Je tente la connexion BDD
	include_once($root . '/scripts/bdd_connect.php');
	
	//Je récupère l'array JSON directement
	$register_alteredpointsteam = $_POST['pointsalteration'];

	//Si le JSON est bien un JSON, on continue le traitement de l'info reçue
	if (empty($register_alteredpointsteam)) {
		//Si le contenu est vide, on retourne un échec
		$success_msg['status'] = 'error: initial AJAX request was empty';
	}
	else {

		//Check si on a bien un teamname, si oui on continue
		if (array_key_exists('teamname', $register_alteredpointsteam)) {
	
			$teamname = $register_alteredpointsteam['teamname'];
			$points2alter = $register_alteredpointsteam['points'];

			//On fait l'update de la team en BDD
			$req0 = $bdd->prepare("
			UPDATE teams SET team_points = team_points + :points2alter WHERE team_name = :team_name;
			");
			$req0->execute(array(
				'points2alter' => $points2alter,
				'team_name' => $teamname));
				
			$req0->closeCursor();	
			
			//On renvoie le nouveau montant de points
			$req1 = $bdd->prepare("
			SELECT team_points FROM teams WHERE team_name= :team_name;
			");
			$req1->execute(array(
				'team_name' => $teamname));

			//Inscription des données récupérées dans le tableau fetch
			$donnees1 = $req1->fetch();			
			
			$req1->closeCursor();
			
			//Récupération des résultats de la requête
			$fetched_teampoints = trim($donnees1['team_points']);	
				
			$success_msg = ["status" => "success", "message" => $fetched_teampoints];

		}
	}
	
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////
	//Si tout OK, en fin de code on retourne un succès
	echo json_encode($success_msg);
	/////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////


?>