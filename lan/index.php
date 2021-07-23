<?php
//ini_set('display_errors',1);

//On vérifie la présence de cookie de login valides
include_once('includes/cookie_connect.php');
	
// Si on trouve des params post de registration, alors on affiche la page de login altérées selon les cas détectés
	if (isset($_POST['alias']) AND isset($_POST['psw'])){

		//Je sécurise les variables
		$login_alias = htmlspecialchars($_POST['alias']);
		$login_psw = htmlspecialchars($_POST['psw']);				
		
		//  Récupération de l'utilisateur et de son pass hashé
		$req = $bdd->prepare('SELECT pseudo,pass,email,confirmed FROM members WHERE pseudo = :pseudo OR email = :pseudo');
		$req->execute(array(
			'pseudo' => $login_alias));
			
		//Inscription des données récupérées dans le tableau fetch		
		$donnees = $req->fetch();
		
		//Récupération des résultats de la requête
		$fetched_pseudo = $donnees['pseudo'];
		$fetched_email = $donnees['email'];
		$fetched_psw = $donnees['pass'];
		$fetched_confirmed = $donnees['confirmed'];

		//Fin de connexion BDD	
		$req->closeCursor();		
		
?>
		
		<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
		
			<?php include_once('includes/header_log_reg.php'); ?>

			<body>
			<?php include_once('includes/forgot_psw.php'); ?>
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
								 <form action="index.php"  method="post">
								  <div class="article_sub">
									<img src="./img/login_avatar.jpg" alt="Avatar" class="avatar">
								  </div>

								  <div class="article_sub">								  
									<label for="alias"><b>Alias</b></label>

<?php

		// Comparaison du pass envoyé via le formulaire avec la base
		$isPasswordCorrect = password_verify($login_psw, $fetched_psw);
		
		//Si psw n'est pas OK, on accepte pas le login
		if (!isset($fetched_pseudo) OR !isset($fetched_psw) OR !$isPasswordCorrect) {

?>
									<input class="denied_form" type="text" placeholder="Alias/Email" name="alias" value="Mauvais identifiant ou mot de passe !" maxlength="50" required>
									<label for="psw"><b>Mot de passe</b></label>
									<input class="denied_form" type="password" placeholder="Mot de passe" name="psw" value="" maxlength="50" required>

									<button type="submit" class="registerbtn">Login</button>
									<label>
									  <input class="init" type="checkbox" name="remember"> Rester connecté
									</label>

								  </div>

								  <div class="article_sub" style="text-align:right;margin-top:4vw;">

									<div class="forgot_credentials"><a href="#">Mot de passe</a> oublié ?</div>
								</div>
								<div class="article_sub" style="text-align:right;margin-top:4vw;">
									<div >Pas encore de compte ? <a href="register.php">Crées-en un !</a></div>
								</div>
								</form> 
							</section>							
				</main>
				<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
				<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>				
			</body>
		</html>		
		
<?php 
		}
		// Sinon, si le compte n'est pas validé, autre erreur
		elseif ($fetched_confirmed == 0) {

?>
									<input class="denied_form" type="text" placeholder="Alias/Email" name="alias" value="Compte non validé ! Check tes mails..." maxlength="50" required>
									<label for="psw"><b>Mot de passe</b></label>
									<input class="denied_form" type="password" placeholder="Mot de passe" name="psw" value="" maxlength="50" required>

									<button type="submit" class="registerbtn">Login</button>
									<label>
									  <input class="init" type="checkbox" name="remember"> Rester connecté
									</label>

								  </div>

								  <div class="article_sub" style="text-align:right;margin-top:4vw;">

									<div class="forgot_credentials"><a href="#">Mot de passe</a> oublié ?</div>
								</div>
								<div class="article_sub" style="text-align:right;margin-top:4vw;">
									<div >Pas encore de compte ? <a href="register.php">Crées-en un !</a></div>
								</div>
								</form> 
							</section>							
				</main>
				<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
				<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>				
			</body>
		</html>
<?php		
		}
		
		//Si tout est OK, on démarre la session
		else {
					
			session_start();
			$_SESSION['pseudo'] = $fetched_pseudo;
			$_SESSION['email'] = $fetched_email;				
			
			// Si checkbox remember est reçue cochée, on écrit un cookie valable 6 heures pour stocker le pseudo et le psw
			if(isset($_POST['remember'])){
				setcookie('login', $fetched_pseudo, time() + 6*3600, null, null, false, true); 
				setcookie('pass_hash', $fetched_psw, time() + 6*3600, null, null, false, true);
			}
		
			//Redirection vers la page d'Accueil
			header('Location: mainpage.php');
			exit();
			
		}												
	//End IF params post reçus pour la page	
	}
	
	//Sinon, si on trouve des params post de confirmation de registration, on affiche une page de login légèrement modifiée
	elseif (isset($_GET['email_reg']) AND isset($_GET['alias_reg']) AND isset($_GET['confirmed_ok'])) 
	{
	
		//Je sécurise les variables
		$confirmed_alias = htmlspecialchars($_GET['alias_reg']);
		$length_confirmed_alias = strlen($confirmed_alias);
		$confirmed_email = htmlspecialchars($_GET['email_reg']);
		$length_confirmed_email = strlen($confirmed_email);
		$confirmed_ok = htmlspecialchars($_GET['confirmed_ok']);
		$length_confirmed_ok = strlen($confirmed_ok);
		
		//Je vérifie si les longueurs maximales des champs sont bien celles que je veux avant de continuer
		if($length_confirmed_alias <= 50 AND $length_confirmed_email <= 50 AND $length_confirmed_ok == 1)
		{
		//Je peux afficher la page altéré
?>
			<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
		
			<?php include_once('includes/header_log_reg.php'); ?>

				<body>
				<?php include_once('includes/forgot_psw.php'); ?>
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
								<div class="article_sub registered">Inscription confirmée, c'est parti !</div>									
								<section id="section6">	
									 <form action="index.php"  method="post">
									  <div class="article_sub">
										<img src="./img/login_avatar.jpg" alt="Avatar" class="avatar">
									  </div>

									  <div class="article_sub">
										<label for="alias"><b>Alias</b></label>
										<input class="init" type="text" placeholder="Alias/Email" name="alias" maxlength="50" required>

										<label for="psw"><b>Mot de passe</b></label>
										<input class="init" type="password" placeholder="Mot de passe" name="psw" maxlength="50" required>

										<button type="submit" class="registerbtn">Login</button>
										<label>
										  <input class="init" type="checkbox" name="remember"> Rester connecté
										</label>
									  </div>

									  <div class="article_sub" style="text-align:right;margin-top:4vw;">

										<div class="forgot_credentials"><a href="#">Mot de passe</a> oublié ?</div>
									</div>
									<div class="article_sub" style="text-align:right;margin-top:4vw;">
										<div >Pas encore de compte ? <a href="register.php">Crées-en un !</a></div>
									</div>
									</form> 
								</section>							
					</main>
					<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
					<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>				
				</body>
			</html>
			
<?php		
		}
		else {
		//On affiche une page avec une indication que la validation du compte a échoué
?>	

			<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
		
			<?php include_once('includes/header_log_reg.php'); ?>

				<body>
				<?php include_once('includes/forgot_psw.php'); ?>
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
								<div class="article_sub unregistered">La confirmation n'as pas pu être effectuée. Merci de contacter le LAN Master !</div>									
								<section id="section6">	
									 <form action="index.php"  method="post">
									  <div class="article_sub">
										<img src="./img/login_avatar.jpg" alt="Avatar" class="avatar">
									  </div>

									  <div class="article_sub">
										<label for="alias"><b>Alias</b></label>
										<input class="init" type="text" placeholder="Alias/Email" name="alias" maxlength="50" required>

										<label for="psw"><b>Mot de passe</b></label>
										<input class="init" type="password" placeholder="Mot de passe" name="psw" maxlength="50" required>

										<button type="submit" class="registerbtn">Login</button>
										<label>
										  <input class="init" type="checkbox" name="remember"> Rester connecté
										</label>
									  </div>

									  <div class="article_sub" style="text-align:right;margin-top:4vw;">

										<div class="forgot_credentials"><a href="#">Mot de passe</a> oublié ?</div>
									</div>
									<div class="article_sub" style="text-align:right;margin-top:4vw;">
										<div >Pas encore de compte ? <a href="register.php">Crées-en un !</a></div>
									</div>
									</form> 
								</section>							
					</main>
					<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
					<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>				
				</body>
			</html>
	
<?php	
		}
	
	}
	
	// Sinon, si pas de params post de login, alors on affiche la page de login initiale
	else {
		
?>
			
		<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
		
			<?php include_once('includes/header_log_reg.php'); ?>

			<body>
			<?php include_once('includes/forgot_psw.php'); ?>
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
							<?php
							// Si on reçoit un post du serveur car demande de réinitialisation du mot de passe, on met la confirmation ici
							if(isset($_GET['psw_asked']))
							{
								$psw_asked = htmlspecialchars($_GET['psw_asked']);
								if($psw_asked == 1)
								{
							?>
							<div class="article_sub registered">Demande de nouveau mot de passe effectuée. Un email pour le renouveler t'a été envoyé.</div>
							<?php
								}
							}
							?>
							<section id="section6">	
								 <form action="index.php"  method="post">
								  <div class="article_sub">
									<img src="./img/login_avatar.jpg" alt="Avatar" class="avatar">
								  </div>

								  <div class="article_sub">
									<label for="alias"><b>Alias</b></label>
									<input class="init" type="text" placeholder="Alias/Email" name="alias" maxlength="50" required>

									<label for="psw"><b>Mot de passe</b></label>
									<input class="init" type="password" placeholder="Mot de passe" name="psw" maxlength="50" required>

									<button type="submit" class="registerbtn">Login</button>
									<label>
									  <input class="init" type="checkbox" name="remember"> Rester connecté
									</label>
								  </div>

								  <div class="article_sub" style="text-align:right;margin-top:4vw;">

									<div class="forgot_credentials"><a href="#">Mot de passe</a> oublié ?</div>
								</div>
								<div class="article_sub" style="text-align:right;margin-top:4vw;">
									<div >Pas encore de compte ? <a href="register.php">Crées-en un !</a></div>
								</div>
								</form> 
							</section>							
				</main>
				<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
				<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>				
			</body>
		</html>

<?php
	}
?>