<?php
	session_start();
	
	require_once('loginFunc.php');
	require_once('../Include/BDD/connexionBDD.php');
	require_once('../Include/affichage/affichage.php');

	afficherEntete('Page de connexion', 'login.css', false); // Fonction de affichage.php : (titre, style, navbar)

	$erreurs_requis = array();
	$infos_utilisateur = array();
	$donnees = array();

	remplir_valeurs_defaut($donnees);
	echo '<a style="float:right" href="../Inscription/inscription.php">M\'inscrire</a>';
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$donnees = $_POST;
		preTraiter($donnees);
		$ok1 = verifierRequis($donnees, $erreurs_requis);
		$ok2 = verifierBDD($donnees, $infos_utilisateur);
		if($ok1 && $ok2) { // On crée les variables de session. On verifie si l'utilisateur est admin.
			$_SESSION['pseudo'] = $donnees['pseudo']; 
			$requete = "SELECT id, admin FROM members WHERE pseudo='".$donnees['pseudo']."'";
			$bdd = connexionBDD();
			$result = mysqli_query($bdd, $requete);
			$var = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
			mysqli_close($bdd);
			$_SESSION['id'] = $var['id'];
			$_SESSION['admin'] = $var['admin'];
			header('Location:../Accueil/home.php'); // Redirection vers l'accueil
			exit;
		}
	}

	afficher_page($donnees, $erreurs_requis, $infos_utilisateur); //vide ou pré-remplie

	afficherFin(); // Fonction de affichage.php
?>
