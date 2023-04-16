<?php
	
	session_start();

	require_once('messagerieFunc.php');
	require_once('../Include/affichage/affichage.php');

	if(!isset($_SESSION['id'])) {
		header("Location:../Login/login.php");
		exit(1);
	}

	afficherEntete('Messagerie', 'messagerie.css', true); // Fonction de affichage.php : (titre, style, navbar)

	//////
	//
	// On récupère un $id qui correspond à la personne connecté actuellement
	//
	/////
	
	$id = $_SESSION['id'];
	
	debutContainer();
	
	afficherTitre();
	
	afficherConv($id);
	
	finContainer();
	
	afficherFin(); // Fonction de affichage.php
?>