-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 13 mars 2024 à 21:00
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12
CREATE DATABASE IF NOT EXISTS balance_ton_bully;

USE balance_ton_bully;

-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS roles, reponses_forum, rendez_vous, sujets_forum, dons, professionnels_sante, utilisateurs, actualites,
    administrateurs;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `balance_ton_bully`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

CREATE TABLE `actualites` (
  `id_actualite` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `contenu` text NOT NULL,
  `lien_article` varchar(255) DEFAULT NULL,
  `date_publication` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id_actualite`, `titre`, `photo`, `contenu`, `lien_article`, `date_publication`) VALUES
(1, 'Titre de l''actualité 1', 'photo1.jpg', 'Contenu de l''actualité 1', 'lien1', '2024-01-01 00:00:00'),
(2, 'Titre de l''actualité 2', 'photo2.jpg', 'Contenu de l''actualité 2', 'lien2', '2024-01-02 00:00:00'),
(3, 'Titre de l''actualité 3', 'photo3.jpg', 'Contenu de l''actualité 3', 'lien3', '2024-01-03 00:00:00'),
(4, 'Titre de l''actualité 4', 'photo4.jpg', 'Contenu de l''actualité 4', 'lien4', '2024-01-04 00:00:00'),
(5, 'Titre de l''actualité 5', 'photo5.jpg', 'Contenu de l''actualité 5', 'lien5', '2024-01-05 00:00:00'),
(6, 'Titre de l''actualité 6', 'photo6.jpg', 'Contenu de l''actualité 6', 'lien6', '2024-01-06 00:00:00'),
(7, 'Titre de l''actualité 7', 'photo7.jpg', 'Contenu de l''actualité 7', 'lien7', '2024-01-07 00:00:00'),
(8, 'Titre de l''actualité 8', 'photo8.jpg', 'Contenu de l''actualité 8', 'lien8', '2024-01-08 00:00:00'),
(9, 'Titre de l''actualité 9', 'photo9.jpg', 'Contenu de l''actualité 9', 'lien9', '2024-01-09 00:00:00'),
(10, 'Titre de l''actualité 10', 'photo10.jpg', 'Contenu de l''actualité 10', 'lien10', '2024-01-10 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE `administrateurs` (
  `id_administrateur` int(11) NOT NULL,
  `nom_administrateur` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe_hash` varchar(255) NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `administrateurs`
--

INSERT INTO `administrateurs` (`id_administrateur`, `nom_administrateur`, `email`, `mot_de_passe_hash`, `date_creation`) VALUES
(1, 'Admin1', 'admin1@email.fr', '$2y$10$Qp/BmnoWpXkbQ8FO1jmR.eEE2EFK5qKFXxqFBkI/FU6GxW1fuJLli', '2024-01-01 00:00:00'),
(2, 'Admin2', 'admin2@email.fr', '$2y$10$vAsCqertK6ec6NeKkt5R1.efqnV9xPFXGsz0McdeaBlYpeYQxWFja', '2024-01-02 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `dons`
--

CREATE TABLE `dons` (
  `id_don` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_don` datetime NOT NULL,
  `recurrent` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reponses_forum`
--

CREATE TABLE `reponses_forum` (
  `id_reponse` int(11) NOT NULL,
  `id_sujet` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'Admin'),
(2, 'User'),
(3, 'Pro');

-- --------------------------------------------------------

--
-- Structure de la table `sujets_forum`
--

CREATE TABLE `sujets_forum` (
  `id_sujet` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime NOT NULL,
  `date_mise_a_jour` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `photo_avatar` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `firstName`, `name`, `userName`, `mail`, `photo_avatar`, `password`, `id_role`) VALUES
(35, 'Kevin', 'Pereira', 'xalos', 'aze@xyz.fr', NULL, '$2y$10$DSyoBE7gUisygeTb6UO6Q.NvlYW9XhTfflWMQENE9h1JbJidj7kIa', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actualites`
--
ALTER TABLE `actualites`
  ADD PRIMARY KEY (`id_actualite`);

--
-- Index pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  ADD PRIMARY KEY (`id_administrateur`);

--
-- Index pour la table `dons`
--
ALTER TABLE `dons`
  ADD PRIMARY KEY (`id_don`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `reponses_forum`
--
ALTER TABLE `reponses_forum`
  ADD PRIMARY KEY (`id_reponse`),
  ADD KEY `id_sujet` (`id_sujet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sujets_forum`
--
ALTER TABLE `sujets_forum`
  ADD PRIMARY KEY (`id_sujet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actualites`
--
ALTER TABLE `actualites`
  MODIFY `id_actualite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `administrateurs`
--
ALTER TABLE `administrateurs`
  MODIFY `id_administrateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `dons`
--
ALTER TABLE `dons`
  MODIFY `id_don` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `reponses_forum`
--
ALTER TABLE `reponses_forum`
  MODIFY `id_reponse` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `sujets_forum`
--
ALTER TABLE `sujets_forum`
  MODIFY `id_sujet` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `dons`
--
ALTER TABLE `dons`
  ADD CONSTRAINT `dons_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `reponses_forum`
--
ALTER TABLE `reponses_forum`
  ADD CONSTRAINT `reponses_forum_ibfk_1` FOREIGN KEY (`id_sujet`) REFERENCES `sujets_forum` (`id_sujet`),
  ADD CONSTRAINT `reponses_forum_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `sujets_forum`
--
ALTER TABLE `sujets_forum`
  ADD CONSTRAINT `sujets_forum_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
