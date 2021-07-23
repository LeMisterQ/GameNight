<?php
	//Je tente la connexion BDD
	try {
		$bdd = new PDO('mysql:host=localhost; dbname=gamenight;charset=utf8', 'adminweb', 'VjoqoV', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	} catch (PDOException $e) {
		echo 'Connexion échouée : ' . $e->getMessage();
	}
?>			