<?php
	$REQUIS = array(
		'pseudo' => true,
		'password' => true);

	function preTraiterChamp($champ) {
		if (!empty($champ)) {
			$champ = trim($champ);
			$champ = htmlspecialchars($champ);
		}
		return $champ;
	}

	function preTraiter(&$donnees) {
		$donnees["pseudo"] = preTraiterChamp($donnees["pseudo"]);
		$donnees["password"] = preTraiterChamp($donnees["password"]);
	}

	function verifierRequis(&$donnees, &$erreurs) {
		global $REQUIS;
		$ok = true;
		foreach ($REQUIS as $champ => $valeur) {
			if (empty($donnees[$champ])) {
				$erreurs[$champ] = true;
				$ok = false;
			}
		}
		return $ok;
	}

	function remplir_valeurs_defaut(&$donnees) {
		$donnees['pseudo'] = false;
		$donnees['password'] = false;
	}

	function page_enreg(&$donnees, &$erreurs_requis) {
	  afficher_entete("Enregistrement");
	  $erreurs = erreurs($erreurs_requis);
	  afficher_formulaire($donnees, $erreurs);
	  //afficher_pied_page();
	}

	function verifierBDD(&$donnees, &$infos_utilisateur) { // On verifie si le mdp et le pseudo sont corrects.
		$ok = true;
		$bdd = connexionBDD();

		$requete = "SELECT pseudo FROM members WHERE pseudo='".$donnees['pseudo']."'";
		$result = mysqli_query($bdd, $requete);

		if(!mysqli_fetch_assoc($result))
		{
			$infos_utilisateur['pseudo'] = true;
			$ok = false;
			mysqli_free_result($result);
		}
		$requete = "SELECT pass FROM members WHERE pseudo='".$donnees['pseudo']."'";
		$result = mysqli_query($bdd, $requete);
		$var = mysqli_fetch_assoc($result);
		if (!($result)){
			$ok = false;
		}else{
			if(!(password_verify($donnees['password'],$var['pass'])))
			{
				$infos_utilisateur['password'] = true;
				$ok = false;
			}
		}
		mysqli_free_result($result);
		mysqli_close($bdd);

		return $ok;
	}

	function afficher_page(&$donnees, &$erreurs_requis, &$infos_utilisateur) {
		$erreurs = erreurs($erreurs_requis,$infos_utilisateur);
		afficher_formulaire($donnees, $erreurs);
	}

	function erreurs(&$erreurs_requis,&$infos_utilisateur) {
		$erreurs = array();
		
		if(isset($infos_utilisateur['pseudo']))
			$erreurs['pseudo'] = "Ce pseudo n'existe pas.";
		if(isset($infos_utilisateur['password']))
			$erreurs['password'] = "Le mot de passe est erron&eacute;.";
		
		foreach ($erreurs_requis as $champ => $val) {
			$erreurs[$champ] = " Ce champ est requis !";
		}

		return $erreurs;
	}

	function afficher_formulaire(&$donnees, &$erreurs) {
			global $REQUIS;

			$class = array();

			foreach($REQUIS as $champ => $clee) { // On parcours tous les champs
				if(isset($erreurs[$champ])) // Ensuite, on applique une class differente en cas d'erreurs.
					$class[$champ] = "wrong_input";
				else {
					$class[$champ] = "default_input";
					$erreurs[$champ] = false; // Un echo d'une variable à false n'affiche rien. (Evite de faire des if(isset$erreurs['blabla']) plus bas...)
				}
			}

			?>
			<article>
				<h1 id="titre">Connectez-vous !</h1>
				<form action="login.php" method="POST">
					<fieldset class="login">
					<legend>Formulaire de connexion</legend>
					<label for="pseudo">Nom d'utilisateur<em>*</em></label><br/>
					<input type="text" id="pseudo" class="<?php echo $class['pseudo'] ?>"  placeholder="Pseudo" name="pseudo" value="<?php echo $donnees['pseudo']; ?>" required />
					<span class="error"> <?php echo $erreurs['pseudo']; ?> </span><br/>
					<label for="password">Mot de passe<em>*</em></label><br/>
					<input type="password" id="password" class="<?php echo $class['password'] ?>" placeholder="Password" name="password" required /> <!-- Il faut au minimum une lettre maj, un chiffre et un ! ou un + -->
					<span class="error"> <?php echo $erreurs['password']; ?> </span><br/>
					</fieldset>
					<input type="submit" id="bouton-connexion" value="Se connecter" name="bouton-connexion"/>
					
				</form>
			</article>
	<?php
	}
?>
