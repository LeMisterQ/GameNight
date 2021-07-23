<?php

//ini_set('display_errors',1);

// On démarre la session AVANT d'écrire du code HTML
session_start();

// Si on ne trouve pas de params de session, alors on affiche pas la page 
	if (isset($_SESSION['pseudo']) OR isset($_SESSION['email'])){
	
		if (isset($_GET['lan_num'])){
		
			//Je sécurise les variables
			$received_lan_num_length = strlen($_GET['lan_num']);
		
			if( $received_lan_num_length <=2 ){
				$received_lan_num = $_GET['lan_num'];
			}
		}
	}
?>
	
<div id="myModal" class="modal_popup" style="display: block;">
	<div class="modal-content_popup">
	<span class="close_popup">×</span>
		<div class="article_popup_other">
			Confirmer l'ajout de l'image ?
		</div>
		<div class="article_popup_sub">		
			<button id="confirm_add" class="edition-green" >Confirmer</button>
			<button id="cancel_add" class="edition-red" >Annuler</button>
		</div>		
		<div class="statusMsg popup"></div>
		<div id="received_lan_num" style="display:none"><?php echo $received_lan_num ?></div>
	</div>
</div>
<script src="js/pop_ups.js?<?php echo filemtime('../js/pop_ups.js') ?>"></script>
<script src="js/send_gallery_imgs.js?<?php echo filemtime('../js/send_gallery_imgs.js') ?>"></script>