<?php 
ini_set('display_errors',1);

//original submit form to php
//<button type="submit" name="submit" class="registerbtn">Uploader</button>

//original input field
//<input type="file" id="up_avatar_file" class="article_profile_small" name="up_avatar_file" /><br />

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
				<header class="header_profile">
					<div id="avatar_editition" class="article_profile_other">
						<div class="article_profile_sub">Upload un nouvel avatar 
							<form id="img_submit" enctype="multipart/form-data">
								<label class="article_profile_small" for="mon_fichier">Image (JPG, PNG ou GIF | max. 4 Mo) :</label><br />
								<input type="hidden" class="article_profile_small" name="MAX_FILE_SIZE" value="4096000" />
								<input type="file" id="up_avatar_file" multiple accept="image/*" name="up_avatar_file" style="display:none">
								<button id="fileSelect" class="edition-green choose">Choisir un fichier</button>								
							</form>
						</div>
						<div id ="img_cropper" class="img_cropper" style="opacity:0">
							<div class="btn_cropper">
								<button id="edit" class="edition-green" >Éditer</button>
								<button id="cancel" class="edition-red" >Annuler édition</button>								
							</div>
							<div id="activities_desc"><?php echo $_SESSION['pseudo']; ?></div>							
							<div id="img_init" class="img_init" >
									<button type="submit" class="registerbtn">No use</a></button>
							</div>
							<button id="crop" class="edition-green" >Valider édition</button>
							<hr>
							<div id="img_thumbnail" class="article_profile_small" style="display:none"></div>
							<button id="fileSubmit" class="registerbtn">Uploader l'avatar</button>							
						</div>
						<div class="article_profile_sub">
							<div class="statusMsg"></div>
						</div>						
					</div>					
				</header>					
		</main>
		<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
		<script src="/js/load-image.all.min.js?<?php echo filemtime('js/load-image.all.min.js') ?>"></script>
		<script src="/js/img_cropper.js?<?php echo filemtime('js/img_cropper.js') ?>"></script>	
		<script src="/js/jquery.Jcrop.js?<?php echo filemtime('js/jquery.Jcrop.js') ?>"></script>
		<script src="/js/hidden_input_files.js?<?php echo filemtime('js/hidden_input_files.js') ?>"></script>
		<script src="/js/send_files.js?<?php echo filemtime('js/send_files.js') ?>"></script>			
	</body>
</html>

<?php
	}
?>




  
	
  
  

