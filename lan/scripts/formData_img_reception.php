<?php

ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

//Limitation du nombre d'image par personne
$img_count_limit = 50;

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
	
		$this_member = $_SESSION['pseudo'];

		if (isset($_POST['whichLan'])){
		
			$whichLan = $_POST['whichLan'];
		
			//On inscrit l'image en BDD pour qu'elle soit affichée automatiquement
			//On vérifie se connecte à la bdd
			include_once('../includes/bdd_connect.php');
			
			//Récupération de l'utilisateur
			$req = $bdd->prepare('SELECT uid FROM members WHERE pseudo = :pseudo');
			$req->execute(array(
			'pseudo' => $this_member));
			
			//Inscription des données récupérées dans le tableau fetch		
			$donnees = $req->fetch();
			
			//Récupération des résultats de la requête
			$fetched_uid = $donnees['uid'];
			
			//Je ferme la connexion bdd
			$req->closeCursor();
			
			//Check le compteur pour l'uid
			$req1 = $bdd->prepare("SELECT stat_value FROM member_stats WHERE uid = :uid AND stat_type = 'img_counter'");
			$req1->execute(array(
			'uid' => $fetched_uid));

			//Inscription des données récupérées dans le tableau fetch		
			$donnees1 = $req1->fetch();
			
			$req1->closeCursor();
			
			//Récupération du compteur d'imgs uploadées
			$fetched_stat_value = $donnees1['stat_value'];						
			
			//Si le compteur n'existe pas, on créé la ligne en bdd pour l'uid
			if( empty($fetched_stat_value))
			{
				
			//Check le compteur pour l'uid
			$req11 = $bdd->prepare("INSERT INTO member_stats (stat_id, uid, stat_type, stat_value) VALUES ('', :uid, 'img_counter', 0)");
			$req11->execute(array(
			'uid' => $fetched_uid));	

			$req11->closeCursor();
			
			//On set la variable à 0 du coup
			$fetched_stat_value = 0;
			}

			if($fetched_stat_value <= $img_count_limit)
			{
			
				function upload($index,$destination,$maxsize,$extensions_valides)
				{
				   //Test1: fichier correctement uploadé
					 if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
				   //Test2: taille limite
					 if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
				   //Test3: extension
					 $ext = substr(strrchr($_FILES[$index]['name'],'.'),1);
					 if ($extensions !== FALSE AND !in_array($ext,$extensions_valides)) return FALSE;
				   //Déplacement, mais on check la longueur du nom de l'image
					if(strlen($_FILES[$index]['name']) <= 255){
					 return move_uploaded_file($_FILES[$index]['tmp_name'], $destination . $_FILES[$index]['name']);
					}
					else{
					echo "Nom de fichier trop long";
					}
				}
				 
				//Constantes
				$original_img_name = $_FILES['image']['name'];				
				$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
				$maxsize = 10096000;
				$destination = '../img/uploads/previous_lan/';
				
				//Lancement de la fonction
				$upload_img = upload('image', $destination, $maxsize, $extensions_valides);

				//Lorsque upload fait
				if ($upload_img) {
				
					//On crée la miniature qui sera affichée dans la Gallery (gain de perf)
					$imgfile = $original_img_name;
					$imgfile_thumb = "mini_" . $imgfile;
					$destination = "../img/uploads/previous_lan/";
					$thumbdir = "../img/uploads/previous_lan/thumbnails/";

					list($old_width, $old_height) = getimagesize($destination . $imgfile);
					
					$new_width = $old_width/10;
					$new_height = $old_height/10;
					
					$new_image = imagecreatetruecolor($new_width, $new_height);
					$old_image = imagecreatefromjpeg($destination . $imgfile);

					imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

					imagejpeg($new_image, $thumbdir . $imgfile_thumb);
					imagedestroy($old_image);
					imagedestroy($new_image);
					
					//On inscrit la référence aux miniatures et img complète en bdd
					$req2 = $bdd->prepare("
					INSERT INTO user_img(img_id, uid, img_type, img_name, thumb_name, lan_id) VALUES ('', :uid, 'gallery', :img_name, :img_thumb, :lan_id);
					UPDATE member_stats SET stat_value = stat_value + 1 WHERE uid = :uid AND stat_type = 'img_counter';
					");
					$req2->execute(array(
					'uid' => $fetched_uid,
					'lan_id' => $whichLan,
					'img_name' => $original_img_name,
					'img_thumb' => $imgfile_thumb));
					
					//Je ferme la connexion bdd
					$req2->closeCursor();
					
					$up_succes = "Success";
					//Code de succès renvoyé
					echo $up_succes;
				}
				else {
					$up_fail = "L'upload de l'image a échoué...";
				echo $up_fail;
				}	
			}
			else {
				$up_fail = "Tu as déjà uploadé trop d'images !";
				echo $up_fail;
			}

		}	
		else {
			echo "Num de LAN invalide";
		}		
	}
	else {
			$up_fail = "Pas de session détectée. Reconnecte-toi avant d'essayer à nouveau...";
			echo $up_fail;
	}
		  
?>