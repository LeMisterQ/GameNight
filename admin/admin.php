<?php
	ini_set('display_errors',1);

	//On place toujours ce require en 1er, permet d'avoir toujorus le bon chemin réel vers les fichiers à include
	require_once('../config.php' );

	// On récupère le code de jeu qui active le sesscionstart et le gamecode de session pour tous (à améliorer)
	include_once($root . '/scripts/get_session.php');
	
	include_once($root . '/includes/modal.php');

?>

<html lang="fr" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv=Cache-control content=no-cache>
		<meta http-equiv=Expires content=-1>

		<link rel="stylesheet" href="admin.css?<?php echo filemtime('admin.css'); ?>"/>
		<link rel="stylesheet" href="../css/pop_ups.css?<?php echo filemtime('../css/pop_ups.css') ?>"/>

		<link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet">
		<title>
		Admin GameMaster
		</title>
	</head>
	<body>
			<main class="container_main">
				<?php echo $mymodal; ?>
			</main>
			<div class="gamestate_container">
				<div class="input_answers_container">
					<div class="answer_head">Show Answer Image</div>
					<input id="select_answer" list="answers_img" type="text" placeholder="Chercher..." data-list-filter="^">
					<datalist id="answers_img">

					</datalist>
					<div class="go_answer">Go</div>
				</div>
				<div class="landscape_btn_container">
					<div id="bonus">BONUS</div>
					<div class="applause">APPLAUSE !</div>
					<div class="good">GOOD !</div>
					<div class="wrong">WRONG !</div>
					<div class="buzzer_all">RESET ALL Buzzer</div>
					<div class="buzzer_object">ACTIVATE Buzzer</div>
				</div>	
			</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="ws_admin_client.js?<?php echo filemtime('ws_admin_client.js') ?>"></script>

	</body>
</html>