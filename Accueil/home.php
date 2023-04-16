<?php

	session_start();

	require_once('../Include/affichage/affichage.php');
	require_once('../Fil_publi/fil_publi.php');
	
	if(!isset($_SESSION['id'])) {
		header("Location:../Login/login.php");
		exit(1);
	}
	
	afficherEntete('Accueil', array('../Fil_publi/fil_publi.css'), true); // Fonction de affichage.php : (titre, style, navbar)

	debutContainer(); // fonction de fil_publi.php
	
	echo '<h1 id="titre">Fil d\'actualit&eacute;</h1>';
	
	$followers = getMyFollowTab($_SESSION['id']); // Je recupere les personnes à qui je suis abonné.
	afficherFil($_SESSION['id'], $followers, $_POST); // On affiche le fil d'actualité
	
	finContainer(); // fonction de fil_publi.php
	
	afficherFin(); // Fonction de affichage.php
?>