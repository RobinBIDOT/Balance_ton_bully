-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS reponses_forum, rendez_vous, sujets_forum, dons, professionnels_sante, utilisateurs, actualites;

-- Création de la table `utilisateurs`
CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    photo_avatar VARCHAR(255),
    mot_de_passe_hash VARCHAR(255) NOT NULL,
    professionnel_de_sante BOOLEAN NOT NULL,
    date_creation DATETIME NOT NULL,
    derniere_connexion DATETIME
);

-- Insertion de 30 utilisateurs dans la table 'utilisateurs'
-- https://phppasswordhash.com/ mot de passe : password
-- Insertion des utilisateurs
INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe_hash, professionnel_de_sante, date_creation) VALUES
    ('nom1', 'nom1@email.fr', '$2y$10$eL7STk7AnwnYQoaI7.YiS.68IQWJ44tGCw3XnNQt6Nk.wAMKyzMO6', 0, '2024-02-21 00:00:00'),
    ('nom2', 'nom2@email.fr', '$2y$10$e350XdDmQssJ5P3GIhjVKe1cOoDZgLQC75ymU0srT6QHIDG8E3ORG', 0, '2024-01-03 00:00:00'),
    ('nom3', 'nom3@email.fr', '$2y$10$sTMcvPGEmTe3spB73nK0A.FC7Ebf3vJseidOwB5YTxwFictID7Vbu', 1, '2024-01-13 00:00:00'),
    ('nom4', 'nom4@email.fr', '$2y$10$Xqteqn6GraU8oDIG4X06pO1uISh6e3jiVcfQl4NssEp5Cw3rJfLDW', 0, '2024-01-21 00:00:00'),
    ('nom5', 'nom5@email.fr', '$2y$10$FvdmqaDfGjPFj25kP4KbV.6CwMMzPG3WPsku9BTyGFNLtB4p2ZZOO', 0, '2024-02-21 00:00:00'),
    ('nom6', 'nom6@email.fr', '$2y$10$/fNDYnBMJEAU.mYA2s6FmOJNk57U.2fLTHoZsJ/cOPPiMqEDLqVMu', 0, '2024-01-03 00:00:00'),
    ('nom7', 'nom7@email.fr', '$2y$10$JjRQMWt08BmwnPAPCcGjc.pidVLUMhIxOVSQ57eRbdmxMWdPW72pC', 1, '2024-01-13 00:00:00'),
    ('nom8', 'nom8@email.fr', '$2y$10$NfSU/ieCUsik6Az9Jk1/9OVCSVn9/UaE4ztTy8yGaPz5BJ3yjU8mG', 0, '2024-01-21 00:00:00'),
    ('nom9', 'nom9@email.fr', '$2y$10$A4wByyravxpbzKqqsGfjgef.D8HO9C54ovEwYo0lbsG4jIXwqZzwG', 0, '2024-02-21 00:00:00'),
    ('nom10', 'nom10@email.fr','$2y$10$y84pwmshC7boE0h5vh0lZ.TFSsfEoz596Z01J77uoM5Sjz/uwwQpq', 0, '2024-01-03 00:00:00'),
    ('nom11', 'nom11@email.fr', '$2y$10$GJNOSmF/seMKt.MbocLnyO5XrfYxfQe.V7tU8mn9V1m1PvZg.h88i', 1, '2024-01-13 00:00:00'),
    ('nom12', 'nom12@email.fr', '$2y$10$.UcOLwPGsDAlwa/hPosKEeq3HASHKos4BVkTCHjUAetYj/qbLoNH.', 0, '2024-01-21 00:00:00'),
    ('nom13', 'nom13@email.fr', '$2y$10$dw7PFOkfjUxT5.P.m.v.derLOauvqxOIJWHeSQGe3TaijtJXzHIlG', 0, '2024-02-21 00:00:00'),
    ('nom14', 'nom14@email.fr', '$2y$10$Dd3fU0YkW2T64ArX4UvZEO8oJbaGUP5Vaguxa1JsSNcdZmEaBob2C', 0, '2024-01-03 00:00:00'),
    ('nom15', 'nom15@email.fr', '$2y$10$DrplgAeU.NubftTC82IccuC68tWpU/KuWAyRftWIb2avgpVoTxOZG', 1, '2024-01-13 00:00:00'),
    ('nom16', 'nom16@email.fr', '$2y$10$/oeoBGvq9liWYY3kUUf05eTjfwhDZUvbs9YHDtwICxIXdcGAkE0KO', 0, '2024-01-21 00:00:00'),
    ('nom17', 'nom17@email.fr', '$2y$10$Y0D3AHZYH3UlgKHudeyA6Ohy9QWc/5MZtne9WYu4oUz8gtA5.zMDq', 0, '2024-02-21 00:00:00'),
    ('nom18', 'nom18@email.fr', '$2y$10$OfOQd1K5cz7CR02Yvtu5NeDN/y179TERIePkAKyPsPUY9fecE.yzO', 0, '2024-01-03 00:00:00'),
    ('nom19', 'nom19@email.fr', '$2y$10$jMVeXTjqppkQsDxTXfIIO.aR1AM31s79Vduc1n.vQ4FrnunuDG1kW', 1, '2024-01-13 00:00:00'),
    ('nom20', 'nom20@email.fr', '$2y$10$EfdYWJEuIeEa0E9CeViYaOzO.wz/I2iK/y0BmHYsk3rlzNKnfA8KG', 0, '2024-01-21 00:00:00'),
    ('nom21', 'nom21@email.fr', '$2y$10$2GEk96ayFNCEzyVpfMXCVOmUE9y9O9Ha8A/ZjOc5k//ebNwto2DGW', 0, '2024-02-21 00:00:00'),
    ('nom22', 'nom22@email.fr', '$2y$10$3rT/T/j8561UdL0p6TKTjuO6etHTiPMgNdA6Q.0jHgLjX15A.M62G', 0, '2024-01-03 00:00:00'),
    ('nom23', 'nom23@email.fr', '$2y$10$PTF140NQaR8kP6fxqtTipOvhOrjnX5YBdP8h3vG1RuuwYJhsBa8.K', 1, '2024-01-13 00:00:00'),
    ('nom24', 'nom24@email.fr', '$2y$10$UU03BIfHz/ZwRgi2cKFEmeNwxcOky.9Gxgq7NanFqKesjof1YvBzm', 0, '2024-01-21 00:00:00'),
    ('nom25', 'nom25@email.fr', '$2y$10$5sw4lba8Opdjd9c.2v.mAutsP3p..Bm4OZ8PfHT52dxt28jXU0Wp6', 0, '2024-02-21 00:00:00'),
    ('nom26', 'nom26@email.fr', '$2y$10$LwcZkPC6LQdJG97y/A19aOkcm0P5jm62F/RkXS7kfFWmxdAdKKbYO', 0, '2024-01-03 00:00:00'),
    ('nom27', 'nom27@email.fr', '$2y$10$iiNq2WeRKRS12TssLNqJoepeg36vRRzvW4ZuKZB9DniyLR3vQf1Xy', 1, '2024-01-13 00:00:00'),
    ('nom28', 'nom28@email.fr', '$2y$10$y9l/ZlYu8oVTp./FCJqfiuBbI6B1JwbF7Ae/SpEhmlLys1OE2Dpx.', 0, '2024-01-21 00:00:00'),
    ('nom29', 'nom29@email.fr', '$2y$10$HFRiHJY85ggsbVA97hGuwOepPFV/kFITxpkjky8JR8eq1zTTlbpnq', 0, '2024-02-21 00:00:00'),
    ('nom30', 'nom30@email.fr', '$2y$10$8CUcMi/4cy00xOqbjh4EmO7JsiJsaHZEKFul7tyC94YprijRyVB2m', 0, '2024-01-03 00:00:00');


