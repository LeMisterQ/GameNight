<?php
//Je tente la connexion BDD
try {

	$bdd = new PDO('mysql:host=homeqfriuhquant1.mysql.db;dbname=homeqfriuhquant1;charset=utf8', 'homeqfriuhquant1', '6azhF4i0LrNUa7HXfJF6', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

} catch (PDOException $e) {

	echo 'Connexion échouée : ' . $e->getMessage();

}
?>			