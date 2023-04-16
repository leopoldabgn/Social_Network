<?php

	$REQUIS = array( // Champs requis.
		'pseudo' => true,
		'password' => true,
		'password2' => true,
		'email' => true);

	function preTraiterChamp($champ) {
		if (!empty($champ)) {
			$champ = trim($champ);
			$champ = htmlentities($champ);
		}
		return $champ;
	}

	function preTraiter(&$donnees) {
		$donnees["pseudo"] = preTraiterChamp($donnees["pseudo"]);
		$donnees["password"] = preTraiterChamp($donnees["password"]);
		$donnees["password2"] = preTraiterChamp($donnees["password2"]);
		$donnees["email"] = preTraiterChamp($donnees["email"]);
	}

	function escape($bdd, $str) {
		return mysqli_real_escape_string($bdd, $str);
	}

	function verifierRequis(&$donnees, &$erreurs) { // On verifie si les champs requis sont bien remplis.
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

	function checkPseudo($pseudo) { // On verifie si le pseudo respecte bien les règles.
		return preg_match('/^[A-Za-z0-9]*$/', $pseudo) && strlen($pseudo) >= 3 && strlen($pseudo) <= 15;
	}

	function checkPassword($pwd) { // On verifie si le mdp respecte bien les règles.
		return preg_match('/^(?=.*[!@#$%+-])(?=.*[0-9])(?=.*[A-Z]).{4,15}$/', $pwd);
	}

	function remplir_valeurs_defaut(&$donnees) {
		$donnees['pseudo'] = false;
		$donnees['password'] = false;
		$donnees['password2'] = false;
		$donnees['email'] = false;
	}

	function verifierFormat(&$donnees, &$erreurs) {
		$ok = true;
		if (!checkPassword($donnees['password'])) {
			$erreurs['password'] = true;
			$ok = false;
		}
		
		if (!checkPseudo($donnees['pseudo'])) {
			$erreurs['pseudo'] = true;
			$ok = false;
		}
		
		if ($donnees['password'] != $donnees['password2']) {
			$erreurs['password2']= true;
			$ok = false;
		}
		return $ok;
	}

	function verifierBDD(&$donnees, &$erreurs) { // On verifie si l'email ou le pseudo n'existe pas deja dans la base.
		$ok = true;
		$bdd = connexionBDD();
		
		$requete = "SELECT pseudo FROM members WHERE pseudo='".escape($bdd, $donnees['pseudo'])."'";
		$result = mysqli_query($bdd, $requete);
		
		if(mysqli_fetch_assoc($result))
		{
			$erreurs['pseudo'] = true;
			$ok = false;
			mysqli_free_result($result);
		}
		
		$requete = "SELECT email FROM members WHERE email='".escape($bdd, $donnees['email'])."'";
		$result = mysqli_query($bdd, $requete);

		if(mysqli_fetch_assoc($result)) // On verifie aussi pour l'adresse email
		{
			$erreurs['email'] = true;
			$ok = false;
		}
		mysqli_free_result($result);

		mysqli_close($bdd);
		
		return $ok;
	}
	// En arrivant ici, le pseudo, le mdp et l'email sont forcement non nuls. Ils ont aussi été traité pour eviter l'injection SQL.
	function injecterDansBDD(&$donnees) {
		$hash = password_hash($donnees['password'], PASSWORD_DEFAULT); // on hashe le mot de passe
		
		$bdd = connexionBDD(); // On se connecte à la BDD.
		
		$req_pre = "INSERT INTO members(pseudo, pass, email, date_inscription) VALUES(?, ?, ?, NOW())"; // On cree un nouveau champ dans la table members de notre base de données.
		$req_pre = mysqli_prepare($bdd, $req_pre);
		/* Preparation de la requête */
		mysqli_stmt_bind_param($req_pre, 'sss', escape($bdd, $donnees['pseudo']), $hash, escape($bdd, $donnees['email']));
			
		/* Exécution de la requête */
		mysqli_stmt_execute($req_pre);
			
		/* Fermeture du traitement */
		mysqli_stmt_close($req_pre);
			
		mysqli_close($bdd);
	}

	function afficher_page(&$donnees, &$erreurs_requis, &$erreurs_format, &$infosDejaUtilise) {
		$erreurs = erreurs($erreurs_requis, $erreurs_format, $infosDejaUtilise);
		afficher_formulaire($donnees, $erreurs);
	}

	function erreurs(&$erreurs_requis, &$erreurs_format, &$infosDejaUtilise) { // Renvoie un tableau avec les differentes erreurs.
		$erreurs = array();
		
		if(isset($erreurs_format["password"])) {
			$erreurs["password"] = ' <p>Le mot de passe doit contenir : </p><ul>'.
			'<li>Un chiffre</li>'.
			'<li>Une lettre minuscule</li>'.
			'<li>Une lettre majuscule</li>'.
			'<li>Un caract&egrave;re sp&eacute;cial parmis !@#$%+-</li>'.
			'<li>Entre 4 et 15 caract&egrave;res</li></ul>';
		}
		if(isset($erreurs_format["password2"]))
			$erreurs["password2"] = "<p>Les mots de passe ne correspondent pas !</p>";
		if(isset($erreurs_format["pseudo"]))
			$erreurs["pseudo"] = "<p>Le nom d'utilisateur ne doit pas contenir".
			" de caract&egrave;res sp&eacute;ciaux et doit avoir une taille".
			" entre 3 et 15 caract&egrave;res !</p>";
		
		if(isset($infosDejaUtilise['pseudo']))
			$erreurs['pseudo'] = "<p>Ce pseudo est d&eacute;j&agrave; utilis&eacute; !</p>";
		if(isset($infosDejaUtilise['email']))
			$erreurs['email'] = "<p>Cette email est d&eacute;j&agrave; utilis&eacute; !</p>";
		
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
			<h1 id="titre">Inscrivez-vous !</h1>
			<form action="inscription.php" method="POST">
				<fieldset class="formulaire-inscription">
				<legend>Formulaire d'incription</legend>
				<label for="pseudo">Nom d'utilisateur<em>*</em></label><br/>
				<input type="text" id="pseudo" class="<?php echo $class['pseudo'] ?>"  placeholder="Au moins 3 caract&egrave;res..." name="pseudo" value="<?php echo $donnees['pseudo']; ?>" required />
				<div class="error"> <?php echo $erreurs['pseudo']; ?> </div><br/>
				<label for="password">Mot de passe<em>*</em></label><br/>
				<input type="password" id="password" class="<?php echo $class['password'] ?>" placeholder="Au moins 4 caract&egrave;res..." name="password" required />
				<div class="error"> <?php echo $erreurs['password']; ?> </div><br/>
				<label for="password2">Confirmez le mot de passe<em>*</em></label><br/>
				<input type="password" id="password2" class="<?php echo $class['password2'] ?>" name="password2" required />
				<div class="error"> <?php echo $erreurs['password2']; ?> </div><br/>
				<label for="email">Adresse e-mail<em>*</em></label><br/>
				<input type="email" id="email" class="<?php echo $class['email'] ?>" placeholder="exemple@domaine.com" name="email" value="<?php echo $donnees['email']; ?>" required />
				<div class="error"> <?php echo $erreurs['email']; ?> </div><br/>
				</fieldset>
				<input type="submit" id="bouton-inscription" value="S'inscrire" name="bouton-inscription"/>
			</form>
		</article>
<?php } 
		
?>