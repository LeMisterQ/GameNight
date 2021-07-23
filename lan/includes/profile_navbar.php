<?php
//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

//Récupération de l'avatar utilisateur
$req = $bdd->prepare("SELECT img_name FROM user_img WHERE uid IN (SELECT uid from members WHERE pseudo = :pseudo) AND img_type='avatar'");
$req->execute(array(
'pseudo' => $_SESSION['pseudo']));

//Inscription des données récupérées dans le tableau fetch		
$donnees = $req->fetch();

//Récupération des résultats de la requête
$fetched_img_name = $donnees['img_name'];

//Fermeture de la connexion à la base
$req->closeCursor();

//Si aucune image de setup, alors on va prendre l'image par défaut en base (user 56)
if($fetched_img_name == null OR $fetched_img_name == "")
{
	$req1 = $bdd->prepare("SELECT img_name FROM user_img WHERE uid IN (SELECT uid from members WHERE pseudo = 'default') AND img_type='avatar'");
	$req1->execute();
	$donnees1 = $req1->fetch();

	//Récupération des résultats de la requête
	$fetched_img_name = $donnees1['img_name'];
}

//Lieu de stockage des images uploadées
$destination = '../img/uploads/';

//Récupération de l'utilisateur
$req2 = $bdd->prepare('SELECT uid,title FROM members WHERE pseudo = :pseudo');
$req2->execute(array(
'pseudo' => $_SESSION['pseudo']));

//Inscription des données récupérées dans le tableau fetch		
$donnees2 = $req2->fetch();

//Récupération des résultats de la requête
$fetched_uid = $donnees2['uid'];

$req2->closeCursor();

//Fetch du rank RL
$req3 = $bdd->prepare('SELECT rank_img FROM rl_img WHERE uid = :uid');
$req3->execute(array(
'uid' => $fetched_uid));

$donnees3 = $req3->fetch();
$fetched_rank_img = $donnees3['rank_img'];
$rl_dossier_img = "img/rl/";
$req3->closeCursor();

?>

<div class="global_avatar_container">
	<div class="avatar_container">
		<div class="avatar">
			<a href="profile.php#header_profile">
				<img class="avatar_img" <?php echo 'src="' . $destination . $fetched_img_name . '" alt="' . $_SESSION['pseudo'] . '"'; ?> /></img>
			</a>
		</div>
		<img class="avatar_rl_img" <?php echo 'src="' . $rl_dossier_img . $fetched_rank_img . '" alt="Rang"'; ?> /></img>
		<h1><?php echo $_SESSION['pseudo']; ?></h1>				
		<div class="avatar_content">
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="mainpage.php#locateLAN"><img class="cell_img" src="../img/pointer_map96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="mainpage.php#locateLAN">C'est où ?</a></div>
			</div>		
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="mainpage.php#section1"><img class="cell_img" src="../img/controller96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="mainpage.php#section1">Jeux</a></div>
			</div>			
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="profile.php#header_profile"><img class="cell_img" src="../img/customer96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="profile.php#header_profile">Mon Profil</a></div>
			</div>
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="gallery.php"><img class="cell_img" src="../img/gallery96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="gallery.php#main_gal">Gallerie</a></div>
			</div>			
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="members.php"><img class="cell_img" src="../img/members96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="members.php#main_memb">Membres</a></div>
			</div>
			<div class="avatar_rows hvr-sweep-to-right">
				<div class="cell_menu"><a href="disconnect.php"><img class="cell_img" src="../img/disconnect96.png"></img></a></div>
				<div class="cell_content"><a class="cell_link" href="disconnect.php">Déconnexion</a></div>
			</div>			
		</div>
	</div>	
	<div class="up_down">
		<img class="fleche" src="../img/arrowu100.png"></img>
	</div>	
</div>
<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
<script src="js/navbar.js?<?php echo filemtime('js/navbar.js') ?>"></script>