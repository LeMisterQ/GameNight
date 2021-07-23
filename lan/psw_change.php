<?php
ini_set('display_errors',1);

if ( !isset($_POST['psw']) AND !isset($_POST['psw-repeat']) )
{
	
	// Si je reçois un GET de psw, alors il faut que je propose de regénérer le psw
	if( isset($_GET['forgot_email']) )
	{
		//Je sécurise les variables
		$confirmed_email = htmlspecialchars($_GET['forgot_email']);
		$length_confirmed_email = strlen($confirmed_email);

		//Je vérifie si les longueurs maximales des champs sont bien celles que je veux avant de continuer
		if( $length_confirmed_email <= 50 )
		{
		//J'affiche la page de regénération du mot de passe
?>	

			<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

				<?php include_once('includes/header_log_reg.php'); ?>
				
				<body>			
					<main class="boite_principale" >
								<header>
									<div class="article_other">
									<?php
									include('includes/lan_name.php');
									?>
									</div>
								<div class="article_sub">
									<?php
									include_once('includes/lan_event_number.php');
									?>
								</div>						
								</header>
								<section id="section6">	
										<form action="psw_change.php" method="post">
											<div class="article_sub">								
												<hr>
												
												<label for="email_confirmed"><b>Email du compte</b></label>
												<input class="init" id="email_confirmed" type="text" name="email_confirmed" maxlength="50" value="<?php echo $confirmed_email ?>" readonly required>
												
												<label for="psw"><b>Mot de passe</b></label>
												<input class="init" id="psw" type="password" placeholder="Entrer le mot de passe" name="psw" maxlength="50" required>

												<label for="psw-repeat"><b>Confirmer Mot de passe</b></label>
												<input class="init" id="psw-repeat" type="password" placeholder="Confirmer le mot de passe" name="psw-repeat" maxlength="50" required>
												<hr>											
												<button type="submit" class="registerbtn" disabled>Valider</button>
											</div>
										</form> 
								</section>							
					</main>
					<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>	
					<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>	
					<link rel="stylesheet" href="css/mainpage.css?<?php echo filemtime('css/mainpage.css') ?>"/>
					<link rel="stylesheet" href="css/pop_ups.css?<?php echo filemtime('css/pop_ups.css') ?>"/>				
				</body>
			</html>

<?php
		}
		else
		{
			echo "Erreur, merci d'essayer à nouveau !";
		}
	}
	else
	{
		echo "Erreur, merci d'essayer à nouveau !";
	}
}	
// Sinon, si je reçois un post du form de renouvellement de psw, alors je le process
else if ( isset($_POST['psw']) AND isset($_POST['psw-repeat']) AND isset($_POST['email_confirmed']))
{

	//Je sécurise les variables
	$confirmed_email = htmlspecialchars($_POST['email_confirmed']);
	$register_psw = htmlspecialchars($_POST['psw']);
	$length_psw = strlen($register_psw);
	$register_psw_repeat = htmlspecialchars($_POST['psw-repeat']);
	$length_psw_repeat = strlen($register_psw_repeat);
	
	//Je vérifie si le mdp et sa confirm sont identiques + si les longueurs maximales des champs sont bien celles que je veux avant de continuer
	if($register_psw == $register_psw_repeat AND $length_psw <= 50 AND $length_psw_repeat <= 50)
	{
		
		// Hachage du mot de passe
		$pass_hache = password_hash($register_psw, PASSWORD_DEFAULT);		
		
		//Je tente la connexion BDD
		include_once('includes/bdd_connect.php');
/*
		echo $confirmed_email;
		echo $register_psw;
		echo $pass_hache;
		*/
		
		// Insertion en BDD de l'utilisateur et ajout de l'avatar de base
		$req = $bdd->prepare("UPDATE members SET pass = :pass WHERE email = :email");
		$req->execute(array(
			'email' => $confirmed_email,
			'pass' => $pass_hache));
		
		//Fin de connexion BDD	
		$req->closeCursor();
?>

<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

		<?php include_once('includes/header_log_reg.php'); ?>
		
			<body>
				<main class="boite_principale" >
							<header>
								<div class="article_other">
									<?php
									include('includes/lan_name.php');
									?>
									</div>
								<div class="article_sub">
									<?php
									include_once('includes/lan_event_number.php');
									?>
								</div>						
							</header>
							<section id="section6">	
									<form action="register.php" method="post">

										<div class="article_sub">
																		
											<hr>

											<label for="psw"><b>Mot de passe</b></label>
											<input class="init granted_form"" id="psw" type="password" placeholder="Entrer le mot de passe" name="psw" maxlength="50" required>

											<label for="psw-repeat"><b>Confirmer Mot de passe</b></label>
											<input class="init granted_form" id="psw-repeat" type="password" placeholder="Confirmer le mot de passe" name="psw-repeat" maxlength="50" required>
											<hr>

											<div class="statusMsg"><span style="font-size:inherit;color:#34A853">Mot de passe changé avec succès !</span></div>
											<div class="article_sub">
												<p><a href="../index.php">Se connecter</a> au site de la LAN</p>
											</div>

										</div>
									</form> 
							</section>							
				</main>
					<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>	
					<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>	
					<link rel="stylesheet" href="css/mainpage.css?<?php echo filemtime('css/mainpage.css') ?>"/>
					<link rel="stylesheet" href="css/pop_ups.css?<?php echo filemtime('css/pop_ups.css') ?>"/>					
			</body>
		</html>

<?php
		
	}
}
	
else
{

echo "Erreur dans l'affichage de la page de renouvellement de mot de passe...";

}
?>