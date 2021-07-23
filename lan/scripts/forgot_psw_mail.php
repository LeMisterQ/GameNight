<?php

// Je vérifie que les champs nécessaires sont bien dispos avant d'envoyer le mail
if(isset($_POST['forget_email']))
{

	$forgot_email = $_POST['forget_email']; // Déclaration de l'adresse de destination.
	if (!preg_match("^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$", $forgot_email)) // On check si le post reçu est bien un email valide
	{
		if (!preg_match("#^[A-z0-9._-]+@(hotmail|live|msn).[A-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
		{
			$passage_ligne = "\r\n";
		}
		else
		{
			$passage_ligne = "\n";
		}

		//=====Déclaration des messages au format texte et au format HTML.
		$link_insert = '<a href="https://homeq.fr/psw_change.php?forgot_email=' . $forgot_email . '" target="_blank">Changer mon mot de passe</a>';
		$message_txt = "Salut à toi, ";
		$message_html = '<html><head></head><body>Alors, on a oublié son <b>mot de passe</b> ?<br />Tu peux en générer un nouveau en utilisant le lien ci dessous.<br />' . $link_insert . '</body></html>';
		//==========
		 
		//=====Création de la boundary
		$boundary = "-----=".md5(rand());
		//==========
		 
		//=====Définition du sujet.
		$sujet = "LAN Event - Changer le mot de passe";
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
		mail($_POST['forget_email'],$sujet,$message,$header);
		//==========
		
		echo "Success";
	}
}
?>
