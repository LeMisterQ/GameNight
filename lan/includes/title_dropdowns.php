<div id="drop_container" class="drop_container">
	<div class="dropdown">
		<button id="but_title_1" class="dropbtn">Partie I</button>
		<div id="drop_title_1" class="dropdown-content">
			<input id="drop_input_title_1" class="input_search" type="text" placeholder="Chercher.." >
		</div>	
	</div> 
	<div class="dropdown">
		<button id="but_title_2" class="dropbtn">Partie II</button>
		<div id="drop_title_2" class="dropdown-content">
			<input id="drop_input_title_2" class="input_search" type="text" placeholder="Chercher.." >
		</div>	
	</div> 
	<div class="dropdown">
		<button id="but_title_3" class="dropbtn">Partie III</button>
		<div id="drop_title_3" class="dropdown-content">
			<input id="drop_input_title_3" class="input_search" type="text" placeholder="Chercher.." >
		</div>	
	</div> 
	<div class="dropdown">
		<button id="but_title_4" class="dropbtn">Partie IV</button>
		<div id="drop_title_4" class="dropdown-content">
			<input id="drop_input_title_4" class="input_search" type="text" placeholder="Chercher.." >
		</div>	
	</div> 
	<div class="dropdown">
		<button id="but_title_5" class="dropbtn">Partie V</button>
		<div id="drop_title_5" class="dropdown-content">
			<input id="drop_input_title_5" class="input_search" type="text" placeholder="Chercher.." >
		</div>	
	</div> 
	<div id="title_full" class="article_profile_sub"></div>	
</div>
	
<form>
	<button id="validate_title" type="submit" class="registerbtn" disabled>Valider Titre</button>
</form>
<div class="article_profile_sub">
	<div class="statusMsg"></div>
</div>
<script src="js/title_gen.js?<?php echo filemtime('js/title_gen.js') ?>"></script>
<script src="js/send_title.js?<?php echo filemtime('js/send_title.js') ?>"></script>
