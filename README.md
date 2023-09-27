# JobBoard 💼

# 🗒️ Mon processus de développement

- Schéma de base de données
- Identification des fonctionnalités clés (tableau Velleda ✍️)
- Développement des fonctionnalité clés
- Ajout des fonctionnalités annexes
- Test systématique des anciennes fonctionnalités après chaque nouvelle fonctionnalité
- Test final

## 🌱 Seeding de la DB

Premier script SQL (seeding de base) :

```sql
-- Création de la base de données
CREATE DATABASE IF NOT EXISTS job_board;
USE job_board;

-- Table des entreprises
CREATE TABLE IF NOT EXISTS entreprises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Table des villes
CREATE TABLE IF NOT EXISTS villes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Table des types de contrat
CREATE TABLE IF NOT EXISTS types_contrat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL
);

-- Table des types de métier
CREATE TABLE IF NOT EXISTS types_metier (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NOT NULL
);

-- Table des offres d'emploi
CREATE TABLE IF NOT EXISTS offres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date_publication DATE NOT NULL,
    date_mise_a_jour DATE NOT NULL,
    reference VARCHAR(255) NOT NULL,
    intitule VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    entreprise_id INT,
    ville_id INT,
    type_contrat_id INT,
    type_metier_id INT,
    FOREIGN KEY (entreprise_id) REFERENCES entreprises(id),
    FOREIGN KEY (ville_id) REFERENCES villes(id),
    FOREIGN KEY (type_contrat_id) REFERENCES types_contrat(id),
    FOREIGN KEY (type_metier_id) REFERENCES types_metier(id)
);

-- Insertion de données fictives
INSERT INTO entreprises (nom) VALUES ('Entreprise A'), ('Entreprise B');
INSERT INTO villes (nom) VALUES ('Paris'), ('Lyon');
INSERT INTO types_contrat (type) VALUES ('CDI'), ('CDD');
INSERT INTO types_metier (type) VALUES ('Développeur'), ('Designer');

INSERT INTO offres (
    date_publication,
    date_mise_a_jour,
    reference,
    intitule,
    description,
    entreprise_id,
    ville_id,
    type_contrat_id,
    type_metier_id
) VALUES (
    '2023-09-27',
    '2023-09-27',
    'REF123',
    'Développeur PHP',
    'Développement de applications web en PHP.',
    1,
    1,
    1,
    1
), (
    '2023-09-27',
    '2023-09-27',
    'REF124',
    'Designer UI/UX',
    'Conception des interfaces utilisateur.',
    2,
    2,
    2,
    2
);

```

Second script SQL (ajouts de 20 offres) :

```sql
-- Ajout de 20 offres d'emploi fictives avec des données aléatoires

-- Réinitialisation du compteur d'auto-incrémentation pour la table des offres
ALTER TABLE offres AUTO_INCREMENT = 1;

DELIMITER //

CREATE PROCEDURE InsertRandomOffers()
BEGIN
  DECLARE i INT DEFAULT 1;
  WHILE i <= 20 DO
    INSERT INTO offres (
      date_publication,
      date_mise_a_jour,
      reference,
      intitule,
      description,
      entreprise_id,
      ville_id,
      type_contrat_id,
      type_metier_id
    ) VALUES (
      DATE_ADD('2023-09-27', INTERVAL -RAND() * 30 DAY), -- Date de publication aléatoire dans les 30 derniers jours
      DATE_ADD('2023-09-27', INTERVAL -RAND() * 30 DAY), -- Date de mise à jour aléatoire dans les 30 derniers jours
      CONCAT('REF', LPAD(i, 3, '0')), -- Référence unique basée sur l'itération
      CONCAT('Offre ', i), -- Intitulé de l'offre
      CONCAT('Description de l\'offre ', i), -- Description de l'offre
      1 + FLOOR(RAND() * 2), -- ID d'entreprise aléatoire entre 1 et 2
      1 + FLOOR(RAND() * 2), -- ID de ville aléatoire entre 1 et 2
      1 + FLOOR(RAND() * 2), -- ID de type de contrat aléatoire entre 1 et 2
      1 + FLOOR(RAND() * 2)  -- ID de type de métier aléatoire entre 1 et 2
    );
    SET i = i + 1;
  END WHILE;
END //

DELIMITER ;

-- Appel de la procédure pour insérer les offres
CALL InsertRandomOffers();

```

## 💾 Schéma de base de données

<img width="984" alt="database-schema" src="https://github.com/AmineAffif/JobBoard/assets/45182137/fdcad137-593c-4dcb-b4fa-60093fa4ca5a">

## Screenshots du site

<img width="1469" alt="image" src="https://github.com/AmineAffif/JobBoard/assets/45182137/3fcbcfa8-1734-4440-9eaf-f6dc23d1d8ee">

<img width="1469" alt="image" src="https://github.com/AmineAffif/JobBoard/assets/45182137/0649e725-2bcd-443f-87dc-5bb694c67422">
