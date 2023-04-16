<?php

	function afficherBar($width1, $width2) {
		$search = false;
		if (isset($_GET['search']) AND !empty($_GET['search'])){
			$search = $_GET['search'];
		}
		?>
		
		<form id="recherche" action="../BarreRecherche/barreRecherche.php" method="GET">
			<input id="barre_search"  style="width:<?php echo $width1; ?>" type="search" name="search" placeholder="Faire une recherche..." autocomplete="off" value="<?php echo $search;?>">
			<input id="submit_search" style="width:<?php echo $width2; ?>" type="submit" name="envoyer" value="Rechercher">
		</form>
		
	<?php
	}
	
	function afficherResultats() {
		$bdd = connexionBDD();
		
		if(!isset($_GET['search']))
			return;
		
		if(empty($_GET['search'])) { // Si c'est vide, on affiche 10 personnes au hasard
			$requete = "SELECT pseudo, id FROM members ORDER BY RAND() LIMIT 10";
		}
		else {
			$search = mysqli_real_escape_string($bdd,$_GET['search']);
			$requete = "SELECT pseudo, id FROM members WHERE pseudo LIKE '%".$search."%' ORDER BY id DESC";
		}
			
		$result = mysqli_query($bdd, $requete);

		if (isset($result) AND $result){ // Si il n'y pas d'erreur dans la requete
			$users = mysqli_fetch_assoc($result);
			if (empty($users)){ // Si aucun n'utilisateur ne correspond aux criteres de recherche
				?>
				<div class="search_NoResult">
					<p>Aucun résultat</p>
				</div>
				<?php
			} else {
				?>
				<div class="result">
				<?php if(empty($_GET['search'])) { 
						  echo '<h1>D&eacute;couvrir des comptes :</h1>';
					  } else {
						  echo '<h1>R&eacute;sultats :</h1>';
					  } ?>
					<div class="pseudos">
						<?php
						do { // Le premier utilisateur est déjà dans $users. D'où le do{}while(); pour ne pas l'oublier.
						?>
						<div>
							<a class="search_result" href="../Profil/profil.php?id=<?php echo $users['id']; ?>"> <?php echo $users['pseudo'] ?> </a>
						</div>
					<?php
					} while ($users = mysqli_fetch_assoc($result));
				echo '</div>'; // div class=pseudos
				echo '</div>'; // div class=result
			}
			mysqli_free_result($result);
		}
			
		mysqli_close($bdd);
	}
	
?>