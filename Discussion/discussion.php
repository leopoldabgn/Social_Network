<?php
	
	session_start();
	
	require_once('discussionFunc.php');
	require_once('../Include/affichage/affichage.php');

	if(!isset($_SESSION['id'])) { // Si il n'est pas connecté, on redirige vers la page de login
		header("Location:../Login/login.php");
		exit(1);
	}

	if(!isset($_GET['id'])) { // Si l'utilisateur supprime le parametre id dans l'url
		header("Location:../Messagerie/messagerie.php"); // On redirige vers la page qui affiche les conversations.
		exit(2);
	}

	afficherEntete('Discussion', 'discussion.css', false); // Fonction de affichage.php : (titre, style, navbar)
	
	//////
	//
	// On récupère un $id1 qui correspond à la personne connecté actuellement
	// On récupère un $id2 qui correspond à la personne à qui on écrit.
	//
	/////
	
	$id1 = $_SESSION['id'];			// Contient forcement un nombre
	$id2 = (integer)$_GET['id'];    // Contient forcement un nombre
	
	// Il ne peut pas y avoir d'injection SQL avec ces deux champs.
	// On ne fera donc pas de mysqli_real_escape_string() dans les fonctions.
	
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$donnees = $_POST;
		envoyerMessage($id1, $id2, $donnees['message']);
	}
	
	debutContainer();
	
	// Verifie egalement si l'id de GET correspond à un vrai id. Si ce n'est pas le cas,
	// il affiche une erreur et n'affiche pas les messages.
	
	if(afficherTitre($id2)) {
		afficherMessages($id1, $id2);
		afficherFormulaire();
	}
	
	finContainer();
	
	afficherFin(); // Fonction de affichage.php
?>