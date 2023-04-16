<?php

	require_once('../Include/BDD/connexionBDD.php');

	if(!isset($_POST['idRecipient']) || !isset($_POST['idMsg'])) {
		header('Location:../Messagerie/messagerie.php');
		exit(1);
	}
	
	$bdd = connexionBDD();
	
	$id = $_POST['idRecipient']; // Pas de possibilité d'injection SQL
	$idMsg = $_POST['idMsg'];	 // Pas de possibilité d'injection SQL
	
	$result = mysqli_query($bdd, "DELETE FROM messages WHERE id=".$idMsg);
		
	mysqli_free_result($result);
	mysqli_close($bdd);
	
	// On aurait pu utiliser $_SERVER['HTTP_REFERER'] à la place...
	header('Location:discussion.php?id='.$id); 
?>