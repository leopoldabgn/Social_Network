<?php
	require_once('../Include/BDD/connexionBDD.php');
	
	if (isset($_POST['supprimer'])) {
		$bdd = connexionBDD();
		$p_id = $_POST['supprimer'];
		
		$requete = "DELETE FROM publications WHERE id='".$p_id."'";
		$result = mysqli_query($bdd, $requete);
		$requete = "DELETE FROM likes WHERE id_publi='".$p_id."'";
		$result = mysqli_query($bdd, $requete);
		//mysqli_free_result($result);
		mysqli_close($bdd);

		// On recupere le lien de l'image qui est relié à cette publication
		// (Si il y en a un)
		$file = glob("../Publications/".$p_id.".*"); // On utilise ".*" car on ne connait pas l'extension de l'image.
		
		if(isset($file[0])) // Si il y a un lien dans le tableau, on le recupere.
			$file = $file[0];
		else
			unset($file); // Sinon, on supprime la variable file.
		
		if(isset($file) && file_exists($file)) { // Si le fichier existe bien, on le supprime.
			unlink($file);
		}
		
	}
	
	// Si HTTP_REFERER est defini, alors on l'utilise pour la location
	// Si ce n'est pas le cas, on redirige vers l'accueil.
	$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "../Accueil/home.php";
	header('Location:'.$url);
?>