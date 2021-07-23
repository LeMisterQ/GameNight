<?php

// Je vérifie que les champs nécessaires sont bien dispos avant d'envoyer le mail
if(isset($register_email, $register_alias, $hash_reg))
{

	$mail = $register_email; // Déclaration de l'adresse de destination.
	if (!preg_match("#^[A-z0-9._-]+@(hotmail|live|msn).[A-z]{2,4}$#", $mail)) // On filtre les serveurs qui rencontrent des bogues.
	{
		$passage_ligne = "\r\n";
	}
	else
	{
		$passage_ligne = "\n";
	}
	//=====Déclaration des messages au format texte et au format HTML.
	$link_insert = '<a href="https://homeq.fr/includes/register_confirm.php?email_reg='. $register_email . '&alias_reg=' . $register_alias . '&hash_reg=' . $hash_reg . '" target="_blank">Confirmer mon inscription</a>';
	$message_txt = "Salut à toi, " . $register_alias;
	$message_html = '<html><head></head><body>Salut à toi, <b>' . $register_alias . '</b> !<br />Tu peux confirmer ta demande d\'inscription au site de <i>la LAN</i> en utilisant le lien ci dessous.<br />' . $link_insert . '</body></html>';
	//==========
	 
	//=====Création de la boundary
	$boundary = "-----=".md5(rand());
	//==========
	 
	//=====Définition du sujet.
	$sujet = "LAN Event - Confirmation de l'inscription";
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

}
?>
