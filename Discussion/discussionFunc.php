<?php
	require_once('../Include/BDD/connexionBDD.php');

	function preTraiter($str) {
		if (!empty($str)) {
			$str = trim($str);
			$str = htmlentities($str);
			$str = str_replace(["\\r\\n", "\\r", "\\n"], "<br/>", $str); // Remplace les retours à la ligne par <br/>
			$str = stripslashes($str);	
		}
		
		return $str;
	}
	
	function debutContainer() {
		echo '<div id="container">';
	}
	
	function finContainer() {
		echo '</div>';
	}
	
	function afficherTitre($id2) { // Id du destinataire
		$bdd = connexionBDD();
		$row = false;
		
		$result = mysqli_query($bdd, "SELECT id, pseudo FROM members WHERE id=".$id2);
		if($result) { // Si la requete n'a pas échoué
			$row = mysqli_fetch_assoc($result);
			mysqli_free_result($result);
		}
		mysqli_close($bdd);
		
		if(!$row || (is_array($row) && $row['id'] == $_SESSION['id'])) { // Si mauvais id ou si id correspond à lui même.
			echo '<div style="text-align:center;"><h1>Cet id ne correspond &agrave; aucun utilisateur</h1></div>';
			return false;
		}
		
		echo '<div id="banner">';
		
		echo '<a id="left-arrow" href="../Messagerie/messagerie.php"></a>'; // Fleche pour revenir à la messagerie.
		
		echo '<div id="titre"><h1>Discussion avec '.$row['pseudo'].'</h1></div>';
		
		echo '</div>';
		
		return true;
	}

	function afficherMessages($id1, $id2) { // L'utilisateur avec son id = $id1 voit l'affichage.
		$bdd = connexionBDD();
		
		// On demande les 70 derniers messages entres $id1 et $id2
		$req = "SELECT *, DATE_FORMAT(date, '%d/%m/%Y %Hh%imin%ss') dateFormat FROM messages WHERE ".
			   "id_sender=".$id1." AND id_recipient=".$id2.
			   " OR id_sender=".$id2." AND id_recipient=".$id1.
			   " ORDER BY date DESC LIMIT 70"; // On affiche maximum les 70 derniers messages.
			   
		$req = "SELECT * FROM ($req)Var1 ORDER BY date"; // On re-trie les messages par date. Le "Var1" est obligatoire. Sinon ça ne fonctionne pas.

		$result = mysqli_query($bdd, $req);
		
		if(!$result) { // N'est pas censé se produire.
			echo '<h1>Une erreur s\'est produite</h1>';
			mysqli_close($bdd);
			return;
		}
		
		$row = mysqli_fetch_assoc($result);
		
		echo '<div id="scroll">';		
		
		if(!$row) { // Aucun message
			echo '<h1 id="no-message">Aucun message.</h1>';
		}
		else {
			do {
				// On verifie si c'est un message de nous ou de l'autre personne.
				// On change le css selon les cas.
				$css = $id1 == $row['id_sender'] ? "senderMsg" : "recipientMsg";
				
				$msg = preTraiter($row['message']);
				
				echo '<div class="containerMsg">'.
					 '<div title="'.$row['dateFormat'].'" class="'.$css.'">'. // on affiche la date du message au survol prolongé de la souris.
					 '<form method="POST" action="supprimerMsg.php">'.
					 '<p>'.$msg.'</p>'.
					 '<div class="trash"><input type="image" src="./trash-icon.png" alt="delete icon" name="msg"/></div>'. // Poubelle pour effacer le message
					 '<input name="idRecipient" type="hidden" value="'.$id2.'" />'.
					 '<input name="idMsg" 	    type="hidden" value="'.$row['id'].'" />'.
					 '</form></div></div>';
			} while($row = mysqli_fetch_assoc($result));
		}
		
		echo '</div>';
		
		// Ce script permet de faire descendre la scroll bar vers le bas. (Si elle existe).
		?>
		<script>
			element = document.getElementById('scroll');
			element.scrollTop = element.scrollHeight;
		</script> 
		<?php
		mysqli_free_result($result);
		
		mysqli_close($bdd);
	}
	
	function envoyerMessage($id1, $id2, $message) {
		$bdd = connexionBDD();
		
		$message = mysqli_real_escape_string($bdd, $message);
		
		$req_pre = "INSERT INTO messages(id_sender, id_recipient, message, date) VALUES (?, ?, ?, NOW())";
		$req_pre = mysqli_prepare($bdd, $req_pre);
		
		/* Preparation de la requête */
		mysqli_stmt_bind_param($req_pre, 'iis', $id1, $id2, $message); // Integer integer string. iis.
		
		/* Exécution de la requête */
		mysqli_stmt_execute($req_pre);
		
		/* Fermeture du traitement */
		mysqli_stmt_close($req_pre);
		
		mysqli_close($bdd);
	}
	
	function afficherFormulaire() { // Formulaire pour envoyer un message
	?>
		<form id="msg-form" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>"> <!-- REQUEST_URI obligatoire pour passer le w3c validator -->
			<textarea name="message" id="message" placeholder="&Eacute;crire un message..." required></textarea>
			<div id="send_button">
				<input type="submit" name="send" value="" />
			</div>
		</form>
	
	<?php }

?>