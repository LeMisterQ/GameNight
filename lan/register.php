<?php
ini_set('display_errors',1);

// Si on trouve des params post de registration, alors on affiche la page de register altérées selon les cas détectés
	if (isset($_POST['alias']) AND isset($_POST['email']) AND isset($_POST['psw']) AND isset($_POST['psw-repeat'])){

		//Je sécurise les variables
		$register_alias = htmlspecialchars($_POST['alias']);
		$length_alias = strlen($register_alias);
		$register_email = htmlspecialchars($_POST['email']);
		$length_email = strlen($register_email);
		$register_psw = htmlspecialchars($_POST['psw']);
		$length_psw = strlen($register_psw);
		$register_psw_repeat = htmlspecialchars($_POST['psw-repeat']);
		$length_psw_repeat = strlen($register_psw_repeat);

		//Je vérifie si le mdp et sa confirm sont identiques + si les longueurs maximales des champs sont bien celles que je veux avant de continuer
		if($register_psw == $register_psw_repeat AND $length_alias <= 50 AND $length_email <= 50 AND $length_psw <= 50 AND $length_psw_repeat <= 50)
		{

			//Je tente la connexion BDD
			include_once('includes/bdd_connect.php');

			//Requêtes de vérfication : user ou email existants déjà
			$req = $bdd->prepare('SELECT uid, pseudo, email FROM members WHERE pseudo = :pseudo OR email = :email');
			$req->execute(array(
				'pseudo' => $register_alias,
				'email' => $register_email));

			//Inscription des données récupérées dans le tableau fetch
			$donnees = $req->fetch();

			//Récupération des résultats de la requête
			$fetched_uid = $donnees['uid'];
			$fetched_pseudo = $donnees['pseudo'];
			$fetched_email = $donnees['email'];

			//Fin de connexion BDD
			$req->closeCursor();

			//Si on a trouvé un user existant, on affiche une page contenant les erreurs sur les champs correspondants
			if(isset($fetched_pseudo) OR isset($fetched_email))
			{
	?>

			<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

			<?php include_once('includes/header_log_reg.php'); ?>

				<body>
				<?php include_once('includes/lan_rules.php'); ?>
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
											<p>Déjà un compte ? <a href="index.php">Log toi !</a></p>
											</div>
											<div class="article_sub">
											<h1 class="registration">Devenir membre</h1>
											<hr>

											<label for="alias"><b>Alias</b></label>
											<?php if (isset($fetched_pseudo) AND $fetched_pseudo == $register_alias){
											?>
											<input class="denied_form" type="text" placeholder="Choisir un alias" name="alias" value="Cet alias existe déjà !" maxlength="50" required>
											<?php }
											else {
											?>
											<input class="init" type="text" placeholder="Choisir un alias" name="alias" maxlength="50" required>
											<?php }
											?>

											<label for="email"><b>Email</b></label>
											<?php if (isset($fetched_email) AND $fetched_email == $register_email){
											?>
											<input class="denied_form" type="text" placeholder="Entrer l'email" name="email" pattern="^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$" value="Cet email existe déjà !" maxlength="50" required>
											<?php }
											else {
											?>
											<input class="init" type="text" placeholder="Entrer l'email" name="email" pattern="^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$" maxlength="50" required>
											<?php }
											?>

											<label for="psw"><b>Mot de passe</b></label>
											<input class="init" id="psw" type="password" placeholder="Entrer le mot de passe" name="psw" maxlength="50" required>

											<label for="psw-repeat"><b>Confirmer Mot de passe</b></label>
											<input class="init" id="psw-repeat" type="password" placeholder="Confirmer le mot de passe" name="psw-repeat" maxlength="50" required>
											<hr>

											<p class="forgot_credentials">En devenant membre, tu t'engages à respecter la <a href="#">Charte de la LAN</a>.</p>
											<button type="submit" class="registerbtn" disabled>Valider</button>
											</div>
										</form>
								</section>
					</main>
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
					<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>
					<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>
				</body>
			</html>

	<?php
			}
			//Si l'utilisateur est bel et bien nouveau, alors on confirme sa demande de création de compte
			else {

				// Hachage du mot de passe
				$pass_hache = password_hash($register_psw, PASSWORD_DEFAULT);

				//Génération d'un hash random pour register mail (voir plus bas)
				$length_reg_hash = 64;
				$hash_reg = bin2hex(random_bytes($length_reg_hash));

				// Insertion en BDD de l'utilisateur et ajout de l'avatar de base
				$req = $bdd->prepare("INSERT INTO members(pseudo, pass, email, register_date, hash_register) VALUES(:pseudo, :pass, :email, CURDATE(), :hash_register);");
				$req->execute(array(
					'pseudo' => $register_alias,
					'pass' => $pass_hache,
					'email' => $register_email,
					'hash_register' => $hash_reg));

				//On recupère l'uid une nouvelle fois car il vient d'être généré
				$req2 = $bdd->prepare('SELECT uid FROM members WHERE pseudo = :pseudo OR email = :email');
				$req2->execute(array(
					'pseudo' => $register_alias,
					'email' => $register_email));

				//Inscription des données récupérées dans le tableau fetch
				$donnees2 = $req2->fetch();

				//Récupération des résultats de la requête
				$fetched_uid2 = $donnees2['uid'];

				//Création de l'avatar par défaut pour le nouvel inscrit
				//Création du rang RL par défaut pour le nouvel inscrit (Bronze 1)
				//Création des stats pour le spider chart des skills (1 partout par défaut)
				$req3 = $bdd->prepare("
				INSERT INTO `user_img`(img_id, uid, img_type, img_name, thumb_name, lan_id) VALUES ('', :uid, 'avatar', 'default_avatar.jpg', 'default_avatar.jpg', '0');
				INSERT INTO `rl_img`(rank_id, rank, subrank, rank_img, uid) VALUES ('', 'Bronze', '1', 'bronze1.png', :uid);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,0,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,1,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,2,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,3,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,4,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,5,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,6,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,7,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,8,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,9,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,10,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,11,1);
				INSERT INTO `member_skills`(`uid`, `skill_id`, `skill_value`) VALUES (:uid,12,1);
				");
				$req3->execute(array(
					'uid' => $fetched_uid2));

				//Fin de connexion BDD
				$req->closeCursor();
				$req2->closeCursor();
				$req3->closeCursor();

	?>
				<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

					<?php include_once('includes/header_log_reg.php'); ?>

					<body>
					<?php include_once('includes/lan_rules.php'); ?>
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
										<div class="article_sub registered">
										Ton inscription a été prise en compte.</br>
										N'oublie pas d'aller la <strong>confirmer</strong> grâce à l'email reçu sur ton adresse <strong><?php echo $register_email?></strong> !
										</div>
										<div class="article_sub">
										<a href="index.php">Revenir à la page de Login</a>
										</div>
									</section>
						</main>
						<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
						<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>
						<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>
					</body>
				</html>
	<?php

			//Comme sa demande de compte est confirmé, on lui envoit un mail
			include_once('includes/register_mail.php');
			}
		}
	//End IF params post reçus pour la page
	}

