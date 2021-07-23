<?php
	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once( 'config.php' );
	
	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');

	// Suppression des variables de session et de la session
	// setcookie('login', '', time()-3600);
	// setcookie('pass_hash', '', time()-3600);

	//Si la session existe bien, on supprime la team et on se déconnecte
	if($_SESSION['teamname']){
		
		session_destroy();

	}

	//Redirection vers la page d'Accueil
	header('Location: /index.php');
	
?>