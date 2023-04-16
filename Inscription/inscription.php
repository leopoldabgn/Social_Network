<?php

	require_once('inscription_func.php');
	require_once('../Include/BDD/connexionBDD.php');
	require_once('../Include/affichage/affichage.php');
	
	afficherEntete('Inscription', 'inscription.css', false); // Fonction de affichage.php : (titre, style, navbar)

	$erreurs_requis = array();
	$erreurs_format = array();
	$infosDejaUtilise = array();
	$donnees = array();

	remplir_valeurs_defaut($donnees);

	echo '<a style="float:right" href="../Login/login.php">Se connecter</a>';

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$donnees = $_POST;
		preTraiter($donnees);
		$ok1 = verifierRequis($donnees, $erreurs_requis);
		$ok2 = verifierFormat($donnees, $erreurs_format);
		$ok3 = verifierBDD($donnees, $infosDejaUtilise);
		
		if($ok1 && $ok2 && $ok3) {
			injecterDansBDD($donnees);
			header('Location:../Accueil/home.php'); // Redirection vers l'accueil
			exit;
		}
	}

	afficher_page($donnees, $erreurs_requis, $erreurs_format, $infosDejaUtilise); //vide ou pré-remplie

	afficherFin(); // Fonction de affichage.php
?>