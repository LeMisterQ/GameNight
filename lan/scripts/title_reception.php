<?php

//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
						
		//Constantes
		$this_member=$_SESSION['pseudo'];
		$up_succes = "Success";
		$up_fail = "Failed";
		
		//Réception des données binaires en POST
		$received_member_title = $_POST['member_title'];

		if(isset($received_member_title) AND strlen($received_member_title) <= 255)
			{
			
			$member_title = htmlspecialchars($received_member_title);
			
			$title_upload = true;
			
			if($title_upload == true)
			{
				//On vérifie la présence de cookie de login valides
				include_once('../includes/bdd_connect.php');
				
				//Récupération de l'utilisateur et de son pass hashé
				$req = $bdd->prepare('SELECT uid FROM members WHERE pseudo = :pseudo');
				$req->execute(array(
				'pseudo' => $this_member));
				
				//Inscription des données récupérées dans le tableau fetch		
				$donnees = $req->fetch();
				
				//Récupération des résultats de la requête
				$fetched_uid = $donnees['uid'];
				
				//Je ferme la connexion bdd
				$req->closeCursor();
				
				$req2 = $bdd->prepare("UPDATE members SET title = :title WHERE uid= :uid");
				$req2->execute(array(
				'uid' => $fetched_uid,
				'title' => $member_title));
				
				//Je ferme la connexion bdd
				$req2->closeCursor();
				
				echo $up_succes;
			}			
		}
	}
?>