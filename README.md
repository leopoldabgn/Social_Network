
----------------------------------------------
Projet Reseau Social :
----------------------------------------------

BASE DE DONNEE:

Un fichier sql est présent avec la structure de toutes les tables necessaire.
Pour modifier les données de connexion à la base de donnée, il faut modifier le fichier
Include/BDD/connexionBDD.php
Ce fichier est inclut sur toutes les pages du site. Il contient une fonction pour se connecter
à la base.

UTILISATION DU SITE :

Pour faire fonctionner le site, il faut tout d'abord mettre le dossier de notre projet dans XAMPP ou WAMP.
Ensuite, pour accéder au site, il suffit de mettre le lien du dossier dans l'url.
Par exemple : localhost/Projet.
Il y un fichier "index.php" qui vous redirigera vers la page de login (si vous n'êtes pas connecté, sinon c'est la page d'accueil).
Normalement, n'importe quelle page du site redirige vers le login. Si ça ne fonctionne pas,
vous pouvez aller directement sur la page de login:
localhost/Projet/Login/login.php

Il y a alors une page de connexion. Pour vous créer un compte, il faut cliquer sur "m'inscrire" en haut à droite.
Cela vous redirigera vers la page d'inscription. 


Inscription :

Pour s'inscrire, il faut remplir tous les champs avec de bonnes informations. En cas d'échec, un message d'erreur s'affiche pour vous guider. 
Pour poster le formulaire, un bouton est situé plus bas. Si vous n'avez pas rempli tous les champs, vous ne pourrez pas poster le formulaire.
Si vous avez réussi votre inscription, vous serez redirigé vers la page de login.
Vous pouvez également acceder à la page de login en cliquant sur "se connecter" situé en haut à droite de la page.


Login :

Pour se connecter, il suffit de remplir les deux champs de texte (pseudo et mdp) et de poster
en cliquant sur le bouton situé plus bas. Vous êtes alors redirigé vers l'accueil.

/////

Une barre de navigation est alors présente en haut de la page avec dans l'ordre: 

(Barre de recherche), Accueil, Profil, Publier, Messagerie, (bouton de déconnexion)

/////


Barre de recherche :

Pour faire une recherche, il suffit d'écrire dans le champ texte et de poster la recherche
en cliquant sur le bouton d'envoi. Cela vous redirige sur une nouvelle page avec les résultats
et une autre barre pour continuer vos recherches. 
Si vous cliquez sur envoyer sans rien préciser dans la barre, alors la page affichera plusieurs comptes choisis aléatoirement.

Une flèche de retour est présente en haut à gauche de la page et permet de revenir sur la page d'accueil.

Pour aller sur la page d'un utilisateur trouvé dans une recherche, il suffit de cliquer sur son pseudo. 
Vous serez alors redirigé vers sa page de profil.


Accueil:

L'accueil affiche toutes les publications des personnes à qui vous êtes abonnés.
Pour chaque publication, vous pouvez la liker/disliker, visiter le profil de son créateur en cliquant sur son pseudo, 
et, si vous êtes admin, la supprimer.


Profil :

La page de profil d’un utilisateur affiche les publications que l’utilisateur a postées. 
Les publications sont affichées de la même manière que sur la page d'accueil.

Si vous êtes sur la page de profil de quelqu'un :
Il y a alors 2 boutons en haut de la page:
- “Follow” (ou “Unfollow” si vous êtes déjà abonné).
- “Écrire” (pour envoyer un message à la personne).

Il y a un troisième bouton si vous êtes administrateur :
- nommer administrateur (ou “déjà administrateur” si l’utilisateur est administrateur, pour enlever l’administrateur de cet utilisateur.)

De plus, si vous êtes sur votre page ou si vous êtes administrateur, vous pouvez cliquer sur la poubelle situé à côté de chaque publication
pour les supprimer.


Publier :

La page de publication permet de poster une publication. On peut ajouter une image (mais ce n’est pas obligatoire), 
et mettre une description (obligatoire). Il y a ensuite un bouton pour envoyer.


Messagerie :

Affiche les dernières conversations que vous avez eues. 
Vous pouvez cliquer sur une des conversations pour continuer une discussion avec une personne du site. 
Vous êtes alors rediriger vers la page de discussion.


Discussion :

La page de discussion permet de parler avec d'autres personnes présentes sur le site.
Elle est accessible en cliquant sur le bouton "Écrire" d'une page de profil ou en cliquant
sur une de ces anciennes conversations dans la messagerie.

Pour envoyer un nouveau message, il suffit de remplir le champ de texte situé en bas de la page.
Il faut cliquer ensuite sur l'avion en papier pour l'envoyer.
On peut aussi supprimer un ancien message. Pour cela il faut:
- Laisser la souris sur le message pendant 1s.
- Cliquer sur la poubelle qui apparaît sur la gauche du message.

Une flèche de retour est présente en haut à gauche de la page et permet de revenir sur la messagerie.


Bouton de déconnexion:

Situé à droite de la barre de navigation. Il vous permet de vous déconnecter et vous redirige vers la page de login.

/////////////


Système d'administration :

Si votre base de données est vide, il faudra dans un premier temps créer un membre depuis la page d'inscription.
Il faut ensuite modifier directement dans la base de donnée la valeur du champ "admin" de votre utilisateur
dans la table members. Il faut le mettre à "1".

Une fois cela effectué pour un utilisateur, on peut ensuite nommer d'autres administrateurs. 
Pour cela il faut se connecter avec le compte précédemment créé, 
puis aller sur le profil de la personne qu'on veut nommer administrateur.
Il suffit ensuite de cliquer sur "nommer admin". Le bouton devient alors "déjà admin". 
Si on clique à nouveau, on peut retirer les permissions d'administration.

Un admin peut également supprimer n'importe quelle publication en cliquant sur la poubelle qui s'affiche sur les publications
des utilisateurs.
