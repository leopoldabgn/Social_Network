<?php
	session_start();
	
	$_SESSION = array();
	session_destroy(); // On detruit la session.
	
	require_once('../Include/affichage/affichage.php');
	
	afficherEntete('D&eacute;connexion', array(), false); // Fonction de affichage.php : (titre, style, navbar)

	
	echo '<h1>&Agrave; bientot !<h1>';

	afficherFin(); // Fonction de affichage.php*
	
	header('Location:../Accueil/home.php');
	
?>