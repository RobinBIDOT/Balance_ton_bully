-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 15 mars 2024 à 15:17
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

-- Création de la base de données si elle n'existe pas
CREATE DATABASE IF NOT EXISTS balance_ton_bully;
USE balance_ton_bully;

-- Suppression des tables si elles existent déjà pour éviter les conflits lors de la création
DROP TABLE IF EXISTS roles, reponses_forum, rendez_vous, sujets_forum, dons, professionnels_sante, utilisateurs, actualites, administrateurs;

-- Paramétrage de la session SQL
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Configuration des caractères
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `balance_ton_bully`
--

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
                         `id` int(11) NOT NULL AUTO_INCREMENT,
                         `role` varchar(255) NOT NULL,
                         PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
                                       (1, 'Admin'),
                                       (2, 'User'),
                                       (3, 'Pro');

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
                                `id` int(11) NOT NULL AUTO_INCREMENT,
                                `firstName` varchar(255) NOT NULL,
                                `name` varchar(255) NOT NULL,
                                `userName` varchar(255) NOT NULL,
                                `mail` varchar(255) NOT NULL,
                                `photo_avatar` varchar(255) DEFAULT '/Balance_ton_bully/assets/avatarProfil.png',
                                `password` varchar(255) NOT NULL,
                                `id_role` int(11) NOT NULL,
                                PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`

--

INSERT INTO `utilisateurs` (`firstName`, `name`, `userName`, `mail`, `password`, `id_role`) VALUES
    ('prenom1', 'nom1', 'pseudo1', 'pseudo1@mail.fr', '$2y$10$eL7STk7AnwnYQoaI7.YiS.68IQWJ44tGCw3XnNQt6Nk.wAMKyzMO6', 2),
    ('prenom2', 'nom2', 'pseudo2', 'pseudo2@mail.fr', '$2y$10$e350XdDmQssJ5P3GIhjVKe1cOoDZgLQC75ymU0srT6QHIDG8E3ORG', 2),
    ('prenom3', 'nom3', 'pseudo3', 'pseudo3@mail.fr', '$2y$10$sTMcvPGEmTe3spB73nK0A.FC7Ebf3vJseidOwB5YTxwFictID7Vbu', 2),
    ('prenom4', 'nom4', 'pseudo4', 'pseudo4@mail.fr', '$2y$10$Xqteqn6GraU8oDIG4X06pO1uISh6e3jiVcfQl4NssEp5Cw3rJfLDW', 2),
    ('prenom5', 'nom5', 'pseudo5', 'pseudo5@mail.fr', '$2y$10$FvdmqaDfGjPFj25kP4KbV.6CwMMzPG3WPsku9BTyGFNLtB4p2ZZOO', 2),
    ('prenom6', 'nom6', 'pseudo6', 'pseudo6@mail.fr', '$2y$10$/fNDYnBMJEAU.mYA2s6FmOJNk57U.2fLTHoZsJ/cOPPiMqEDLqVMu', 2),
    ('prenom7', 'nom7', 'pseudo7', 'pseudo7@mail.fr', '$2y$10$JjRQMWt08BmwnPAPCcGjc.pidVLUMhIxOVSQ57eRbdmxMWdPW72pC', 2),
    ('prenom8', 'nom8', 'pseudo8', 'pseudo8@mail.fr', '$2y$10$NfSU/ieCUsik6Az9Jk1/9OVCSVn9/UaE4ztTy8yGaPz5BJ3yjU8mG', 2),
    ('prenom9', 'nom9', 'pseudo9', 'pseudo9@mail.fr', '$2y$10$A4wByyravxpbzKqqsGfjgef.D8HO9C54ovEwYo0lbsG4jIXwqZzwG', 2),
    ('prenom10', 'nom10', 'pseudo10', 'pseudo10@mail.fr', '$2y$10$y84pwmshC7boE0h5vh0lZ.TFSsfEoz596Z01J77uoM5Sjz/uwwQpq', 2),
    ('prenom11', 'nom11', 'pseudo11', 'pseudo11@mail.fr', '$2y$10$GJNOSmF/seMKt.MbocLnyO5XrfYxfQe.V7tU8mn9V1m1PvZg.h88i', 2),
    ('prenom12', 'nom12', 'pseudo12', 'pseudo12@mail.fr', '$2y$10$.UcOLwPGsDAlwa/hPosKEeq3HASHKos4BVkTCHjUAetYj/qbLoNH.', 2),
    ('prenom13', 'nom13', 'pseudo13', 'pseudo13@mail.fr', '$2y$10$dw7PFOkfjUxT5.P.m.v.derLOauvqxOIJWHeSQGe3TaijtJXzHIlG', 2),
    ('prenom14', 'nom14', 'pseudo14', 'pseudo14@mail.fr', '$2y$10$Dd3fU0YkW2T64ArX4UvZEO8oJbaGUP5Vaguxa1JsSNcdZmEaBob2C', 2),
    ('prenom15', 'nom15', 'pseudo15', 'pseudo15@mail.fr', '$2y$10$DrplgAeU.NubftTC82IccuC68tWpU/KuWAyRftWIb2avgpVoTxOZG', 2),
    ('prenom16', 'nom16', 'pseudo16', 'pseudo16@mail.fr', '$2y$10$/oeoBGvq9liWYY3kUUf05eTjfwhDZUvbs9YHDtwICxIXdcGAkE0KO', 2),
    ('prenom17', 'nom17', 'pseudo17', 'pseudo17@mail.fr', '$2y$10$Y0D3AHZYH3UlgKHudeyA6Ohy9QWc/5MZtne9WYu4oUz8gtA5.zMDq', 2),
    ('prenom18', 'nom18', 'pseudo18', 'pseudo18@mail.fr', '$2y$10$OfOQd1K5cz7CR02Yvtu5NeDN/y179TERIePkAKyPsPUY9fecE.yzO', 2),
    ('prenom19', 'nom19', 'pseudo19', 'pseudo19@mail.fr', '$2y$10$jMVeXTjqppkQsDxTXfIIO.aR1AM31s79Vduc1n.vQ4FrnunuDG1kW', 2),
    ('prenom20', 'nom20', 'pseudo20', 'pseudo20@mail.fr', '$2y$10$EfdYWJEuIeEa0E9CeViYaOzO.wz/I2iK/y0BmHYsk3rlzNKnfA8KG', 2),
    ('prenom21', 'nom21', 'pseudo21', 'pseudo21@mail.fr', '$2y$10$2GEk96ayFNCEzyVpfMXCVOmUE9y9O9Ha8A/ZjOc5k//ebNwto2DGW', 2),
    ('prenom22', 'nom22', 'pseudo22', 'pseudo22@mail.fr', '$2y$10$3rT/T/j8561UdL0p6TKTjuO6etHTiPMgNdA6Q.0jHgLjX15A.M62G', 2),
    ('prenom23', 'nom23', 'pseudo23', 'pseudo3@mail.fr', '$2y$10$PTF140NQaR8kP6fxqtTipOvhOrjnX5YBdP8h3vG1RuuwYJhsBa8.K', 2),
    ('prenom24', 'nom24', 'pseudo24', 'pseudo4@mail.fr', '$2y$10$UU03BIfHz/ZwRgi2cKFEmeNwxcOky.9Gxgq7NanFqKesjof1YvBzm', 2),
    ('prenom25', 'nom25', 'pseudo25', 'pseudo5@mail.fr', '$2y$10$5sw4lba8Opdjd9c.2v.mAutsP3p..Bm4OZ8PfHT52dxt28jXU0Wp6', 2),
    ('prenom26', 'nom26', 'pseudo26', 'pseudo6@mail.fr', '$2y$10$LwcZkPC6LQdJG97y/A19aOkcm0P5jm62F/RkXS7kfFWmxdAdKKbYO', 2),
    ('prenom27', 'nom27', 'pseudo27', 'pseudo7@mail.fr', '$2y$10$iiNq2WeRKRS12TssLNqJoepeg36vRRzvW4ZuKZB9DniyLR3vQf1Xy', 2),
    ('prenom28', 'nom28', 'pseudo28', 'pseudo8@mail.fr', '$2y$10$y9l/ZlYu8oVTp./FCJqfiuBbI6B1JwbF7Ae/SpEhmlLys1OE2Dpx.', 2),
    ('prenom29', 'nom29', 'pseudo29', 'pseudo9@mail.fr', '$2y$10$HFRiHJY85ggsbVA97hGuwOepPFV/kFITxpkjky8JR8eq1zTTlbpnq', 2),
    ('prenom30', 'nom30', 'pseudo30', 'pseudo10@mail.fr', '$2y$10$8CUcMi/4cy00xOqbjh4EmO7JsiJsaHZEKFul7tyC94YprijRyVB2m', 2),
    ('Kevin', 'Pereira', 'Xalos', 'aze@xyz.fr', '$2y$10$/nEHgBSMp4gS5P2UcGPj8uZECP9UV3/MVMhlQV76owIarC12h7Yre', 1);
-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

CREATE TABLE `actualites` (
    `id_actualite` int(11) NOT NULL AUTO_INCREMENT,
    `titre` varchar(255) NOT NULL,
    `photo` varchar(255) DEFAULT NULL,
    `contenu` text NOT NULL,
    `lien_article` varchar(255) DEFAULT NULL,
    `date_publication` datetime NOT NULL,
    PRIMARY KEY (`id_actualite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actualites`
--

INSERT INTO `actualites` (`id_actualite`, `titre`, `photo`, `contenu`, `lien_article`, `date_publication`) VALUES
(1, 'Titre de l''actualité 1', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 1', 'lien1', '2024-01-01 00:00:00'),
(2, 'Titre de l''actualité 2', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 2', 'lien2', '2024-01-02 00:00:00'),
(3, 'Titre de l''actualité 3', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 3', 'lien3', '2024-01-03 00:00:00'),
(4, 'Titre de l''actualité 4', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 4', 'lien4', '2024-01-04 00:00:00'),
(5, 'Titre de l''actualité 5', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 5', 'lien5', '2024-01-05 00:00:00'),
(6, 'Titre de l''actualité 6', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 6', 'lien6', '2024-01-06 00:00:00'),
(7, 'Titre de l''actualité 7', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 7', 'lien7', '2024-01-07 00:00:00'),
(8, 'Titre de l''actualité 8', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 8', 'lien8', '2024-01-08 00:00:00'),
(9, 'Titre de l''actualité 9', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 9', 'lien9', '2024-01-09 00:00:00'),
(10, 'Titre de l''actualité 10', '/Balance_ton_bully/assets/actu.jpg', 'Contenu de l''actualité 10', 'lien10', '2024-01-10 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `dons`
--

CREATE TABLE Dons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type_don ENUM('Don ponctuel', 'Don mensuel') NOT NULL,
    montant DECIMAL(10, 2),
    montant_libre DECIMAL(10, 2),
    prenom VARCHAR(50) NOT NULL,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_naissance DATE NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    code_postal VARCHAR(10) NOT NULL,
    ville VARCHAR(100) NOT NULL,
    pays VARCHAR(100) NOT NULL,
    est_organisme BOOLEAN NOT NULL,
    raison_sociale VARCHAR(100),
    siren VARCHAR(9),
    forme_juridique VARCHAR(100),
    date_paiement DATE DEFAULT CURRENT_DATE,
    est_paye BOOLEAN NOT NULL DEFAULT FALSE,
    date_arret_don_mensuel DATE DEFAULT NULL,
    stopper_don_mensuel BOOLEAN DEFAULT FALSE,
    CONSTRAINT CHK_SirenLength CHECK (
        (est_organisme = TRUE AND LENGTH(siren) = 9) OR est_organisme = FALSE
        )
)
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO Dons (type_don, montant, montant_libre, prenom, nom, email, date_naissance, adresse, code_postal, ville, pays, est_organisme, raison_sociale, siren, forme_juridique, date_paiement, est_paye) VALUES
    ('Don ponctuel', 50.00, NULL, 'Alice', 'Dubois', 'alice.dubois@example.com', '1985-04-12', '123 rue de la Paix', '75000', 'Paris', 'France', FALSE, NULL, NULL, NULL, '2024-03-21', TRUE),
    ('Don mensuel', 20.00, NULL, 'Jacques', 'Moreau', 'jacques.moreau@example.com', '1978-08-25', '456 avenue Liberté', '33000', 'Bordeaux', 'France', TRUE, 'Entreprise XYZ', '123456789', 'SARL', '2024-03-21', FALSE),
    ('Don ponctuel', 30.00, NULL, 'Marie', 'Martin', 'marie.martin@example.com', '1980-06-10', '10 rue du Soleil', '69000', 'Lyon', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', NULL, 25.00, 'Paul', 'Dupont', 'paul.dupont@example.com', '1990-11-22', '22 avenue de la Lune', '44000', 'Nantes', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don ponctuel', 75.00, NULL, 'Élise', 'Mercier', 'elise.mercier@example.com', '1975-02-15', '15 boulevard des Fleurs', '34000', 'Montpellier', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don ponctuel', 60.00, NULL, 'Lucas', 'Petit', 'lucas.petit@example.com', '1982-08-30', '30 chemin des Oliviers', '59000', 'Lille', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don mensuel', 45.00, NULL, 'Chloé', 'Rousseau', 'chloe.rousseau@example.com', '1994-05-19', '19 rue des Cerisiers', '67000', 'Strasbourg', 'France', TRUE, 'Compagnie ABC', '987654321', 'SA', '2024-03-22', TRUE),
    ('Don ponctuel', NULL, 55.00, 'Maxime', 'Lefebvre', 'maxime.lefebvre@example.com', '1968-12-28', '28 rue du Chêne', '80000', 'Amiens', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don mensuel', 35.00, NULL, 'Laura', 'Garnier', 'laura.garnier@example.com', '1988-09-09', '9 rue de la Colline', '13090', 'Aix-en-Provence', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don ponctuel', 40.00, NULL, 'Nicolas', 'Bonnet', 'nicolas.bonnet@example.com', '1992-03-14', '14 avenue des Saules', '76000', 'Rouen', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don mensuel', NULL, 50.00, 'Julie', 'Bertrand', 'julie.bertrand@example.com', '1986-01-21', '21 boulevard des Érables', '31000', 'Toulouse', 'France', TRUE, 'Société XYZ', '112233445', 'SAS', '2024-03-22', TRUE),
    ('Don ponctuel', 65.00, NULL, 'Antoine', 'Lopez', 'antoine.lopez@example.com', '1996-07-02', '2 rue des Ormes', '37000', 'Tours', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don mensuel', 30.00, NULL, 'Emma', 'Brun', 'emma.brun@example.com', '1991-10-18', '18 avenue des Peupliers', '21000', 'Dijon', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', NULL, 35.00, 'Sophie', 'Leroy', 'sophie.leroy@example.com', '1992-01-15', '789 boulevard Égalité', '13000', 'Marseille', 'France', FALSE, NULL, NULL, NULL, '2024-03-21', TRUE),
    ('Don ponctuel', 100.00, NULL, 'Nathalie', 'Perrin', 'nathalie.perrin@example.com', '1983-07-12', '12 avenue des Tilleuls', '14000', 'Caen', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', 25.00, NULL, 'Stéphane', 'Leclerc', 'stephane.leclerc@example.com', '1993-02-27', '27 rue du Port', '17000', 'La Rochelle', 'France', TRUE, 'Groupe DEF', '546372819', 'SASU', '2024-03-22', FALSE),
    ('Don ponctuel', NULL, 70.00, 'Isabelle', 'Moulin', 'isabelle.moulin@example.com', '1966-09-16', '16 chemin du Lac', '74000', 'Annecy', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', 55.00, NULL, 'Olivier', 'Fournier', 'olivier.fournier@example.com', '1974-06-08', '8 boulevard des Alpes', '25000', 'Besançon', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don ponctuel', 80.00, NULL, 'Audrey', 'Morel', 'audrey.morel@example.com', '1984-10-31', '31 rue des Acacias', '57000', 'Metz', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', NULL, 45.00, 'Rémi', 'Fontaine', 'remi.fontaine@example.com', '1995-08-20', '20 avenue de la Mer', '29000', 'Quimper', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don ponctuel', 35.00, NULL, 'Catherine', 'Roux', 'catherine.roux@example.com', '1972-03-11', '11 rue des Jonquilles', '72000', 'Le Mans', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', 40.00, NULL, 'François', 'David', 'francois.david@example.com', '1969-05-23', '23 boulevard des Sports', '26000', 'Valence', 'France', TRUE, 'Organisation GHI', '753951846', 'SCOP', '2024-03-22', FALSE),
    ('Don ponctuel', NULL, 65.00, 'Hélène', 'Girard', 'helene.girard@example.com', '1981-12-07', '7 chemin des Dunes', '56000', 'Vannes', 'France', FALSE, NULL, NULL, NULL, '2024-03-22', TRUE),
    ('Don mensuel', 20.00, NULL, 'Arnaud', 'Dumont', 'arnaud.dumont@example.com', '1976-11-17', '17 rue de la Forêt', '88000', 'Épinal','France', FALSE, NULL, NULL, NULL, '2024-03-22', FALSE),
    ('Don mensuel', 15.00, NULL, 'Vincent', 'Simon', 'vincent.simon@example.com', '1989-02-28', '28 rue des Écoles', '54000', 'Nancy', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', TRUE),
    ('Don ponctuel', NULL, 95.00, 'Caroline', 'Blanc', 'caroline.blanc@example.com', '1977-04-03', '3 avenue des Rosiers', '83000', 'Toulon', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', FALSE),
    ('Don mensuel', 10.00, NULL, 'Éric', 'Dupuis', 'eric.dupuis@example.com', '1967-05-20', '20 rue des Pommiers', '64000', 'Pau', 'France', TRUE, 'Organisation JKL', '258147369', 'EURL', '2024-03-23', TRUE),
    ('Don ponctuel', 85.00, NULL, 'Amandine', 'Richard', 'amandine.richard@example.com', '1999-07-14', '14 chemin des Vignes', '68000', 'Colmar', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', FALSE),
    ('Don mensuel', NULL, 60.00, 'Bruno', 'Lemaire', 'bruno.lemaire@example.com', '1971-08-08', '8 boulevard de la Victoire', '16000', 'Angoulême', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', TRUE),
    ('Don ponctuel', 90.00, NULL, 'Fanny', 'Colin', 'fanny.colin@example.com', '1987-10-21', '21 rue des Lilas', '41000', 'Blois', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', FALSE),
    ('Don mensuel', 30.00, NULL, 'Mathieu', 'Barbier', 'mathieu.barbier@example.com', '1979-01-12', '12 avenue de Verdun', '49000', 'Angers', 'France', TRUE, 'Société MNO', '123789456', 'SNC', '2024-03-23', TRUE),
    ('Don ponctuel', NULL, 100.00, 'Véronique', 'Lefevre', 'veronique.lefevre@example.com', '1993-03-31', '31 rue de Bretagne', '72000', 'Le Mans', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', FALSE),
    ('Don mensuel', 40.00, NULL, 'Guillaume', 'Renaud', 'guillaume.renaud@example.com', '1990-09-17', '17 chemin de Provence', '84000', 'Avignon', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', TRUE),
    ('Don ponctuel', 70.00, NULL, 'Clémence', 'Thomas', 'clemence.thomas@example.com', '1973-06-06', '6 rue de Normandie', '80000', 'Amiens', 'France', FALSE, NULL, NULL, NULL, '2024-03-23', FALSE);

-- --------------------------------------------------------

--
-- Structure de la table `sujets_forum`
--

CREATE TABLE `sujets_forum` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_utilisateur` int(11) NOT NULL,
    `titre` varchar(255) NOT NULL,
    `contenu` text NOT NULL,
    `date_creation` datetime NOT NULL,
    `date_mise_a_jour` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Insertion de 20 sujets de forum en une seule requête
