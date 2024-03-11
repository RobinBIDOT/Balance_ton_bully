-- Suppression des tables si elles existent déjà
DROP TABLE IF EXISTS reponses_forum, rendez_vous, sujets_forum, dons, professionnels_sante, utilisateurs, actualites;


-- Création de la table `utilisateurs`
CREATE TABLE utilisateurs (
                              id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
                              nom_utilisateur VARCHAR(255),
                              email VARCHAR(255),
                              photo_avatar VARCHAR(255),
                              mot_de_passe_hash VARCHAR(255),
                              professionnel_de_sante BOOLEAN,
                              date_creation DATETIME,
                              derniere_connexion DATETIME
);

-- Création de la table `sujets_forum`
CREATE TABLE sujets_forum (
                              id_sujet INT AUTO_INCREMENT PRIMARY KEY,
                              id_utilisateur INT,
                              titre VARCHAR(255),
                              contenu TEXT,
                              date_creation DATETIME,
                              date_mise_a_jour DATETIME,
                              FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Création de la table `reponses_forum`
CREATE TABLE reponses_forum (
                                id_reponse INT AUTO_INCREMENT PRIMARY KEY,
                                id_sujet INT,
                                id_utilisateur INT,
                                contenu TEXT,
                                date_creation DATETIME,
                                FOREIGN KEY (id_sujet) REFERENCES sujets_forum(id_sujet),
                                FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Création de la table `professionnels_sante`
CREATE TABLE professionnels_sante (
                                      id_professionnel INT AUTO_INCREMENT PRIMARY KEY,
                                      nom VARCHAR(255),
                                      specialite VARCHAR(255),
                                      informations_contact VARCHAR(255)
);

-- Création de la table `rendez_vous`
CREATE TABLE rendez_vous (
                             id_rendez_vous INT AUTO_INCREMENT PRIMARY KEY,
                             id_professionnel INT,
                             id_utilisateur INT,
                             heure_rendez_vous DATETIME,
                             statut VARCHAR(255),
                             FOREIGN KEY (id_professionnel) REFERENCES professionnels_sante(id_professionnel),
                             FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);

-- Création de la table `actualites`
CREATE TABLE actualites (
                            id_actualite INT AUTO_INCREMENT PRIMARY KEY,
                            titre VARCHAR(255),
                            photo VARCHAR(255),
                            contenu TEXT,
                            lien_article VARCHAR(255),
                            date_publication DATETIME
);

-- Création de la table `dons`
CREATE TABLE dons (
                      id_don INT AUTO_INCREMENT PRIMARY KEY,
                      id_utilisateur INT,
                      montant DECIMAL(10, 2),
                      date_don DATETIME,
                      recurrent BOOLEAN,
                      FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur)
);