-- Création de la table `sujets_forum`
CREATE TABLE sujets_forum (
    id_sujet INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    date_creation DATETIME NOT NULL,
    date_mise_a_jour DATETIME,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

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
    (16, 'Témoignages d''élèves', 'Lorem ipsum dolor sit amet', '2024-04-11 00:00:00');


-- Création de la table `reponses_forum`
CREATE TABLE reponses_forum (
    id_reponse INT AUTO_INCREMENT PRIMARY KEY,
    id_sujet INT NOT NULL,
    id_utilisateur INT NOT NULL,
    contenu TEXT NOT NULL,
    date_creation DATETIME NOT NULL,
    FOREIGN KEY (id_sujet) REFERENCES sujets_forum(id_sujet),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Insertion de réponses pour les sujets du forum
INSERT INTO reponses_forum (id_sujet, id_utilisateur, contenu, date_creation) VALUES
    (1, 5, 'Lorem ipsum dolor sit amet', '2024-03-05 00:00:00'),
    (1, 2, 'Lorem ipsum dolor sit amet', '2024-03-06 00:00:00'),
    (2, 10, 'Lorem ipsum dolor sit amet', '2024-02-24 00:00:00'),
    (2, 3, 'Lorem ipsum dolor sit amet', '2024-02-25 00:00:00'),
    (3, 4, 'Lorem ipsum dolor sit amet', '2024-02-18 00:00:00'),
    (4, 8, 'Lorem ipsum dolor sit amet', '2024-02-09 00:00:00'),
    (5, 6, 'Lorem ipsum dolor sit amet', '2024-03-11 00:00:00'),
    (6, 1, 'Lorem ipsum dolor sit amet', '2024-03-13 00:00:00'),
    (6, 3, 'Lorem ipsum dolor sit amet', '2024-03-14 00:00:00'),
    (7, 7, 'Lorem ipsum dolor sit amet', '2024-02-21 00:00:00'),
    (7, 9, 'Lorem ipsum dolor sit amet', '2024-02-22 00:00:00'),
    (8, 2, 'Lorem ipsum dolor sit amet', '2024-03-16 00:00:00'),
    (8, 4, 'Lorem ipsum dolor sit amet', '2024-03-17 00:00:00'),
    (9, 11, 'Lorem ipsum dolor sit amet', '2024-02-12 00:00:00'),
    (10, 5, 'Lorem ipsum dolor sit amet', '2024-03-19 00:00:00'),
    (10, 12, 'Lorem ipsum dolor sit amet', '2024-03-20 00:00:00'),
    (11, 6, 'Lorem ipsum dolor sit amet', '2024-03-23 00:00:00'),
    (11, 13, 'Lorem ipsum dolor sit amet', '2024-03-24 00:00:00'),
    (12, 15, 'Lorem ipsum dolor sit amet', '2024-03-27 00:00:00'),
    (12, 17, 'Lorem ipsum dolor sit amet', '2024-03-29 00:00:00'),
    (13, 18, 'Lorem ipsum dolor sit amet', '2024-04-02 00:00:00'),
    (13, 20, 'Lorem ipsum dolor sit amet', '2024-04-04 00:00:00'),
    (14, 16, 'Lorem ipsum dolor sit amet', '2024-04-06 00:00:00'),
    (14, 19, 'Lorem ipsum dolor sit amet', '2024-04-08 00:00:00'),
    (15, 21, 'Lorem ipsum dolor sit amet', '2024-04-10 00:00:00'),
    (15, 23, 'Lorem ipsum dolor sit amet', '2024-04-12 00:00:00'),
    (16, 22, 'Lorem ipsum dolor sit amet', '2024-04-13 00:00:00'),
    (16, 24, 'Lorem ipsum dolor sit amet', '2024-04-14 00:00:00'),
    (17, 25, 'Lorem ipsum dolor sit amet', '2024-04-15 00:00:00'),
    (17, 27, 'Lorem ipsum dolor sit amet', '2024-04-16 00:00:00'),
    (18, 28, 'Lorem ipsum dolor sit amet', '2024-04-17 00:00:00'),
    (18, 30, 'Lorem ipsum dolor sit amet', '2024-04-18 00:00:00'),
    (19, 29, 'Lorem ipsum dolor sit amet', '2024-04-19 00:00:00'),
    (19, 26, 'Lorem ipsum dolor sit amet', '2024-04-20 00:00:00'),
    (20, 14, 'Lorem ipsum dolor sit amet', '2024-04-21 00:00:00'),
    (20, 12, 'Lorem ipsum dolor sit amet', '2024-04-22 00:00:00');

-- Création de la table `professionnels_sante`
CREATE TABLE professionnels_sante (
    id_professionnel INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    specialite VARCHAR(255) NOT NULL,
    informations_contact VARCHAR(255) NOT NULL
);

INSERT INTO professionnels_sante (nom, specialite, informations_contact) VALUES
    ('Dr. Alice Dupont', 'Psychologie', 'alice.dupont@email.fr'),
    ('Dr. Jean Martin', 'Pédopsychiatrie', 'jean.martin@email.fr'),
    ('Dr. Sarah Lefebvre', 'Conseiller d''orientation', 'sarah.lefebvre@email.fr'),
    ('Dr. Marc Durand', 'Thérapie comportementale', 'marc.durand@email.fr'),
    ('Dr. Émilie Bernard', 'Psychologie clinique', 'emilie.bernard@email.fr'),
    ('Dr. Nicolas Petit', 'Psychiatrie enfant et adolescent', 'nicolas.petit@email.fr'),
    ('Dr. Julie Roussel', 'Psychologie de l''éducation', 'julie.roussel@email.fr'),
    ('Dr. Alexandre Moreau', 'Thérapie familiale', 'alexandre.moreau@email.fr');

-- Création de la table `rendez_vous`
CREATE TABLE rendez_vous (
    id_rendez_vous INT AUTO_INCREMENT PRIMARY KEY,
    id_professionnel INT NOT NULL,
    id_utilisateur INT NOT NULL,
    heure_rendez_vous DATETIME NOT NULL,
    statut VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_professionnel) REFERENCES professionnels_sante(id_professionnel),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Insertion de rendez-vous dans la table 'rendez_vous'
INSERT INTO rendez_vous (id_professionnel, id_utilisateur, heure_rendez_vous, statut) VALUES
    (1, 5, '2024-04-10 09:00:00', 'Confirmé'),
    (2, 3, '2024-04-11 10:00:00', 'Confirmé'),
    (3, 7, '2024-04-12 11:00:00', 'Confirmé'),
    (4, 2, '2024-04-13 09:00:00', 'Confirmé'),
    (5, 10, '2024-04-14 10:00:00', 'Confirmé'),
    (6, 4, '2024-04-15 11:00:00', 'Confirmé'),
    (7, 8, '2024-04-16 09:00:00', 'Confirmé'),
    (8, 6, '2024-04-17 10:00:00', 'Confirmé'),
    (1, 9, '2024-04-18 11:00:00', 'Confirmé'),
    (2, 11, '2024-04-19 09:00:00', 'Confirmé'),
    (3, 13, '2024-04-20 10:00:00', 'Confirmé'),
    (4, 12, '2024-04-21 11:00:00', 'Confirmé'),
    (5, 15, '2024-04-22 09:00:00', 'Confirmé'),
    (6, 14, '2024-04-23 10:00:00', 'Confirmé'),
    (7, 16, '2024-04-24 11:00:00', 'Confirmé'),
    (8, 18, '2024-04-25 09:00:00', 'Confirmé'),
    (1, 17, '2024-04-26 10:00:00', 'Confirmé'),
    (2, 19, '2024-04-27 11:00:00', 'Confirmé'),
    (3, 20, '2024-04-28 09:00:00', 'Confirmé'),
    (4, 22, '2024-04-29 10:00:00', 'Confirmé');

-- Création de la table `actualites`
CREATE TABLE actualites (
    id_actualite INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    contenu TEXT NOT NULL,
    lien_article VARCHAR(255),
    date_publication DATETIME NOT NULL
);

-- Insertion de données dans la table `actualites`
INSERT INTO actualites (titre, photo, contenu, lien_article, date_publication) VALUES
    ('Titre de l''actualité 1', 'photo1.jpg', 'Contenu de l''actualité 1', 'lien1', '2024-01-01 00:00:00'),
    ('Titre de l''actualité 2', 'photo2.jpg', 'Contenu de l''actualité 2', 'lien2', '2024-01-02 00:00:00'),
    ('Titre de l''actualité 3', 'photo3.jpg', 'Contenu de l''actualité 3', 'lien3', '2024-01-03 00:00:00'),
    ('Titre de l''actualité 4', 'photo4.jpg', 'Contenu de l''actualité 4', 'lien4', '2024-01-04 00:00:00'),
    ('Titre de l''actualité 5', 'photo5.jpg', 'Contenu de l''actualité 5', 'lien5', '2024-01-05 00:00:00'),
    ('Titre de l''actualité 6', 'photo6.jpg', 'Contenu de l''actualité 6', 'lien6', '2024-01-06 00:00:00'),
    ('Titre de l''actualité 7', 'photo7.jpg', 'Contenu de l''actualité 7', 'lien7', '2024-01-07 00:00:00'),
    ('Titre de l''actualité 8', 'photo8.jpg', 'Contenu de l''actualité 8', 'lien8', '2024-01-08 00:00:00'),
    ('Titre de l''actualité 9', 'photo9.jpg', 'Contenu de l''actualité 9', 'lien9', '2024-01-09 00:00:00'),
    ('Titre de l''actualité 10', 'photo10.jpg', 'Contenu de l''actualité 10', 'lien10', '2024-01-10 00:00:00');


-- Création de la table `dons`
CREATE TABLE dons (
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT NOT NULL,
    montant DECIMAL(10, 2) NOT NULL,
    date_don DATETIME NOT NULL,
    recurrent BOOLEAN NOT NULL,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Insertion de données dans la table `dons`
INSERT INTO dons (id_utilisateur, montant, date_don, recurrent) VALUES
    (1, 100.00, '2024-01-01 12:00:00', 1),
    (2, 50.00, '2024-01-02 12:00:00', 0),
    (3, 200.00, '2024-01-03 12:00:00', 1),
    (4, 75.00, '2024-01-04 12:00:00', 0),
    (5, 150.00, '2024-01-05 12:00:00', 1),
    (6, 300.00, '2024-01-06 12:00:00', 0),
    (7, 25.00, '2024-01-07 12:00:00', 1),
    (8, 100.00, '2024-01-08 12:00:00', 0),
    (9, 200.00, '2024-01-09 12:00:00', 1),
    (10, 150.00, '2024-01-10 12:00:00', 0),
    (11, 75.00, '2024-01-11 12:00:00', 1),
    (12, 300.00, '2024-01-12 12:00:00', 0),
    (13, 50.00, '2024-01-13 12:00:00', 1),
    (14, 100.00, '2024-01-14 12:00:00', 0),
    (15, 200.00, '2024-01-15 12:00:00', 1),
    (16, 150.00, '2024-01-16 12:00:00', 0),
    (17, 75.00, '2024-01-17 12:00:00', 1),
    (18, 300.00, '2024-01-18 12:00:00', 0),
    (19, 50.00, '2024-01-19 12:00:00', 1),
    (20, 100.00, '2024-01-20 12:00:00', 0);

-- Création de la table `administrateurs`
CREATE TABLE administrateurs (
    id_administrateur INT AUTO_INCREMENT PRIMARY KEY,
    nom_administrateur VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe_hash VARCHAR(255) NOT NULL,
    date_creation DATETIME NOT NULL
);

-- Insertion de deux administrateurs dans la table 'administrateurs'
-- https://phppasswordhash.com/ mot de passe : administrateur
INSERT INTO administrateurs (nom_administrateur, email, mot_de_passe_hash, date_creation) VALUES
    ('Admin1', 'admin1@email.fr', '$2y$10$Qp/BmnoWpXkbQ8FO1jmR.eEE2EFK5qKFXxqFBkI/FU6GxW1fuJLli', '2024-01-01 00:00:00'),
    ('Admin2', 'admin2@email.fr', '$2y$10$vAsCqertK6ec6NeKkt5R1.efqnV9xPFXGsz0McdeaBlYpeYQxWFja', '2024-01-02 00:00:00');