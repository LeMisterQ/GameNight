<?php
ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (!isset($_SESSION['pseudo']) OR !isset($_SESSION['email'])){
	
	//On inclue la page de please connect car pas de session
	include_once('includes/please_connect.php');
	
	}
	else {
	
			//On inclue la bdd et on va chercher le chemin de l'avatar de l'utilisateur
			include_once('includes/bdd_connect.php');	
			
			//Récupération de l'utilisateur
			$req = $bdd->prepare('SELECT uid,title FROM members WHERE pseudo = :pseudo');
			$req->execute(array(
			'pseudo' => $_SESSION['pseudo']));
			
			//Inscription des données récupérées dans le tableau fetch		
			$donnees = $req->fetch();
			
			//Récupération des résultats de la requête
			$fetched_title = $donnees['title'];
			$fetched_uid = $donnees['uid'];

			$req->closeCursor();
			
			if(!isset($fetched_title))
			{
			$fetched_title = 'Pas encore de titre choisi !';
			}
			
			//Fetch du rank RL
			$req2 = $bdd->prepare('SELECT rank, subrank, rank_img FROM rl_img WHERE uid = :uid');
			$req2->execute(array(
			'uid' => $fetched_uid));
			
			$donnees2 = $req2->fetch();
			
			$fetched_rank_img = $donnees2['rank_img'];
			$fetched_rank = $donnees2['rank'];
			$fetched_subrank = $donnees2['subrank'];
			$rl_dossier_img = "img/rl/";
			
			$req2->closeCursor();
			
?>	
	
	<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
	
	<?php include_once('includes/header_main.php'); ?>
	
		<body>
			<?php 
			include_once('includes/profile_navbar.php');
			?>		
		<main class="boite_principale" >
					<section id="section0">
						<div id="article_0"></div>
						<div id="article_header">
							<?php
							include('includes/lan_name.php');
							echo('<br>');
							include_once('includes/lan_event_number.php');
							?>
							<p id="demo"></p>
						</div>
					</section>
					<header id ="header_profile" class="header_profile">
						<div class="article_profile_other"> 
							<?php echo $_SESSION['pseudo']; ?> 
							<div class="article_profile_sub">Photo de profil</div>
							<div class="article_profile_sub">
								<img class="img_profile" src=<?php echo '"' . $destination . $fetched_img_name . '"'?> alt="Avatar"></img>
								<form action="profile_crop.php#avatar_editition">
									<button type="submit" class="registerbtn">Éditer</button>
								</form>
							</div>
						</div>					
						<div id="my_title" class="article_profile_other">Mon titre
							<div class="article_profile_sub"><?php echo $fetched_title; ?></div>
							<form id="form_edit_title">
								<button type="button" id="edit_title" class="registerbtn">Éditer</button>
							</form>
						</div>

						<div class="article_profile_other">Mon rang
							<div class="article_profile_sub"><?php echo $fetched_rank . " " . $fetched_subrank; ?></div>
							<img class="img_rl" src=<?php echo '"' . $rl_dossier_img . $fetched_rank_img . '"'?> alt="Rang Rocket League"></img>
						</div>
						<div class="article_profile_other">Mes infos Steam</div>
						<div class="article_profile_sub">à venir si tu mets ton profil Steam en "Public"</div>
					</header>					
			</main>						
		</body>
		<script	src="js/edit_title.js?<?php echo filemtime('js/edit_title.js') ?>"></script>
	</html>
	
<?php
	}
	/*<?php include_once('includes/title_dropdowns.php'); ?>*/
?>
