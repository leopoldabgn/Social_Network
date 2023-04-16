<?php
	session_start();

	if(!isset($_SESSION['id'])) {
		header("Location:../Login/login.php");
		exit(1);
	}

	require_once('profilFunc.php');
	require_once('../Include/affichage/affichage.php');
	require_once('../Fil_publi/fil_publi.php');
	
	afficherEntete('Profil', array('profil.css', '../Fil_publi/fil_publi.css'), true); // Fonction de affichage.php : (titre, style, navbar)

	debutContainer(); // fonction de fil_publi.php

	$id1 = $_SESSION['id'];
	$id2 = isset($_GET['id']) ? (integer)$_GET['id'] : false;

	afficherBanniere($id1, $id2, $_POST);
	
	finContainer(); // fonction de fil_publi.php
	
	afficherFin(); // Fonction de affichage.php

?>