INSERT INTO sujets_forum (id_utilisateur, titre, contenu, date_creation) VALUES
    (30, 'Témoignage de harcèlement', 'Lorem ipsum dolor sit amet', '2024-03-04 00:00:00'),
    (5, 'Expérience d''un parent', 'Lorem ipsum dolor sit amet', '2024-02-23 00:00:00'),
    (14, 'Conseils d''un psychologue', 'Lorem ipsum dolor sit amet', '2024-02-17 00:00:00'),
    (3, 'Demande de conseil de professeur', 'Lorem ipsum dolor sit amet', '2024-02-08 00:00:00'),
    (7, 'Mon enfant est harcelé', 'Lorem ipsum dolor sit amet', '2024-03-10 00:00:00'),
    (9, 'Gérer le harcèlement en classe', 'Lorem ipsum dolor sit amet', '2024-03-12 00:00:00'),
    (2, 'Aide pour un enfant harcelé', 'Lorem ipsum dolor sit amet', '2024-02-20 00:00:00'),
    (17, 'Témoignage d''un élève', 'Lorem ipsum dolor sit amet', '2024-03-15 00:00:00'),
    (4, 'Conseils pour les parents', 'Lorem ipsum dolor sit amet', '2024-02-11 00:00:00'),
    (11, 'Stratégies de coping', 'Lorem ipsum dolor sit amet', '2024-03-18 00:00:00'),
    (19, 'Discussion ouverte sur le harcèlement', 'Lorem ipsum dolor sit amet', '2024-03-22 00:00:00'),
    (22, 'Comment détecter le harcèlement', 'Lorem ipsum dolor sit amet', '2024-03-26 00:00:00'),
    (8, 'Partage d''expériences', 'Lorem ipsum dolor sit amet', '2024-03-28 00:00:00'),
    (12, 'Soutien pour les victimes', 'Lorem ipsum dolor sit amet', '2024-04-01 00:00:00'),
    (23, 'Parole aux enseignants', 'Lorem ipsum dolor sit amet', '2024-03-30 00:00:00'),
    (15, 'Conseils d''auto-assistance', 'Lorem ipsum dolor sit amet', '2024-04-03 00:00:00'),
    (27, 'Réunions de soutien', 'Lorem ipsum dolor sit amet', '2024-04-05 00:00:00'),
    (13, 'Ressources utiles', 'Lorem ipsum dolor sit amet', '2024-04-07 00:00:00'),
    (26, 'Vivre après le harcèlement', 'Lorem ipsum dolor sit amet', '2024-04-09 00:00:00'),
    (16, 'Témoignages d''élèves', 'Lorem ipsum dolor sit amet', '2024-04-11 00:00:00'),
    (5, 'Témoignages de parents sur le harcèlement scolaire', 'Lorem ipsum dolor sit amet', '2024-04-15 00:00:00'),
    (14, 'Conseils pour détecter le harcèlement à l''école', 'Lorem ipsum dolor sit amet', '2024-04-17 00:00:00'),
    (3, 'Stratégies pour soutenir un enfant harcelé', 'Lorem ipsum dolor sit amet', '2024-04-19 00:00:00'),
    (7, 'Témoignages d''enseignants face au harcèlement', 'Lorem ipsum dolor sit amet', '2024-04-21 00:00:00'),
    (9, 'Discussion sur les conséquences du harcèlement', 'Lorem ipsum dolor sit amet', '2024-04-23 00:00:00'),
    (2, 'Partage d''expériences sur le harcèlement en ligne', 'Lorem ipsum dolor sit amet', '2024-04-25 00:00:00'),
    (17, 'Comment agir en tant que parent face au harcèlement', 'Lorem ipsum dolor sit amet', '2024-04-27 00:00:00'),
    (4, 'Ressources pour aider les enfants victimes de harcèlement', 'Lorem ipsum dolor sit amet', '2024-04-29 00:00:00'),
    (11, 'Débats sur les politiques de lutte contre le harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-01 00:00:00'),
    (19, 'Témoignages de survivants du harcèlement scolaire', 'Lorem ipsum dolor sit amet', '2024-05-03 00:00:00'),
    (22, 'Conseils pour prévenir le harcèlement entre élèves', 'Lorem ipsum dolor sit amet', '2024-05-05 00:00:00'),
    (8, 'Rôle des écoles dans la prévention du harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-07 00:00:00'),
    (12, 'Soutien psychologique pour les victimes de harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-09 00:00:00'),
    (23, 'Débats sur l''impact du harcèlement sur la santé mentale', 'Lorem ipsum dolor sit amet', '2024-05-11 00:00:00'),
    (15, 'Stratégies pour promouvoir un environnement anti-harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-13 00:00:00'),
    (27, 'Témoignages d''anciens harceleurs sur leur expérience', 'Lorem ipsum dolor sit amet', '2024-05-15 00:00:00'),
    (13, 'Comment favoriser l''expression des élèves face au harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-17 00:00:00'),
    (26, 'Discussion sur le rôle des réseaux sociaux dans le harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-19 00:00:00'),
    (16, 'Témoignages d''intervenants extérieurs dans la prévention du harcèlement', 'Lorem ipsum dolor sit amet', '2024-05-21 00:00:00');


