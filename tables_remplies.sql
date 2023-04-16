-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : Dim 23 mai 2021 à 13:08
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id_publi` int(11) NOT NULL,
  `id_member` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`id_publi`, `id_member`) VALUES
(14, 14),
(15, 14),
(16, 10),
(17, 12),
(18, 10),
(19, 12),
(17, 14),
(19, 10),
(15, 13),
(19, 13);

-- --------------------------------------------------------

--
-- Structure de la table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_inscription` datetime NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `members`
--

INSERT INTO `members` (`id`, `pseudo`, `pass`, `email`, `date_inscription`, `admin`) VALUES
(14, 'Louise', '$2y$10$PgqYjYoAEDGQ.NmUxaejj.LaaK4aB2h3c/TqKijjOD19BY0.J7siK', 'louise@gmail.com', '2021-05-15 23:49:01', 0),
(13, 'Antoine', '$2y$10$PGX1Rnt2YANT8RiylSpkq.P4hiUEQ0xBCbxxoM.HaqrWd9V2Ln3ka', 'antoine@gmail.com', '2021-05-15 23:47:29', 0),
(12, 'thomas', '$2y$10$1O/KD4eoS5il9seSvPDg.uk9VwRST8xp6RnALvq/QXQALlcnJQPCu', 'thomas@gmail.com', '2021-05-15 21:24:30', 1),
(11, 'chloe_space', '$2y$10$yG.EXQlzDWs.9UxIKJHXxeCKNmoe6DqrvTTjTkRTjrHJ0k6Hn5tDm', 'chloe.space@gmail.com', '2021-05-15 21:03:02', 0),
(10, 'lucien', '$2y$10$qqMCliSwPfiXQqv4Lg00j.i21FVXFehVf4DisK//YKWIoCKNEQXnC', 'lucien.prc@gmail.com', '2021-05-15 20:36:25', 1);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sender` int(11) NOT NULL,
  `id_recipient` int(11) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `id_sender`, `id_recipient`, `message`, `date`) VALUES
(6, 11, 10, 'Salut', '2021-05-15 21:21:12'),
(7, 11, 10, 'On se voit demain ?\\r\\n', '2021-05-15 21:21:33'),
(27, 10, 11, 'Oui pourquoi pas !', '2021-05-22 19:45:07'),
(9, 14, 11, 'Salut comment ça va ?\\r\\n', '2021-05-15 23:49:48'),
(10, 14, 10, 'Salut\\r\\n', '2021-05-15 23:50:18'),
(16, 10, 14, 'Salut', '2021-05-15 23:55:15'),
(17, 10, 14, 'Tu vas bien ?\\r\\n', '2021-05-15 23:55:29'),
(18, 10, 14, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2021-05-15 23:56:28'),
(26, 10, 14, 'Super ! Merci.', '2021-05-22 19:44:53'),
(20, 14, 10, 'Donec et nisl id sapien blandit mattis. Aenean dictum odio sit amet risus. Morbi purus. Nulla a est sit amet purus venenatis iaculis. Vivamus viverra purus vel magna. Donec in justo sed odio malesuada dapibus. Nunc ultrices aliquam nunc. Vivamus facilisis pellentesque velit. Nulla nunc velit, vulputate dapibus, vulputate id, mattis ac, justo. Nam mattis elit dapibus purus. Quisque enim risus, congue non, elementum ut, mattis quis, sem. Quisque elit.', '2021-05-15 23:57:51'),
(25, 14, 10, 'Très bien et toi ?', '2021-05-22 19:44:21'),
(24, 14, 11, 'alors ?\\r\\n', '2021-05-16 21:32:34');

-- --------------------------------------------------------

--
-- Structure de la table `publications`
--

DROP TABLE IF EXISTS `publications`;
CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_member` int(11) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `publications`
--

INSERT INTO `publications` (`id`, `id_member`, `description`, `date`) VALUES
(13, 10, 'Mon chien Milou !!!', '2021-05-15 20:43:10'),
(14, 10, 'Les Cosses !!!', '2021-05-15 20:50:28'),
(15, 11, 'Besoin d\\\'espace...', '2021-05-15 21:06:11'),
(16, 11, 'Les planètes c\\\'est chouette !!!', '2021-05-15 21:12:55'),
(17, 11, 'Je veux devenir une star !', '2021-05-15 21:19:21'),
(18, 12, 'Salut ! Premier message sur mon compte.', '2021-05-15 21:25:25'),
(19, 12, 'Miam !', '2021-05-15 21:26:39');

-- --------------------------------------------------------

--
-- Structure de la table `subscription`
--

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE IF NOT EXISTS `subscription` (
  `id_member` int(11) NOT NULL,
  `id_follower` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `subscription`
--

INSERT INTO `subscription` (`id_member`, `id_follower`) VALUES
(1, 2),
(2, 4),
(4, 2),
(5, 4),
(3, 4),
(4, 6),
(6, 4),
(4, 9),
(10, 11),
(11, 10),
(11, 12),
(11, 14),
(10, 14),
(12, 10);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
