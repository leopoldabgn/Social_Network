<?php

	require_once('../Include/BDD/connexionBDD.php');
	
	function getPseudo($id) { // Renvoie le pseudo si l'utilisateur existe. Sinon renvoie false.
		$bdd = connexionBDD();
		
		$req = "SELECT pseudo FROM members WHERE id=".$id;
		$result = mysqli_query($bdd, $req);
		$row = mysqli_fetch_assoc($result);
		
		mysqli_free_result($result);
		mysqli_close($bdd);
		
		if($row)
			return $row['pseudo'];
		return false;
	}
	
	function afficherTitre($id) {
		$pseudo = getPseudo($id);
		if(!$pseudo) { // Si l'id ne correspond à aucun membre.
			echo '<h1 id="titre">Aucun profil ne correspond &agrave; cet id !</h1>';
			return false;
		}
		else {
			echo '<h1 id="titre">Profil de '.$pseudo.'</h1>';
			return true;
		}
	}
	
	function afficherBanniere($id1, $id2, $donnees) {
		
		if($id2 && $id1 != $id2)
			$following = isFollowing($id1, $id2); // Je verifie si id1 suit id2
		
		if(isset($donnees['follow-button']) && isset($following)) { // Si le bouton follow est cliqué
				
				if($following) {
					unfollow($id1, $id2); // $id1 ne suit plus $id2
				}
				else {
					
					follow($id1, $id2); // $id1 s'abonne à $id2
				}
				$following = !$following;
		}
		
		if($id2 && $id1 != $id2){
			$bdd = connexionBDD();
			$req = "SELECT admin FROM members WHERE id=".$id2; // On regarde si la personne a qui appartient le profil est admin.
			$result = mysqli_query($bdd, $req);
			$row = mysqli_fetch_assoc($result);
			
			$admin = $row['admin'];
			
			if($_SESSION['admin'] == 1 && isset($donnees['admin-button'])) { // Si la personne actuelle est bien admin, et que le bouton nommer admin a ete pressé.
				$admin = $row['admin'] == 0 ? 1 : 0; // Si il est admin, on lui enleve les privilèges. Sinon on lui ajoute.
				$req = "UPDATE members SET admin=".$admin."  WHERE id=".$id2;
				$result = mysqli_query($bdd, $req);					
			}
			mysqli_close($bdd);
		}
		
		// Si il y a un id dans l\'url, alors on affiche le profil
		// correspondant à cet utilisateur. Sinon, on affiche le profil
		// de l\'utilisateur connecté.
		
		if(!afficherTitre($id2 ? $id2 : $id1)) // Si id2 vaut false, alors il n'y rien dans l'url. Cela signifie qu'on doit afficher le profil de l'utilisateur connecté
			return;
		
		afficherCompteurs($id2 ? $id2 : $id1); // Meme manipulation ici pour le parametre.
		
		echo '<div id="buttons-container">';
		echo '<form method="POST" action="'.$_SERVER['REQUEST_URI'].'">';
		
		if(isset($following)) {
			afficherBoutonFollow($following);
			$tab = array($id2);
		}
		else {
			$tab = array($id1); // On affiche pas de bouton follow si on est sur le profil de la personne connecté.
		}
		
		if($id2 && $id2 != $id1) {
			echo '<a id="ecrire" href="../Discussion/discussion.php?id='.$id2.'">&Eacute;crire</a>';
		}
		
		if($_SESSION['admin'] == 1 && isset($admin)) {
			$str = $admin == 0 ? "Nommer administrateur" : "D&eacute;j&agrave; administrateur"; // On change le nom du bouton en fonction du fait qu'il soit admin ou pas.
			echo '<input id="admin-button" type="submit" name="admin-button" value="'.$str.'"/>';
		}
		
		echo '</form>';
		echo '</div>';
		
		afficherFil($id1, $tab, $donnees);
	}
	
	function follow($id_follower, $id1) { // $id_follower s'abonne à $id1
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "INSERT INTO subscription(id_member, id_follower) VALUES($id1, $id_follower)";
		$result = mysqli_query($bdd, $requete);
		
		mysqli_close($bdd);
	}
	
	function unfollow($id_follower, $id1) { // id_follower ne suit plus $id1
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "DELETE FROM subscription WHERE id_member=".$id1." AND id_follower=".$id_follower;
		$result = mysqli_query($bdd, $requete);

		mysqli_close($bdd);
	}

	function isFollowing($id_follower, $id1) { // On verifie si $id_follower est abonné à $id1
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "SELECT * FROM subscription WHERE id_member=".$id1." AND id_follower=".$id_follower;
		$result = mysqli_query($bdd, $requete);
		
		if(!$result)
			$row = false;
		else
			$row = mysqli_fetch_assoc($result);
		
		mysqli_close($bdd);
		
		return (boolean)$row;
	}
	
	function getNbPubli($id) { // Recupere le nombre de publi de id
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "SELECT COUNT(id) as nbPubli FROM publications WHERE id_member=".$id;
		$result = mysqli_query($bdd, $requete);
		
		if(!$result)
			$nb = false;
		else {
			$nb = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
		}
		
		mysqli_close($bdd);
		
		return is_array($nb) ? $nb['nbPubli'] : false;
	}
	
	function getArrayFollowers($id) { // Renvoie un tableau avec le nombre d'abonnés et le nombre d'abonnements.
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$loop = array("id_member", "id_follower");
		$follow = array();
		
		for($i=0;$i<2;$i++) {
			$requete = "SELECT COUNT(id_follower) as nbFollow FROM subscription WHERE ".$loop[$i]."=".$id;
			$result = mysqli_query($bdd, $requete);
			
			if(!$result)
				return array();
			$row = mysqli_fetch_assoc($result);
			$follow[] = $row['nbFollow'];
			mysqli_free_result($result);
		}
		mysqli_close($bdd);
		
		return $follow; // $follow[0] --> abonnés, $follow[1] --> abonnements.
	}
	
	function afficherCompteurs($id) // On affiche tous les compteurs : publications, abonnés, abonnements
	{
		$arr = getArrayFollowers($id);
		$arr[] = getNbPubli($id);
		echo '<div id="compteurs">';
		
		echo '<div class="center-container"><p><span class="number">'.$arr[2].'</span>'.
			 '<br/>Publication'.($arr[2] > 1 ? 's' : '').'</p></div>'; // On affiche le compteur de publications.
		echo '<div class="center-container"><p><span class="number">'.$arr[0].'</span>'.
			 '<br/>Abonn&eacute;'.($arr[0] > 1 ? 's' : '').'</p></div>'; // On affiche le compteur d'abonnés.
		echo '<div class="center-container"><p><span class="number">'.$arr[1].'</span>'.
			 '<br/>Abonnement'.($arr[1] > 1 ? 's' : '').'</p></div>'; // On affiche le compteur d'abonnements.
			 
		echo '</div>';
	}
	
	function afficherBoutonFollow($following) {
		$class = !$following ? "follow_button" : "unfollow_button" ;
		$value = !$following ? "+ Follow" : "Unfollow";
		?>
		<input id="<?php echo $class; ?>" type="submit" name="follow-button" value="<?php echo $value; ?>" />
	<?php }
	
?>