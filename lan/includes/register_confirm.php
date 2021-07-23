<?php
//ini_set('display_errors',1);

// Je vérifie que les champs nécessaires sont bien dispos avant de continuer
if(isset($_GET['email_reg']) AND isset($_GET['alias_reg']) AND isset($_GET['hash_reg']))
{

	//Je sécurise les variables
	$confirmed_alias = htmlspecialchars($_GET['alias_reg']);
	$length_confirmed_alias = strlen($confirmed_alias);
	$confirmed_email = htmlspecialchars($_GET['email_reg']);
	$length_confirmed_email = strlen($confirmed_email);
	$confirmed_hash_reg = htmlspecialchars($_GET['hash_reg']);
	$length_confirmed_hash_re = strlen($confirmed_hash_reg);
	
	//Je vérifie si les longueurs maximales des champs sont bien celles que je veux avant de continuer
	if($length_confirmed_alias <= 50 AND $length_confirmed_email <= 50 AND $length_confirmed_hash_re >= 64)
	{		

		//Je tente la connexion BDD
		include_once('bdd_connect.php');

		//Requêtes de vérfication : user existant
		$req = $bdd->prepare('SELECT pseudo, email, hash_register, confirmed FROM members WHERE pseudo = :pseudo AND email = :email AND hash_register = :hash_register');
		$req->execute(array(
				'pseudo' => $confirmed_alias,
				'email' => $confirmed_email,
				'hash_register' => $confirmed_hash_reg));	
		
		//Je check si l'utilisateur existe avec les params GET reçus. Si oui on peut continuer.
		$user_exist = $req->rowCount();		
		if($user_exist == 1)
		{
		
		//On vérifie que le compte n'est pas déjà validé			
			//Inscription des données récupérées dans le tableau fetch		
			$donnees = $req->fetch();
			
			//Récupération des résultats de la requête
			$fetched_confirmed = $donnees['confirmed'];
			
			if($fetched_confirmed == 0)
			{
				$confirmed_value = 1; //1 valide le compte en BDD
				
				$req = $bdd->prepare('UPDATE members SET confirmed = :confirmed_ok WHERE pseudo = :pseudo AND email = :email AND hash_register = :hash_register');
				$req->execute(array(
				'pseudo' => $confirmed_alias,
				'email' => $confirmed_email,
				'hash_register' => $confirmed_hash_reg,
				'confirmed_ok' => $confirmed_value));
				
				//User confirmé, redirection vers login
				header('Location: ../index.php?email_reg='. $confirmed_email . '&alias_reg=' . $confirmed_alias . '&confirmed_ok=' . $confirmed_value);
				exit();				
			}
			else{
			
			//User a déjà validé, redirection vers login
			header('Location: ../index.php');
			exit();
			
			}
		}
		
		else{
		
		//User n'existe pas, redirection vers register
		header('Location: ../register.php');
		exit();
		
		}
		
		//Fin de connexion BDD	
		$req->closeCursor();		
	}
}	
?>