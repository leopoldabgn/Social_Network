<?php
	// Fonction utilisé dans tous les fichiers.
	// Affiche l'entete avec les balises html, head, body
	function afficherEntete($title, $style, $navBar) { ?>
		<!DOCTYPE html>
		<html lang="fr">
			<head>
				<meta charset="utf-8">
				<?php if($navBar == true) { ?>
				<!-- On ajoute le style de la barre de navigation -->
				<link rel="stylesheet" href="../Include/navbar/navbar.css"/>
				<!-- On ajoute le style de la barre de recherche -->
				<link rel="stylesheet" href="../BarreRecherche/barreRecherche.css"/>
				<?php }
				
				if(is_array($style)) { // Si il y a plusieurs styles, on les ajoute tous.
					foreach($style as $s) {
						echo '<link rel="stylesheet" href="'.$s.'"/>';
					}
				}
				else { // Sinon, si il n'y en a qu'un, on ajoute uniquement celui là.
					echo '<link rel="stylesheet" href="'.$style.'"/>';
				} ?>
				<!-- On ajoute le style qui change la police d'ecriture (c'est la même pour toutes les pages -->
				<link rel="stylesheet" href="../Include/Font/font.css"/>
				<!-- On change le titre de l'onglet de la page -->
				<title><?php echo $title ?></title>
			</head>
			<body>
		<?php
		if($navBar == true) {// Uniquement si $navBar est true.
			require_once('../Include/navbar/navbar.php'); // On inclut la barre de navigation
		}
	}
	
	function afficherErreur() { // Fonction qui affiche une erreur. Elle n'est utilisé par aucun fichier actuellement.
		echo '<div style="text-align:center">';
		echo '<h1>Une erreur s\'est produite !</h1>';
		echo '<a href="../Accueil/home.php">revenir &agrave; l\'accueil</a>';
		echo '</div>';
	}
	
	function afficherFin() { // Ferme les balises html et body ouvertent precedemment
		echo '</body></html>';
	}
	
?>