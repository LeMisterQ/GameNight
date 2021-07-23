<?php

//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
						
		//Constantes
		$this_member=$_SESSION['pseudo'];
		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
		$maxsize=4096000;
		$destination='../img/uploads/';
		$up_succes = "Success";
		$up_fail = "Failed";
		
		//Réception des données binaires en POST
		$data = $_POST['base64data'];	

		// Vérification qu'on reçoit bien un binaire base64
		if (preg_match('/^data:image\/(\w+);base64,/', $data)) {
		
			list($type, $data) = explode(';', $data);
			list(, $type) = explode('/', $type);
			list(, $data) = explode(',', $data);
			$type = strtolower($type); // jpg, png, gif

			if (!in_array($type, $extensions_valides)) {
				throw new \Exception('Type d\'image non valide');
				echo $up_fail;
			}

			$data = base64_decode($data);

			if ($data === false) {
				throw new \Exception('Déchiffrement de l\'image échoué');
				echo $up_fail;
			}
		} else {
			throw new \Exception('Fichier invalide');			
			echo $up_fail;
		}
		
		$avatar_name = $_SESSION['pseudo'] . '_avatar.' . $type;
		file_put_contents($destination . $avatar_name, $data);
		$avatar_upload = true;	
		echo $up_succes;
		
		if($avatar_upload == true)
		{
			//On vérifie se connecte à la bdd
			include_once('../includes/bdd_connect.php');
			
			//Récupération de l'utilisateur
			$req = $bdd->prepare('SELECT uid FROM members WHERE pseudo = :pseudo');
			$req->execute(array(
			'pseudo' => $this_member));
			
			//Inscription des données récupérées dans le tableau fetch		
			$donnees = $req->fetch();
			
			//Récupération des résultats de la requête
			$fetched_uid = $donnees['uid'];
			
			//Je ferme la connexion bdd
			$req->closeCursor();
			
			$req2 = $bdd->prepare("DELETE FROM user_img WHERE uid = :uid AND img_type = 'avatar';INSERT INTO user_img(img_id, uid, img_type, img_name, thumb_name, lan_id) VALUES ('',:uid, 'avatar', :avatar_name, :avatar_name, 0)");
			$req2->execute(array(
			'uid' => $fetched_uid,
			'avatar_name' => $avatar_name));
			
			//Je ferme la connexion bdd
			$req2->closeCursor();
		}
		
	}
		  
?>