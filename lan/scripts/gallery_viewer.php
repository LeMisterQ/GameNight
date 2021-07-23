<?php

ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
	
		if (isset($_POST['clicked_content_path'])){
		
			//Je sécurise les variables
			if (preg_match('/jpg|jpg|png|gif|mp4|mpeg$/', $_POST['clicked_content_path']))
			{
			$received_content_path = $_POST['clicked_content_path'];
			}
		}
	}
?>
	
<div id="myModal" class="modal_viewer">
	<span class="close_viewer">×</span>
	<div class="modal-content_viewer">
		<img id="displayed_content" class="displayed_content" src="<?php echo $received_content_path ?>">
	</div>
</div>
<script src="js/content_viewer.js?<?php echo filemtime('../js/content_viewer.js') ?>"></script>