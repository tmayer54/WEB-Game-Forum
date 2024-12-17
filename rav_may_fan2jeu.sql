-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 26 avr. 2023 à 21:32
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS rav_may_fan2jeu;

CREATE DATABASE IF NOT EXISTS rav_may_fan2jeu DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE rav_may_fan2jeu;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rav_may_fan2jeu`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `id_post` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `contenu` varchar(10000) NOT NULL,
  `date_commentaire` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_post`, `id_utilisateur`, `contenu`, `date_commentaire`) VALUES
(1, 4, 2, 'Pwaa trop la classe. C\'est plus une maison mais un palace à ce niveau !!', '2023-04-26 15:41:37'),
(2, 7, 3, 'Pwa le perso cancer. J\'aurai honte à ta place', '2023-04-26 15:43:20'),
(3, 5, 3, 'Flem de chercher. Balance la réponse !', '2023-04-26 15:44:08'),
(4, 1, 3, 'C\'etait bien en 2010. Faut tourner la page', '2023-04-26 15:45:12'),
(5, 4, 3, 'Forcement une photo prise sur Internet. T\'as pas le niveau pour ca. Décevant...', '2023-04-26 15:45:48'),
(6, 10, 1, 'Trop bien ! C\'est quoi ton meilleur score ? ', '2023-04-26 19:29:36'),
(7, 5, 1, 'J\'ai pas trouvé. Je peux avoir un indice ?', '2023-04-26 19:29:56');

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `contenu` varchar(10000) NOT NULL,
  `imgPresentation` varchar(100) DEFAULT 'images/post/default.png',
  `date_post` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id_post`, `id_utilisateur`, `titre`, `contenu`, `imgPresentation`, `date_post`) VALUES
(1, 1, 'Minecraft c\'est trop bien', 'En plus le jeu il a de très bonne notes', 'images/post/default.png', '2023-04-26 15:15:42'),
(2, 1, 'Ma pp', 'Vous l\'attendiez ! \r\nVoila l\'image de ma pp ! Elle vient tout droit du nether. Quelle aventure !', 'images/post/2.png', '2023-04-26 15:16:13'),
(3, 1, 'Victoire', 'Hier j\'ai tué l\'enderdragon pour la première fois !!', 'images/post/3.png', '2023-04-26 15:18:43'),
(4, 1, 'Ma maison', 'Voila une image de ma premiere maison ! \r\ndites moi ce que vous en pensez.', 'images/post/4.jpg', '2023-04-26 15:20:14'),
(5, 2, 'Mon pseudo', 'Oui, mon pseudo est une référence à League of Legends, félicitation à la personne qui trouvera !!', 'images/post/default.png', '2023-04-26 15:35:08'),
(6, 2, 'Match de fou', 'Vous avez vu le dernier match de la LFL ? Celui ou l\'équipe de fou la elle a vaincu l\'autre équipe. Vraiment magique comme moment', 'images/post/default.png', '2023-04-26 15:40:13'),
(7, 2, 'Teemo', 'Mon personnage préféré c\'est Teemo, il est beaucoup trop mignong. \r\nEt vous, c\'est lequel ? ', 'images/post/7.jpg', '2023-04-26 15:41:05'),
(8, 3, 'Test', 'à tous ceux qui liront ce post, sachez que je ne suis pas votre ami. ', 'images/post/default.png', '2023-04-26 15:47:00'),
(9, 3, 'Critique objective', 'Franchement trop naze les personnes sur ce blog', 'images/post/default.png', '2023-04-26 16:10:27'),
(10, 4, 'PacMan', 'Saviez-vous que la première version de Pacman est sortie en 1980 ?', 'images/post/default.png', '2023-04-26 17:04:03'),
(13, 5, 'Animal Crossing', 'Quel était votre premier jeu Animal Crossing?\r\nJ\'ai commencé à jouer à Animal Crossing Wild Word sur DS. Ce jeu est sorti en 2005!', 'images/post/13.jpg', '2023-04-26 18:53:32'),
(15, 1, 'Mon ressenti.', 'J\'ai vu des commentaires honteux sous l\'un de mes derniers posts, c\'est vraiment pas gentil. Ca me donne envie de ne plus revenir ici :c', 'images/post/default.png', '2023-04-26 18:59:30'),
(16, 1, 'Impossible', 'En fait non. \r\nJe peux pas me passer de poster mes aventures ici. Je ferai avec les critiques négatives ! ', 'images/post/default.png', '2023-04-26 19:00:05'),
(17, 1, 'Je dois avouer', 'Suite aux accusations qui m\'ont été faites, je me sens obligé de dire la vérité. Cette image est celle qui apparait sur le post\r\nOui, j\'ai pris une image sur Internet pour la faire passe comme ma maison. C\'est celle qui apparait dans ce post', 'images/post/17.jpg', '2023-04-26 19:25:18'),
(18, 1, 'Merci', 'Je vous remercie de me pardonner pour le post de ma \"fausse maison\". \r\nVoici en réalité ma maison actuelle !! ', 'images/post/18.jpg', '2023-04-26 19:21:13'),
(19, 1, 'Nouvelle maison ! ', 'Je suis passé à une maison un peu plus moderne. \r\nAge de pierre, me voila ! ', 'images/post/19.png', '2023-04-26 19:26:27'),
(20, 1, 'Ender dragon ! ', 'Cette fois ci, j\'ai vraiment vaincu l\'ender dragon. La dernière fois j\'avais juste ouvert le portail et j\'etais mort dans l\'end. ', 'images/post/default.png', '2023-04-26 19:27:08'),
(21, 1, 'Jouons ensemble ', 'je viens d\'avoir une idée (oui ca arrive)\r\nca vous dit d\'ouvrir un serveur ensemble ? \r\nSans Haterz car il est pas l\'air sympa.', 'images/post/default.png', '2023-04-26 19:28:04');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `date_naissance` date NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pseudo` varchar(20) NOT NULL,
  `mdp` varchar(50) NOT NULL,
  `avatar` varchar(100) DEFAULT 'images/avatar/default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `date_naissance`, `nom`, `prenom`, `email`, `pseudo`, `mdp`, `avatar`) VALUES
(1, '2013-03-15', 'Boy', 'Minecraft', 'minecraft.boy@gmail.com', 'MinecraftBoy2013', '*6665C221D097110B3BCF38EFC24E383308479FD3', 'images/avatar/1.png'),
(2, '1996-05-07', 'LFS', 'GB', 'gb.lfs@gmail.com', 'GBLFS', '*0D3AC9757794504F14442BF1E88F76CA4423C983', 'images/avatar/2.jpg'),
(3, '1950-01-01', 'terz', 'Ha', 'ha.tez@gmail.com', 'Haterz', '*FDE0E6CA7051F0896E0155CD1915592C360D6943', 'images/avatar/default.png'),
(4, '1980-07-19', 'Man', 'Pac', 'Pac.man@gmail.com', 'PacMaNFAN', '*70E2BA5C1FA90FD40FCD62B1426E5B7C19D6B3AF', 'images/avatar/4.jpg'),
(5, '1998-10-01', 'elephant', 'Fanny', 'fanny.elephant@gmail.com', 'Fanny♥', '*C2BC95191EC66EA745AC994A92EC3175AE2C811B', 'images/avatar/5.png');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_post` (`id_post`);

--
-- Index pour la table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `pseudo` (`pseudo`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `post`
--
ALTER TABLE `post`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE,
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_post`) REFERENCES `post` (`id_post`) ON DELETE CASCADE;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
