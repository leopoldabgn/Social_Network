<?php
	session_start();

	if(!isset($_SESSION['id'])) {
		header("Location:../Login/login.php");
		exit(1);
	}

	require_once('../Include/affichage/affichage.php');
	require_once('../Include/BDD/connexionBDD.php');
	require_once('barreRechercheFunc.php');
	
	afficherEntete('Rechercher', array('barreRecherche.css'), false); // Fonction de affichage.php : (titre, style, navbar)
	
	echo '<a class="left-arrow" href="../Accueil/home.php"></a>'; // Fleche pour revenir à l'accueil
	
	echo '<div class="main">';
	
		echo '<div class="bar_container">';
		
			afficherBar("70%", "20%"); // On affiche la barre de recherche
			
		echo '</div>';
		
		afficherResultats(); // Les resultats...
		
	echo '</div>';
	
	afficherFin();
?>