<?php

	session_start();

	require_once('publierFunc.php');
	require_once('../Include/affichage/affichage.php');
	
	if(!isset($_SESSION['id'])) {
		header("Location:../Login/login.php");
		exit(1);
	}
	
	afficherEntete('Publier', 'publier.css', true); // Fonction de affichage.php : (titre, style, navbar)
	
	debutContainer();

	echo '<div id="titre"><h1>Publier un message...</h1></div>';

	$id = $_SESSION['id'];
	$error = false;
	$text = false;
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$text = isset($_POST['description']) ? $_POST['description'] : false;
				
		$error = checkFile($_FILES['imageUpload']); // On verifie que l'image respecte les criteres requis.
		if(!$error && !empty($text)) {
			injecterDansBDD($id, $text);
			uploadFile($_FILES['imageUpload']); // On upload l'image.
			header('Location:../Profil/profil.php');
			exit(0);
		}
	}
	
	afficherFormulaire($error, $text);
	
	finContainer();

	afficherFin(); // Fonction de affichage.php
?>