-- --------------------------------------------------------

--
-- Structure de la table `reponses_forum`
--

CREATE TABLE `reponses_forum` (
    `id_reponse` int(11) NOT NULL AUTO_INCREMENT,
    `id_sujet` int(11) NOT NULL,
    `id_utilisateur` int(11) NOT NULL,
    `contenu` text NOT NULL,
    `date_creation` datetime NOT NULL,
    PRIMARY KEY (`id_reponse`),
    FOREIGN KEY (`id_sujet`) REFERENCES `sujets_forum` (`id`),
    FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Insertion de réponses pour les sujets du forum
INSERT INTO reponses_forum (id_sujet, id_utilisateur, contenu, date_creation) VALUES
    (1, 5, 'Lorem ipsum dolor sit amet', '2024-03-05 09:00:00'),
    (1, 2, 'Lorem ipsum dolor sit amet', '2024-03-06 09:15:00'),
    (1, 8, 'Lorem ipsum dolor sit amet', '2024-03-05 09:30:00'),
    (1, 2, 'Lorem ipsum dolor sit amet', '2024-03-06 09:45:00'),
    (1, 20, 'Lorem ipsum dolor sit amet', '2024-03-05 10:00:00'),
    (1, 15, 'Lorem ipsum dolor sit amet', '2024-03-06 10:15:00'),
    (1, 14, 'Lorem ipsum dolor sit amet', '2024-03-05 10:30:00'),
    (1, 18, 'Lorem ipsum dolor sit amet', '2024-03-06 10:45:00'),
    (1, 2, 'Lorem ipsum dolor sit amet', '2024-03-05 11:00:00'),
    (1, 25, 'Lorem ipsum dolor sit amet', '2024-03-06 11:15:00'),
    (1, 14, 'Lorem ipsum dolor sit amet', '2024-03-05 11:30:00'),
    (1, 12, 'Lorem ipsum dolor sit amet', '2024-03-06 11:45:00'),
    (1, 5, 'Lorem ipsum dolor sit amet', '2024-03-05 12:00:00'),
    (1, 8, 'Lorem ipsum dolor sit amet', '2024-03-06 12:15:00'),
    (1, 9, 'Lorem ipsum dolor sit amet', '2024-03-05 12:30:00'),
    (1, 4, 'Lorem ipsum dolor sit amet', '2024-03-06 12:45:00'),
    (1, 8, 'Lorem ipsum dolor sit amet', '2024-03-05 13:00:00'),
    (1, 9, 'Lorem ipsum dolor sit amet', '2024-03-06 13:15:00'),
    (1, 17, 'Lorem ipsum dolor sit amet', '2024-03-05 13:30:00'),
    (1, 22, 'Lorem ipsum dolor sit amet', '2024-03-06 13:45:00'),
    (1, 15, 'Lorem ipsum dolor sit amet', '2024-03-05 14:00:00'),
    (1, 22, 'Lorem ipsum dolor sit amet', '2024-03-06 14:15:00'),
    (1, 24, 'Lorem ipsum dolor sit amet', '2024-03-05 14:30:00'),
    (1, 26, 'Lorem ipsum dolor sit amet', '2024-03-06 14:45:00'),
    (1, 15, 'Lorem ipsum dolor sit amet', '2024-03-05 15:00:00'),
    (1, 16, 'Lorem ipsum dolor sit amet', '2024-03-06 15:15:00'),
    (1, 26, 'Lorem ipsum dolor sit amet', '2024-03-05 15:30:00'),
    (1, 30, 'Lorem ipsum dolor sit amet', '2024-03-06 15:45:00'),
    (1, 28, 'Lorem ipsum dolor sit amet', '2024-03-05 16:00:00'),
    (1, 27, 'Lorem ipsum dolor sit amet', '2024-03-06 16:15:00'),
    (1, 15, 'Lorem ipsum dolor sit amet', '2024-03-05 16:30:00'),
    (1, 22, 'Lorem ipsum dolor sit amet', '2024-03-06 16:45:00'),
    (1, 15, 'Lorem ipsum dolor sit amet', '2024-03-05 17:00:00'),
    (1, 23, 'Lorem ipsum dolor sit amet', '2024-03-06 17:15:00'),
    (2, 10, 'Lorem ipsum dolor sit amet', '2024-02-24 09:00:00'),
    (2, 3, 'Lorem ipsum dolor sit amet', '2024-02-25 09:15:00'),
    (3, 4, 'Lorem ipsum dolor sit amet', '2024-02-18 09:30:00'),
    (4, 8, 'Lorem ipsum dolor sit amet', '2024-02-09 09:45:00'),
    (5, 6, 'Lorem ipsum dolor sit amet', '2024-03-11 10:00:00'),
    (6, 1, 'Lorem ipsum dolor sit amet', '2024-03-13 10:15:00'),
    (6, 3, 'Lorem ipsum dolor sit amet', '2024-03-14 10:30:00'),
    (7, 7, 'Lorem ipsum dolor sit amet', '2024-02-21 10:45:00'),
    (7, 9, 'Lorem ipsum dolor sit amet', '2024-02-22 11:00:00'),
    (8, 2, 'Lorem ipsum dolor sit amet', '2024-03-16 11:15:00'),
    (8, 4, 'Lorem ipsum dolor sit amet', '2024-03-17 11:30:00'),
    (9, 11, 'Lorem ipsum dolor sit amet', '2024-02-12 11:45:00'),
    (10, 5, 'Lorem ipsum dolor sit amet', '2024-03-19 12:00:00'),
    (10, 12, 'Lorem ipsum dolor sit amet', '2024-03-20 12:15:00'),
    (11, 6, 'Lorem ipsum dolor sit amet', '2024-03-23 12:30:00'),
    (11, 13, 'Lorem ipsum dolor sit amet', '2024-03-24 12:45:00'),
    (12, 15, 'Lorem ipsum dolor sit amet', '2024-03-27 13:00:00'),
    (12, 17, 'Lorem ipsum dolor sit amet', '2024-03-29 13:15:00'),
    (13, 18, 'Lorem ipsum dolor sit amet', '2024-04-02 13:30:00'),
    (13, 20, 'Lorem ipsum dolor sit amet', '2024-04-04 13:45:00'),
    (14, 16, 'Lorem ipsum dolor sit amet', '2024-04-06 14:00:00'),
    (14, 19, 'Lorem ipsum dolor sit amet', '2024-04-08 14:15:00'),
    (15, 21, 'Lorem ipsum dolor sit amet', '2024-04-10 14:30:00'),
    (15, 23, 'Lorem ipsum dolor sit amet', '2024-04-12 14:45:00'),
    (16, 22, 'Lorem ipsum dolor sit amet', '2024-04-13 15:00:00'),
    (16, 24, 'Lorem ipsum dolor sit amet', '2024-04-14 15:15:00'),
    (17, 25, 'Lorem ipsum dolor sit amet', '2024-04-15 15:30:00'),
    (17, 27, 'Lorem ipsum dolor sit amet', '2024-04-16 15:45:00'),
    (18, 28, 'Lorem ipsum dolor sit amet', '2024-04-17 16:00:00'),
    (18, 30, 'Lorem ipsum dolor sit amet', '2024-04-18 16:15:00'),
    (19, 29, 'Lorem ipsum dolor sit amet', '2024-04-19 16:30:00'),
    (19, 26, 'Lorem ipsum dolor sit amet', '2024-04-20 16:45:00'),
    (20, 14, 'Lorem ipsum dolor sit amet', '2024-04-21 17:00:00'),
    (20, 12, 'Lorem ipsum dolor sit amet', '2024-04-22 17:15:00'),
    (21, 21, 'Lorem ipsum dolor sit amet', '2024-03-12 09:00:00'),
    (21, 22, 'Lorem ipsum dolor sit amet', '2024-03-12 09:15:00'),
    (21, 23, 'Lorem ipsum dolor sit amet', '2024-03-12 09:30:00'),
    (21, 24, 'Lorem ipsum dolor sit amet', '2024-03-12 09:45:00'),
    (21, 25, 'Lorem ipsum dolor sit amet', '2024-03-12 10:00:00'),
    (22, 26, 'Lorem ipsum dolor sit amet', '2024-03-12 10:15:00'),
    (22, 17, 'Lorem ipsum dolor sit amet', '2024-03-12 10:30:00'),
    (22, 8, 'Lorem ipsum dolor sit amet', '2024-03-12 10:45:00'),
    (22, 9, 'Lorem ipsum dolor sit amet', '2024-03-12 11:00:00'),
    (22, 10, 'Lorem ipsum dolor sit amet', '2024-03-12 11:15:00'),
    (23, 11, 'Lorem ipsum dolor sit amet', '2024-03-12 11:30:00'),
    (23, 12, 'Lorem ipsum dolor sit amet', '2024-03-12 11:45:00'),
    (23, 13, 'Lorem ipsum dolor sit amet', '2024-03-12 12:00:00'),
    (23, 14, 'Lorem ipsum dolor sit amet', '2024-03-12 12:15:00'),
    (23, 5, 'Lorem ipsum dolor sit amet', '2024-03-12 12:30:00'),
    (24, 1, 'Lorem ipsum dolor sit amet', '2024-03-12 12:45:00'),
    (24, 2, 'Lorem ipsum dolor sit amet', '2024-03-12 13:00:00'),
    (24, 3, 'Lorem ipsum dolor sit amet', '2024-03-12 13:15:00'),
    (24, 4, 'Lorem ipsum dolor sit amet', '2024-03-12 13:30:00'),
    (24, 5, 'Lorem ipsum dolor sit amet','2024-03-12 13:45:00'),
    (25, 6, 'Lorem ipsum dolor sit amet', '2024-03-12 14:00:00'),
    (25, 7, 'Lorem ipsum dolor sit amet', '2024-03-12 14:15:00'),
    (25, 8, 'Lorem ipsum dolor sit amet', '2024-03-12 14:30:00'),
    (25, 9, 'Lorem ipsum dolor sit amet', '2024-03-12 14:45:00'),
    (25, 10, 'Lorem ipsum dolor sit amet','2024-03-12 15:00:00'),
    (26, 11, 'Lorem ipsum dolor sit amet', '2024-03-12 15:15:00'),
    (26, 12, 'Lorem ipsum dolor sit amet', '2024-03-12 15:30:00'),
    (26, 13, 'Lorem ipsum dolor sit amet', '2024-03-12 15:45:00'),
    (26, 14, 'Lorem ipsum dolor sit amet', '2024-03-12 16:00:00'),
    (26, 15, 'Lorem ipsum dolor sit amet','2024-03-12 16:15:00'),
    (27, 1, 'Lorem ipsum dolor sit amet', '2024-03-12 16:30:00'),
    (27, 2, 'Lorem ipsum dolor sit amet', '2024-03-12 16:45:00'),
    (27, 3, 'Lorem ipsum dolor sit amet', '2024-03-12 17:00:00'),
    (27, 4, 'Lorem ipsum dolor sit amet', '2024-03-12 17:15:00'),
    (27, 5, 'Lorem ipsum dolor sit amet','2024-03-12 17:30:00'),
    (28, 6, 'Lorem ipsum dolor sit amet', '2024-03-12 17:45:00'),
    (28, 7, 'Lorem ipsum dolor sit amet', '2024-03-12 18:00:00'),
    (28, 8, 'Lorem ipsum dolor sit amet', '2024-03-12 18:15:00'),
    (28, 9, 'Lorem ipsum dolor sit amet', '2024-03-12 18:30:00'),
    (28, 10, 'Lorem ipsum dolor sit amet','2024-03-12 18:45:00'),
    (29, 11, 'Lorem ipsum dolor sit amet', '2024-03-12 19:00:00'),
    (29, 12, 'Lorem ipsum dolor sit amet', '2024-03-12 19:15:00'),
    (29, 13, 'Lorem ipsum dolor sit amet', '2024-03-12 19:30:00'),
    (29, 14, 'Lorem ipsum dolor sit amet', '2024-03-12 19:45:00'),
    (29, 15, 'Lorem ipsum dolor sit amet','2024-03-12 20:00:00'),
    (30, 1, 'Lorem ipsum dolor sit amet', '2024-03-12 20:15:00'),
    (30, 2, 'Lorem ipsum dolor sit amet', '2024-03-12 20:30:00'),
    (30, 3, 'Lorem ipsum dolor sit amet', '2024-03-12 20:45:00'),
    (30, 4, 'Lorem ipsum dolor sit amet', '2024-03-12 21:00:00'),
    (30, 5, 'Lorem ipsum dolor sit amet','2024-03-12 21:15:00');

CREATE TABLE `signalements` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_reponse` INT(11) NOT NULL,
    `date_signalement` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`id_reponse`) REFERENCES `reponses_forum`(`id_reponse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO signalements (id_reponse) VALUES
    (10),
    (11),
    (15),
    (20);

