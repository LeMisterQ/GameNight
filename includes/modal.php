<?php

	// On récupère la fonction permettant de savoir si on est sur mobile
	include_once($root . '/includes/ismobile.php');
	
	if(strval(trim(isMobileDevice())) == "0"){

	$mymodal = '
		<div id="myModal" class="modal_popup">
			<!-- Modal content -->
			<div class="modal-content_popup">
				<div class="article_popup_other" ></div>
				<div class="close_popup">&times;</div>
			</div>
		</div>	
		';
	}
	else if(strval(trim(isMobileDevice())) == "1"){
		
		$mymodal = '
		<div id="myModal" class="modal_popup">
			<!-- Modal content -->
			<div class="modal-content_popup">
				<div class="article_popup_other" ></div>
			</div>
			<button class="closebtn">Fermer</button>
		</div>	
		';
	
	}

?>