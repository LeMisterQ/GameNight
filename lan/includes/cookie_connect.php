<?php
//ini_set('display_errors',1);

session_start();

//Je tente la connexion BDD
include_once('includes/bdd_connect.php');

// echo 'session pseudo = ' . $_SESSION['pseudo'];
// echo 'cookie login = ' . $_COOKIE['login'];
// echo 'cookie pwd = ' . $_COOKIE['pass_hash'];

//On vérifie si une session PHP existe déjà et si des cookies sont là et complétés afin de pouvoir se souvenir de la session user
if(!isset($_SESSION['pseudo']) AND isset($_COOKIE['login'], $_COOKIE['pass_hash']) AND !empty($_COOKIE['login']) AND !empty($_COOKIE['pass_hash']))
{
	//Je sécurise les variables
	$cookie_login = htmlspecialchars($_COOKIE['login']);
	$cookie_pwd = htmlspecialchars($_COOKIE['pass_hash']);
		
	//Récupération de l'utilisateur grâce au cookie
	$req = $bdd->prepare('SELECT pseudo,pass,email FROM members WHERE pseudo = :pseudo OR email = :pseudo');
	$req->execute(array(
		'pseudo' => $cookie_login));
		
	//Inscription des données récupérées dans le tableau fetch		
	$donnees = $req->fetch();
	
	//Récupération des résultats de la requête
	$fetched_pseudo = $donnees['pseudo'];
	$fetched_email = $donnees['email'];
	$fetched_psw = $donnees['pass'];

	//Fin de connexion BDD	
	$req->closeCursor();
		
	//Si psw n'est pas OK, on accepte pas le login
	if ($fetched_psw == $cookie_pwd) {
	
		// On reconnecte le user avec les infos issues de la BDD
		$_SESSION['pseudo'] = $fetched_pseudo;
		$_SESSION['email'] = $fetched_email;
		echo 'Go session auto!';
		
		// On récrit des cookies valables 6 heures
		setcookie('login', $fetched_pseudo, time() + 6*3600, null, null, false, true); 
		setcookie('pass_hash', $fetched_psw, time() + 6*3600, null, null, false, true);
		
		//Redirection vers la page d'Accueil
		header('Location: mainpage.php');
		exit();
	
	}	
	else{
	
	}
}		
?>