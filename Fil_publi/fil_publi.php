<?php
	
	require_once('../Include/BDD/connexionBDD.php');
	
	function debutContainer() {
		echo '<div id="container">';
	}
	
	function finContainer() {
		echo '</div>';
	}
	
	function getImg($id) { // Verifie si une image existe pour cette id.
		$extensions = array('jpg', 'jpeg', 'png', 'gif');
		$url = "";
		foreach($extensions as $ext) {
			$url = "../Publications/".$id.".".$ext;
			if(is_file($url)) {
				$bool = true;
				break;
			}
		}
		
		if(isset($bool)) // Si l'image existe, on recupere son url.
			$img = $url;
		else
			$img = false; // Sinon on renvoie false.
		
		return $img;
	}
	
	function like($id_publi, $id_member) { // id_member like la publi avec id_publi
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "INSERT INTO likes(id_publi, id_member) VALUES($id_publi, $id_member)";
		$result = mysqli_query($bdd, $requete);
		
		mysqli_close($bdd);
	}
	
	function unlike($id_publi, $id_member) { // id_member enleve son like de la publi avec id_publi
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "DELETE FROM likes WHERE id_publi=$id_publi AND id_member=$id_member";
		$result = mysqli_query($bdd, $requete);

		mysqli_close($bdd);
	}
	
	function isLikedBy($id_publi, $id_member) { // On verifie si $id_member a mis un like $id_publi
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "SELECT * FROM likes WHERE id_publi=$id_publi AND id_member=$id_member";
		$result = mysqli_query($bdd, $requete);
		
		if(!$result)
			$row = false;
		else
			$row = mysqli_fetch_assoc($result);
		
		mysqli_close($bdd);
		
		return (boolean)$row; // Renvoie true si $row est un tableau. False sinon.
	}
	
	function getNbLikes($id_publi) { // Renvoie le nombre de like pour cette publi.
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "SELECT COUNT(id_publi) as nbLikes FROM likes WHERE id_publi=".$id_publi;
		$result = mysqli_query($bdd, $requete);
		
		if(!$result)
			$nb = false;
		else
			$nb = mysqli_fetch_assoc($result);
		
		mysqli_close($bdd);
		
		return is_array($nb) ? $nb['nbLikes'] : false;
	}
	
	function afficher_coeur($id1, $id_publi, $donnees) {
		$liked = isLikedBy($id_publi, $id1);
		
		if(isset($donnees) && !empty($donnees)) {
			if(isset($donnees['coeur_vide'.$id_publi.'_x'])) { // On verifie si le coeur etait rouge ou vide.
				if(!$liked) { // Evite un bug. Plusieurs likes pouvaient être attribués lors d'un refresh de la page.
					like($id_publi, $id1);
					$liked = !$liked;
				}
			}
			else if(isset($donnees['coeur_rouge'.$id_publi.'_x'])){ // Si il etait rouge, c'est qu'il y a un dislike.
				if($liked) {
					unlike($id_publi, $id1);
					$liked = !$liked;
				}
			}
		}
		
		if($liked) {
			echo '<input name="coeur_rouge'.$id_publi.'" type="image" src="../Fil_publi/coeur_rouge.png" alt="coeur rouge" />';
		} else {
			echo '<input name="coeur_vide'.$id_publi.'" type="image" src="../Fil_publi/coeur_vide.png" alt="coeur vide" />';
		}
	}
	
	function preTraiter($str) {
		if (!empty($str)) {
			$str = trim($str);
			$str = htmlentities($str);
			$str = str_replace(["\\r\\n", "\\r", "\\n"], "<br/>", $str); // Remplace les retours à la ligne par <br/>
			$str = stripslashes($str);	
		}
		
		return $str;
	}
	
	function afficherPublication($id1, $infos, $donnees) {
		// On a l'id de la personne qui est connecté (important pour les likes)
		// Et les infos concernant la publication.
		// p_id -> id publication
		// m_id -> id de celui qui a posté la publication
		// id1  -> id de celui qui est connecté actuellement
		
		if(isset($id1) && isset($infos)) {
			
			$img = getImg($infos['p_id']);

			?>

			<div class="publi-container">
				<div class="publi-title"> <!-- On affiche un lien clickable avec le pseudo du createur -->
					<?php echo '<a href="../Profil/profil.php?id='.$infos['m_id'].'">'.$infos['pseudo'].'</a>'; ?>
				</div>
				<?php if($img) { ?> <!-- Si il y a une image, on l'affiche -->
				<div class="publication">
					<img src="<?php echo $img; ?>" alt="publication" />
				</div>
				<?php } ?>
				<div class="publi-desc"> <!-- Texte de la publi -->
					<p> <?php echo preTraiter($infos['description']); ?></p>
				</div>
				<div class="publi-option-bar"> <!-- Barre avec le like et (parfois) une poubelle pour la suppression. -->
					<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
						<div class="publi-like">
							<?php
								afficher_coeur($id1, $infos['p_id'], $donnees);
								$nb = getNbLikes($infos['p_id']);
								$nb = !$nb ? 0 : $nb;
								echo '<p>';
								echo ($nb > 1 ? $nb.' likes' : $nb.' like'); // On affiche un 's' à "like" si il y en a plusieurs.
								echo '</p>';
							?>
						</div>
					</form>
					<!-- On affiche la poubelle dans 2 cas :
						- Si c'est notre publication
						- Si on est administrateur
					-->
					<?php if ($infos['m_id'] == $_SESSION['id'] || $_SESSION['admin'] == 1){ ?>
					<div class="supprimer"> 
						<!-- Methode POST pour que ce soit sécurisé -->
						<form action="../Fil_publi/supprimerPubli.php" method="POST">
							<div class="trash">
								<input type="image" src="../Fil_publi/trash-icon.png" alt="delete icon">
							</div>
							<!-- Champ caché avec l'id de la publication à supprimer en cas de click sur la poubelle -->
							<input type="hidden" name="supprimer" value="<?php echo $infos['p_id'] ?>">
						</form>
					</div>
					<?php } ?>
				</div>
				<div class="publi-date"> <!-- On affiche la date de la publi -->
					<p> <?php echo $infos['date']; ?> </p>
				</div>
			</div>
		
		<?php }
	}
	
	function getMyFollowTab($id) { // Renvoie les personnes à qui $id est abonné.
		$bdd = connexionBDD(); // On se connecte à la BDD.
		$requete = "SELECT id_member FROM subscription WHERE id_follower=".$id;
		$result = mysqli_query($bdd, $requete);
		if(!$result) {
			mysqli_close($bdd);
			return array();
		}
		
		$followers = array();
		
		while($row = mysqli_fetch_assoc($result)) {
			$followers[] = $row['id_member'];
		}
		
		mysqli_free_result($result);
		mysqli_close($bdd);
		
		return $followers;
	}
	
	function afficherFil($id1, $followers, $donnees) { // Permet d'afficher les publications sur la page d'acceuil et de profil.
		// Prend l'utilisateur connecté et un tableau contenant ses abonnés.
		// Pour afficher un profil, le tableau contiendra uniquement l'id de la personne, pour afficher uniquement ses publications.
		$bool = false;
		if(!isset($followers))
			$bool = true;
		else if(empty($followers))
			$bool = true;
		
		if($bool) {
			echo '<h1 style="text-align:center">Vous n\'&ecirc;tes encore abonn&eacute; &agrave; personne !</h1>';
			return;
		}
		
		$bdd = connexionBDD(); // On se connecte à la BDD.
		$requete = "SELECT m.pseudo pseudo, m.admin admin, m.id m_id, p.id p_id, p.*, ".
				   "DATE_FORMAT(p.date, '%d/%m/%Y') date FROM publications ".
				   "p INNER JOIN members m ON p.id_member = m.id"; // Jointure entre la table members et la table publications.
				   
		// On recupere les champs pour chaque utilisateur à qui nous sommes abonné.
		// On rajoute un "OR m.id = ..." pour chaque personne. 
		for($i=0;$i<count($followers);$i++) {
			if($i == 0)
				$requete = $requete." WHERE m.id=".$followers[$i];
			else
				$requete = $requete." OR m.id=".$followers[$i];
		}
		$requete = $requete." ORDER BY p.id DESC"; // On trie par id. Revient au même que de trier par date.
		$result = mysqli_query($bdd, $requete);
		
		if(!$result) {
			mysqli_close($bdd);
			return;
		}
		
		echo '<div id="scrolling-container">';
		
		$row = mysqli_fetch_assoc($result);
		
		if(!$row) {
			echo "<h1 style=\"text-align:center\">Aucune publication</h1>";
		}
		else {
			do {
				afficherPublication($id1, $row, $donnees); // On affiche chaque publication avec les bonnes informations en parametre.
			} while($row = mysqli_fetch_assoc($result));
		}
		
		echo '</div>';
		
		mysqli_free_result($result);
		mysqli_close($bdd);
	}

?>