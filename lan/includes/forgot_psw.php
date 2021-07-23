<div id="myModal" class="modal_popup">

  <!-- Modal content -->
  <div class="modal-content_popup">
    <span class="close_popup">&times;</span>
	<form method="post">
		<div class="article_sub" >Mot de passe oublié ? Boulet...<br /><br />
			
			<label for="forget_email"><b>Saisir un email valide</b></label>
			<input class="init" type="text" id="forget_email" name="forget_email" pattern="^[A-z0-9._%+-]+@[A-z0-9.-]+\.[A-z]{2,4}$" required>
			
			<button id="button_forgot" class="registerbtn" type="button" disabled>Soumettre</button>
		</div>
	</form>
	<div class="statusMsg"></div>
  </div>
</div>
<script src="js/jquery-3.4.1.min.js?<?php echo filemtime('js/jquery-3.4.1.min.js') ?>"></script>
<script src="js/pws_check.js?<?php echo filemtime('js/pws_check.js') ?>"></script>
<script src="js/send_forget_psw.js?<?php echo filemtime('js/send_forget_psw.js') ?>"></script>