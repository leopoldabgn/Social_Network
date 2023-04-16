<?php
	require_once('../Include/BDD/connexionBDD.php');

	function preTraiterChamp($champ) {
		if (!empty($champ)) {
			$champ = trim($champ);
			$champ = htmlspecialchars($champ);
		}
		return $champ;
	}
	
	function debutContainer() {
		echo '<div id="container">';
	}
	
	function finContainer() {
		echo '</div>';
	}
	
	function afficherTitre() {
		echo '<div id="titre"><h1>Mes messages</h1></div>';
	}

	function getPseudo($id) {
		$bdd = connexionBDD();
		
		$req = "SELECT pseudo FROM members WHERE id=".$id;
		$result = mysqli_query($bdd, $req);
		$row = mysqli_fetch_assoc($result);
		
		mysqli_free_result($result);
		mysqli_close($bdd);
		
		if($row)
			return $row['pseudo'];
		return "ERREUR : Aucun pseudo ne correspond a cette id.";
	}
	
	function getLastMsg($id1, $id2) { // On renvoie le dernier message entre les deux users.
		$bdd = connexionBDD();
		
		$req = "SELECT message FROM messages WHERE ".
	   "id_sender=".$id1." AND id_recipient=".$id2.
	   " OR id_sender=".$id2." AND id_recipient=".$id1.
	   " ORDER BY date DESC LIMIT 1";
		$result = mysqli_query($bdd, $req);
		$row = mysqli_fetch_assoc($result);
		
		mysqli_free_result($result);
		mysqli_close($bdd);
		if($row)
			return $row['message'];
		return "ERREUR : Pas de message.";
	}
	
	function preTraiter($str) {
		if (!empty($str)) {
			$str = trim($str);
			$str = htmlentities($str);
			$str = str_replace(["\\r\\n", "\\r", "\\n"], " ", $str);
			$str = stripslashes($str);	
		}
		
		return $str;
	}
	
	function raccourcir($msg) { // Renvoie un message raccourci (60 caractères max) et rajoute "..." à la fin si besoin.
		if(!isset($msg))
			return "";
		$m = substr($msg, 0, 60);
		$m = preTraiter($m);
		if(strlen($m) == 60)
			$m .= "...";
		
		return $m;
	}
	
	function afficherConv($id) {
		$bdd = connexionBDD();
		
		// Requete pour recuperer les personnes avec qui $id à des conversations.
		$req = "SELECT id_recipient, id_sender FROM messages WHERE ".
			   "id_sender=".$id." OR id_recipient=".$id;

		$result = mysqli_query($bdd, $req);
		
		echo '<div id="scroll">';
		
		$conv = array();
		
		while($row = mysqli_fetch_assoc($result)) { // On les met dans $conv.
			// Si je suis celui qui envoie, alors je recupere l'id de celui qui reçoit
			// Et inversement.
			$var = $row['id_sender'] == $id ? $row['id_recipient'] : $row['id_sender'];
			
			if(!in_array($var, $conv))
				$conv[] = $var;
		}
		
		mysqli_free_result($result);
		mysqli_close($bdd);
		
		for($i=0;$i<count($conv);$i++) { // J'affiche le pseudo de chaque personne et notre dernier message.
			echo '<div class="msgBox"><a href="../Discussion/discussion.php?id='.$conv[$i].'">'.
				 '<div class="pseudo"><p>'.getPseudo($conv[$i]).'</p>'.
				 '</div><div class="msg"><p>'.raccourcir(getLastMsg($id, $conv[$i])).'</p></div></a></div>';
		}
		
		if(count($conv) == 0) {
			echo '<div id="alone"><h1>Cette salle est bien vide...</h1></div>';
		}
		
		echo '</div>';
		
		// Ce script permet de faire descendre la scroll bar vers le bas. (Si il en y a une).
		?>
		<script>
			element = document.getElementById('scroll');
			element.scrollTop = element.scrollHeight;
		</script> 
		<?php
		
		
	}
	
?>