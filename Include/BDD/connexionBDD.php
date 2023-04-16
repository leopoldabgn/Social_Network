<?php
	// Fonction pour se connecter à la bdd.
	// Toute les pages qui doivent se connecter à la bdd utilise cette fonction.
	function connexionBDD() {
		$serv = "localhost";
		$user = "root";
		$pwd = "";
		$bdd = "projet";
		$connexion = mysqli_connect($serv, $user, $pwd, $bdd);
		if(!$connexion){
			echo mysqli_connect_error($connex);
			exit(1);
		}
		
		if (!mysqli_set_charset($connexion, "utf8")) { // !! Très important pour les caractères spéciaux !!
			echo "Erreur charset : ".mysqli_error($connexion);
			exit(2);
		}
		
		return $connexion;
	}

?>