<?php
ini_set('display_errors',1);

if (isset($_POST['forget_email'])){

	//Je sécurise les variables
	$forget_email = htmlspecialchars($_POST['forget_email']);
	$length_forget_email = strlen($forget_email);				
	
	//Je vérifie si les longueurs maximales des champs sont bien celles que je veux avant de continuer
	if($length_forget_email <= 50)
	{
	
		//Je tente la connexion BDD
		include_once('bdd_connect.php');
			
		//Récupération de l'utilisateur et de son pass hashé
		$req = $bdd->prepare('SELECT pseudo, email FROM members WHERE email = :email');
		$req->execute(array(
			'email' => $forget_email));
		
		//Je check si l'utilisateur existe avec les params GET reçus. Si oui on peut continuer.
		$user_exist = $req->rowCount();		
		if($user_exist == 1)
		{
				
			//Inscription des données récupérées dans le tableau fetch		
			$donnees = $req->fetch();
			
			//Récupération des résultats de la requête
			$fetched_pseudo = $donnees['pseudo'];
			$fetched_email = $donnees['email'];

			//Fin de connexion BDD	
			$req->closeCursor();
			
			//On démarre l'envoi d'un email à admin
			$admin_mail = 'contact@homeq.fr';
			$mail = $admin_mail; // Déclaration de l'adresse de destination.
			
			if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
			{
				$passage_ligne = "\r\n";
			}
			else
			{
				$passage_ligne = "\n";
			}
			//=====Déclaration des messages au format texte et au format HTML.
			$message_txt = "Salut l'Admin,";
			$message_html = '<html><head></head><body>Il semble que <b>' . $fetched_pseudo . '</b> a perdu son mot de passe...<br />Voici l\'adresse email associée : ' . $fetched_email . '.<div>Good luck !</div></body></html>';
			//==========
			 
			//=====Création de la boundary
			$boundary = "-----=".md5(rand());
			//==========
			 
			//=====Définition du sujet.
			$sujet = "LAN Event - Mot de passe perdu !";
			//=========
			 
			//=====Création du header de l'e-mail.
			$header = "From: \"LAN Master\"<contact@homeq.fr>".$passage_ligne;
			$header.= "Reply-to: \"LAN Master\" <contact@homeq.fr>".$passage_ligne;
			$header.= "MIME-Version: 1.0".$passage_ligne;
			$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;
			//==========
			 
			//=====Création du message.
			$message = $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format texte.
			$message.= "Content-Type: text/plain; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_txt.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary.$passage_ligne;
			//=====Ajout du message au format HTML
			$message.= "Content-Type: text/html; charset=\"ISO-8859-1\"".$passage_ligne;
			$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
			$message.= $passage_ligne.$message_html.$passage_ligne;
			//==========
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			$message.= $passage_ligne."--".$boundary."--".$passage_ligne;
			//==========
			 
			//=====Envoi de l'e-mail.
			mail($mail,$sujet,$message,$header);
			//==========
			
			//Redirection vers la page d'Accueil
			header('Location: ../index.php?psw_asked=1');
			exit();
		}
		header('Location: ../register.php');
		exit();
	}
	header('Location: ../register.php');
	exit();
}
header('Location: ../register.php');
exit();		
?>