// Si pas de params post de registration, alors on affiche la page de register initiale
	else {
?>

		<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">

			<?php include_once('includes/header_log_reg.php'); ?>

			<body>
			<?php include_once('includes/lan_rules.php'); ?>
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
											<p>Déjà un compte ? <a href="index.php">Log toi !</a>
											</p>
										</div>
										<div class="article_sub">
										<h1 class="registration">Devenir membre</h1>
										<hr>

										<label for="alias"><b>Alias</b></label>
										<input class="init" type="text" placeholder="Choisir un alias" name="alias" maxlength="50" required>

										<label for="email"><b>Email</b></label>
										<input class="init" type="text" placeholder="Entrer l'email" name="email" pattern="^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$" maxlength="50" required>

										<label for="psw"><b>Mot de passe</b></label>
										<input class="init" id="psw" type="password" placeholder="Entrer le mot de passe" name="psw" maxlength="50" required>

										<label for="psw-repeat"><b>Confirmer Mot de passe</b></label>
										<input class="init" id="psw-repeat" type="password" placeholder="Confirmer le mot de passe" name="psw-repeat" maxlength="50" required>
										<hr>

										<p class="forgot_credentials">En devenant membre, tu t'engages à respecter la <a href="#">Charte de la LAN</a>.</p>
										<button type="submit" class="registerbtn" disabled>Valider</button>
										</div>
									</form>
							</section>
				</main>
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>
				<script src="js/pop_ups.js?<?php echo filemtime('js/pop_ups.js') ?>"></script>
			</body>
		</html>

<?php
	}
?>


