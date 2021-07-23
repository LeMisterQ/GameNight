<?php
	
	# Session lifetime of 3 hours
	ini_set('session.gc_maxlifetime', 21600);
	
	// each client should remember their session id for EXACTLY 1 hour
	session_set_cookie_params(21600);

	# Enable session garbage collection with a 1% chance of
	# running on each session_start()
	ini_set('session.gc_probability', 1);
	ini_set('session.gc_divisor', 100);

	# Our own session save path; it must be outside the
	# default system save path so Debian's cron job doesn't
	# try to clean it up. The web server daemon must have
	# read/write permissions to this directory.
	session_save_path('/var/www/homeq.fr/public_html/sessions');
	
	// On démarre la session AVANT d'écrire du code HTML
	session_start();
	
	//SET le gamecode pour tous les scripts
	$_SESSION['gamecode'] = "ROBBY";
	$gamecode = $_SESSION['gamecode'];
	
?>