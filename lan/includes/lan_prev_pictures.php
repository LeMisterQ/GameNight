<?php
//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){

	//Nombre de LAN ayant eu lieu à !!!!!!!!!!!!!! changer à chaque LAN !!!!!!!!!!!!!!
	$lan_number = 8;
	
	//On inclue la bdd et on va chercher le chemin de l'avatar de l'utilisateur
	include_once('bdd_connect.php');
		
	//Fonction de récupération des images d'une LAN donnée
	function generate_img_list($bdd, $lan_id){

		//Tableau de stockage des images de la liste récupérée
		$this_lan_img_list = array();
		
		//Dossier des images gallery stockées
		$dossier_gal = "../img/uploads/previous_lan/";

		$reqx = $bdd->prepare("SELECT img_name FROM user_img WHERE img_type = 'gallery' AND lan_id = :lan_id");
		$reqx->execute(array(
		'lan_id' => $lan_id));
		
		//Inscription des données récupérées dans le tableau fetch
		//FETCHASSOC important car permet un accès plus malin aux données array
		$img_list = $reqx->fetchAll(PDO::FETCH_ASSOC);	
		$img_list_lenght = sizeof($img_list);
		
		for ($x = 0; $x < $img_list_lenght; $x++)
		{
			$this_created_img_div = "<div id=\"prev_lan" . $lan_id . "_pic_" . $x . "\" href=\"#\" class=\"gamesImg add\" style=\"background-image: url('" . $dossier_gal . $img_list[$x]['img_name'] . "')\"></div>";
			array_push($this_lan_img_list, $this_created_img_div);
		}
		
		//Taille du tableau d'imgs généré
		$this_lan_img_list_lenght = sizeof($this_lan_img_list);
		
		//Je concatène toutes les valeurs récupérées pour générer tous mes divs d'images les un après les autres
		for ($i = 0; $i < $img_list_lenght; $i++)
		{
			$imgs .= $this_lan_img_list[$i];
		}
		
		//echo $imgs;
		
		//Je ferme la connexion bdd
		$reqx->closeCursor();
		
		// Tableau des LANs !!!!!!!!!!!!!! changer à chaque LAN !!!!!!!!!!!!!!
		$LANs_table = array (
		'8' => "LAN W1nt€r 1s €nd1ng - Curling Dust, 01-02-03/03/2019",
		'7' => "LAN 5umm3r 535510n - N0 Jump @Gunther's, 27-28-29/07/2018",
		'6' => "LAN Sh0rt N0tice - Kara0k3 @Gunther's, 16-17-18/02/2018",
		'5' => "LAN No Whin3r- No SkillCap @Gunther's, 20-21-22/10/2018",
		'4' => "LAN GoSu LaM3R chez Cira / Parya / Jean, 21-22-23/07/2017",
		'3' => "LAN PGM + Dust CH3Z GUNTHER, 17-18-19/02/2017",
		'2' => "LAN RoXXor's CH3Z WAX, 16-17-18/12/2016",
		'1' => "Nuit du Gaming @Gunther's, 03-04-05/06/2016");
		
		$this_created_lan_header = "<header><div id=\"article_lan" . $lan_id . "\" class=\"article_other\">" . $LANs_table[$lan_id] . "</div></header>";
		$this_created_section_start = "<section id=\"section1\" class=\"lan" . $lan_id . "\">";
		$this_created_section_end = "<div id=\"add_lan" . $lan_id . "_pic\" class=\"image-upload\"><label for=\"file-input" . $lan_id . "\"><div class=\"gamesImg add\" style=\"background-image: url('" . $dossier_gal . "add.jpg')\"></div></label><input type=\"hidden\" class=\"article_profile_small\" name=\"MAX_FILE_SIZE\" value=\"50096000\"><input id=\"file-input" . $lan_id . "\" name=\"up_img_file\" type=\"file\" multiple=\"\" accept=\"image/*\" /></div></section>";		
		
		//On met tous dans la même variable, qu'on affiche ensuite en return de fonction
		$generated_lan_section = $this_created_lan_header . $this_created_section_start . $imgs . $this_created_section_end;
		echo $generated_lan_section;
		
	}

	for ($l = $lan_number; $l >= 1; $l--)
	{
	generate_img_list($bdd, $l);
	}
	
}

?>