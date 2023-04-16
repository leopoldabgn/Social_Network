<?php

	require_once('../Include/BDD/connexionBDD.php');

	function debutContainer() {
		echo '<div id="container">';
	}
	
	function finContainer() {
		echo '</div>';
	}

	function checkFile(&$file) {
		$error = true;
		
		// Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
		
		// Si size == 0, aucune image n'a été uploadé.
		// (Il n'y a pas d'erreur, on peut poster une publication sans image).
		if(isset($file) && $file['size'] == 0)
			return false;
		
		if (isset($file) AND $file['error'] == 0) {
			// Testons si le fichier n'est pas trop gros
			
			if ($file['size'] <= 3000000) { // 3mo maximum accepté.
				// Testons si l'extension est autorisée
				$infosfichier = pathinfo($file['name']);
				$extension_upload = $infosfichier['extension'];
				$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
				if (in_array($extension_upload, $extensions_autorisees))
					$error = false;
			}
		}

		return $error;
	}

	function uploadFile(&$file) {
		$extension = pathinfo($file['name'])['extension'];
		// On stocke l'image avec l'id de la publication comme nom, suivi de l'extension de l'image (png, jpg...).
		move_uploaded_file($file['tmp_name'], '../Publications/'.getLastID().'.'.$extension);
	}

	function getLastID() { // Permet de recuperer l'id de la dernière publication posté.
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$requete = "SELECT id FROM publications ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($bdd, $requete);

		$row = mysqli_fetch_assoc($result);

		mysqli_free_result($result);
		mysqli_close($bdd);

		if(!$row) { // Si c'est "false", c'est qu'il n'y a aucune publication posté sur le site.
			return 1; // (Normalement, ce cas ne devrait pas arrivé ).
		}
		return $row['id'];

	}
	
	function injecterDansBDD($id_member, $text) {
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$text = mysqli_real_escape_string($bdd, $text);
		
		$req_pre = "INSERT INTO publications(id_member, description, date) VALUES(?, ?, NOW())"; // On cree un nouveau champ dans la table publications de notre base de données.
		$req_pre = mysqli_prepare($bdd, $req_pre);
		/* Preparation de la requête */
		mysqli_stmt_bind_param($req_pre, 'is', $id_member, $text);
			
		/* Exécution de la requête */
		mysqli_stmt_execute($req_pre);
			
		/* Fermeture du traitement */
		mysqli_stmt_close($req_pre);
			
		mysqli_close($bdd);
	}

	function afficherFormulaire($error, $text) { ?>
		
		<form id="publication" method="POST" action="publier.php" enctype="multipart/form-data">
			<div id="upload_container">
				<label for="upload"><img id="upload_arrow" src="upload.png" alt="upload arrow"/></label>
				<p>Ajouter une image...</p>
			</div>
				
			<input id="upload" type="file" name="imageUpload" style="display:none;" onchange="loadFile(event)" />
			<img src="no_src" id="output" alt="output image" /> <!-- image de preview -->
				
			<textarea name="description" placeholder="Ecrire un message..." <?php if(isset($text) && !empty($text)) {echo 'value="'.$text.'"';	} ?> required></textarea>
			<input id="bouton-publication" type="submit" value="Publier" />
				
			<?php
				if($error) {
					echo '<p>Le fichier est trop gros ou n\'est pas du bon format !</p>';
				}
			?>
				
		</form>
		<!-- J'inclus mon fichier javascript : -->
		<script src="preview.js"></script>
<?php
	}
?>