<?php 
session_start();

// Suppression des variables de session et de la session
// setcookie('login', '', time()-3600);
// setcookie('pass_hash', '', time()-3600);

$_SESSION = array();
session_destroy();

//Redirection vers la page d'Accueil
header('Location: register.php');
exit();
?>