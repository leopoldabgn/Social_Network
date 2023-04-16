<?php

	require_once('../BarreRecherche/barreRechercheFunc.php');

	// Cette page est une barre de navigation et contient une balise <nav>
	// Elle est inclus dans d'autres pages et ne doit pas être affiché
	// toute seule.
	
	$page=$_SERVER['PHP_SELF'];
	$debut = "../Include/navbar/icons/";

	$pages = array(
		$debut.'home-icon' => '../Accueil/home.php',		   	// Accueil
		$debut.'profil-icon' => '../Profil/profil.php',	   	    // Profil
		$debut.'upload-icon' => '../Publier/publier.php',		// Publier
		$debut.'msg-icon' => '../Messagerie/messagerie.php');   // Message

	echo '<nav>';
	echo '<div style="width:25%">';
	afficherBar("53%", "23%"); // Provient de BarreRecherche.php
	echo '</div>';

	echo '<div id="bar">';

	foreach($pages as $imgUrl => $url) {

		$bool = (basename($url) == basename($page)); // Si on est sur cette page, $bool = true.

		$str = $bool ? "" : "No"; // Si on est sur cette page, alors on met le css correspondant au bouton selectionné.
		$imgUrl = $bool ? $imgUrl."-select.png" : $imgUrl.".png"; // Si on est sur cette page, on met l'icon en bleu
		
		echo '<a href="'.$url.'"><span class="Bouton'.$str.'Current"><img class="icon" src="'.$imgUrl.'" alt="icon" /></span></a>';

	}

	echo '</div>';

	echo '<div id="deco-reco">';

	if(!isset($_SESSION['id'])) {
		echo '<a href="../Login/login.php">Se connecter !</a>';
	}
	else {
		echo '<a href="../Login/deco.php">Se d&eacute;connecter !</a>';
	}

	echo '</div>';

	echo '</nav>';

?>