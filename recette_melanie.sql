-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 17 nov. 2024 à 21:58
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `recette_melanie`
--
CREATE DATABASE IF NOT EXISTS `recette_melanie` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `recette_melanie`;

-- --------------------------------------------------------

--
-- Structure de la table `appellations`
--

DROP TABLE IF EXISTS `appellations`;
CREATE TABLE IF NOT EXISTS `appellations` (
  `id_appellation` int NOT NULL AUTO_INCREMENT,
  `nom_local` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_recette` int DEFAULT NULL,
  `pays_origine` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `region_origine` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `histoire` text COLLATE utf8mb4_general_ci,
  `differences` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_appellation`),
  UNIQUE KEY `id_recette` (`id_recette`,`pays_origine`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `appellations`
--

INSERT INTO `appellations` (`id_appellation`, `nom_local`, `id_recette`, `pays_origine`, `region_origine`, `histoire`, `differences`) VALUES
(2, 'Salade de tomates', 1, 'France', 'Provence', 'Une salade classique de l\'été, pleine de saveurs.', 'Peut être servie avec du fromage de chèvre.'),
(3, 'Poulet rôti', 2, 'France', 'Île-de-France', 'Un plat traditionnel français souvent préparé le dimanche.', 'Peut être accompagné de légumes rôtis.'),
(4, 'Gâteau au chocolat', 3, 'France', 'Île-de-France', 'Un dessert populaire qui ravit les gourmands.', 'Peut être préparé avec différents types de chocolat.'),
(5, 'Risotto aux champignons', 4, 'Italie', 'Lombardie', 'Un plat crémeux à base de riz arborio, typique de la cuisine italienne.', 'Peut inclure différents types de champignons.'),
(6, 'Soupe de légumes', 5, 'France', 'Bretagne', 'Une soupe réconfortante faite de légumes frais.', 'Peut être servie chaude ou froide.'),
(7, 'Tarte aux pommes', 6, 'France', 'Normandie', 'Un dessert classique français, souvent servi avec de la crème.', 'Peut être faite avec différentes variétés de pommes.'),
(8, 'Pâtes à la carbonara', 7, 'Italie', 'Lazio', 'Un plat de pâtes crémeux à base d\'oeufs et de fromage.', 'Traditionnellement préparé sans crème.'),
(9, 'Curry de crevettes', 8, 'Inde', 'État du Kerala', 'Un plat épicé à base de crevettes, typique de la cuisine indienne.', 'Peut être servi avec du riz basmati.'),
(10, 'Quiche lorraine', 9, 'France', 'Lorraine', 'Une tarte salée à base de crème et de lardons.', 'Peut être agrémentée de légumes ou de fromage.'),
(11, 'Chili con carne', 10, 'Mexique', 'Chihuahua', 'Un plat épicé à base de viande hachée et de haricots.', 'Peut être préparé avec ou sans haricots.'),
(12, 'Salade de quinoa aux légumes et saumon', 11, 'Canada', 'Colombie-Britannique', 'Une salade saine et nutritive, parfaite pour un déjeuner léger.', 'Peut inclure différents légumes selon la saison.'),
(13, 'Salade Composée Multisensorielle', 12, 'France', 'Île-de-France', 'Une salade créative qui stimule les sens.', 'Peut varier en fonction des ingrédients disponibles.'),
(14, 'Boulettes de Viande à la Sauce Tomate', 13, 'Italie', 'Campanie', 'Un plat réconfortant, souvent servi avec des pâtes.', 'Peut être préparé avec différentes viandes.'),
(15, 'Panna Cotta', 14, 'Italie', 'Piémont', 'Un dessert crémeux à base de crème et de gélatine.', 'Peut être aromatisé avec des fruits.'),
(16, 'Boeuf Bourguignon', 15, 'France', 'Bourgogne', 'Un plat mijoté à base de viande de boeuf.', 'Traditionnellement servi avec des pommes de terre.'),
(17, 'Ratatouille', 16, 'France', 'Provence', 'Un mélange de légumes mijotés.', 'Peut être servi chaud ou froid.'),
(18, 'Mousse au Chocolat', 17, 'France', 'Île-de-France', 'Un dessert léger et aéré à base de chocolat.', 'Peut être servi avec de la crème chantilly.'),
(19, 'Sushi', 18, 'Japon', 'Tokyo', 'Des rouleaux de riz et de poisson cru.', 'Peut être accompagné de sauce soja et de wasabi.'),
(20, 'Paella', 19, 'Espagne', 'Valence', 'Un plat de riz typique de la région de Valence.', 'Traditionnellement préparé avec des fruits de mer et du poulet.'),
(21, 'Tacos', 20, 'Mexique', 'Basse-Californie', 'Des tortillas garnies de viande et de légumes.', 'Peut être servi avec de la salsa et de la guacamole.'),
(22, 'Blinis au Saumon', 21, 'Russie', 'Moscou', 'Petites crêpes servies avec du saumon fumé.', 'Idéal pour un apéritif.'),
(23, 'Gaspacho', 22, 'Espagne', 'Andalousie', 'Une soupe froide à base de tomates et de légumes.', 'Parfait pour les journées chaudes.'),
(24, 'Pêches Melba', 23, 'France', 'Île-de-France', 'Un dessert à base de pêches, crème et glace.', 'Créé par le chef Auguste Escoffier.'),
(25, 'Lasagnes', 24, 'Italie', 'Émilie-Romagne', 'Des pâtes en couches avec viande et béchamel.', 'Peut être préparé avec différents types de viande.'),
(26, 'Crêpes Suzette', 25, 'France', 'Île-de-France', 'Des crêpes flambées au Grand Marnier.', 'Un dessert classique français.'),
(27, 'Fondue Savoyarde', 26, 'France', 'Savoie', 'Du fromage fondu servi avec du pain.', 'Traditionnellement préparé avec du fromage de Savoie.'),
(28, 'Choucroute Garnie', 27, 'France', 'Alsace', 'Du chou fermenté accompagné de viandes.', 'Un plat réconfortant pour l’hiver.'),
(29, 'Pâté en Croûte', 28, 'France', 'Île-de-France', 'Viande hachée cuite dans une croûte de pâte.', 'Idéal pour les pique-niques.'),
(30, 'Baba au Rhum', 29, 'France', 'Île-de-France', 'Un gâteau imbibé de rhum.', 'Souvent servi avec de la crème chantilly.'),
(31, 'Soufflé au Fromage', 30, 'France', 'Île-de-France', 'Un plat léger à base de fromage et d\'œufs.', 'Peut être préparé avec différents fromages.'),
(32, 'Couscous', 31, 'Maroc', 'Marrakech', 'Un plat à base de semoule servi avec des légumes et de la viande.', 'Un plat traditionnel du Maghreb.'),
(33, 'Tiramisu', 32, 'Italie', 'Vénétie', 'Un dessert à base de café et de mascarpone.', 'Peut être préparé avec ou sans alcool.'),
(34, 'Oeufs Cocotte', 33, 'France', 'Île-de-France', 'Des œufs cuits au four avec de la crème.', 'Peut être agrémenté de légumes ou de fromage.');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id_catg` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_catg`),
  UNIQUE KEY `unique_nom_cat` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id_catg`, `nom`) VALUES
(3, 'Desserts'),
(1, 'Entrées'),
(2, 'Plats'),
(4, 'Soupes');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id_com` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `text_commentaire` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_com` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_recette` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_com`),
  KEY `id_recette` (`id_recette`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id_com`, `nom`, `text_commentaire`, `date_com`, `id_recette`, `id_utilisateur`) VALUES
(1, 'Alice', 'Une salade de tomates parfaite pour l\'été !', '2023-10-01', 1, 11),
(2, 'Bob', 'Un peu trop salé à mon goût, mais la présentation est belle.', '2023-10-02', 1, 2),
(3, 'Charlie', 'Facile à préparer et très savoureux. Je vais la refaire !', '2023-10-03', 1, 3),
(4, 'Diana', 'J\'ai ajouté quelques épices supplémentaires et c\'était parfait.', '2023-10-04', 1, 4),
(5, 'Ethan', 'Pas mal, mais j\'ai trouvé que c\'était un peu trop riche.', '2023-10-05', 1, 5),
(6, 'Fiona', 'Une excellente recette pour les soirées entre amis.', '2023-10-06', 1, 6),
(7, 'George', 'Je n\'ai pas aimé la texture, mais le goût était bon.', '2023-10-07', 1, 7),
(8, 'Hannah', 'Recette parfaite pour un dîner rapide. Je recommande.', '2023-10-08', 1, 8),
(9, 'Ian', 'J\'ai remplacé quelques ingrédients et c\'était toujours bon.', '2023-10-09', 1, 9),
(10, 'Julia', 'Une des meilleures recettes que j\'ai essayées. Merci !', '2023-10-10', 1, 10),
(11, 'Alice', 'Le poulet rôti était juteux et bien assaisonné.', '2023-10-11', 2, 11),
(12, 'Bob', 'Une recette classique, mais toujours efficace.', '2023-10-12', 2, 2),
(13, 'Charlie', 'Mon poulet était un peu trop cuit, mais le goût était là.', '2023-10-13', 2, 3),
(14, 'Diana', 'Parfait avec des légumes rôtis, je le referai.', '2023-10-14', 2, 4),
(15, 'Ethan', 'Une belle présentation, mais un peu trop salé.', '2023-10-15', 2, 5),
(16, 'Fiona', 'Le gâteau au chocolat est un vrai délice !', '2023-10-16', 3, 6),
(17, 'George', 'Un peu trop sucré à mon goût, mais très moelleux.', '2023-10-17', 3, 7),
(18, 'Hannah', 'J\'adore cette recette, elle est devenue ma préférée.', '2023-10-18', 3, 8),
(19, 'Ian', 'Facile à faire et toujours un succès.', '2023-10-19', 3, 9),
(20, 'Julia', 'Une texture parfaite, je le recommande vivement.', '2023-10-20', 3, 10),
(21, 'Alice', 'Le risotto était crémeux et plein de saveurs.', '2023-10-21', 4, 11),
(22, 'Bob', 'J\'ai adoré les champignons, ça ajoute une belle profondeur.', '2023-10-22', 4, 2),
(23, 'Charlie', 'Un peu trop long à préparer, mais ça vaut le coup.', '2023-10-23', 4, 3),
(24, 'Diana', 'Une recette que je vais refaire sans hésiter.', '2023-10-24', 4, 4),
(25, 'Ethan', 'J\'ai ajouté du parmesan, c\'était excellent.', '2023-10-25', 4, 5),
(26, 'Fiona', 'Une soupe de légumes réconfortante, parfaite pour l\'hiver.', '2023-10-26', 5, 6),
(27, 'George', 'Un peu trop liquide pour moi, mais très savoureuse.', '2023-10-27', 5, 7),
(28, 'Hannah', 'Idéale pour un repas léger, je l\'adore.', '2023-10-28', 5, 8),
(29, 'Ian', 'Facile à préparer et très nutritive, parfaite pour les enfants.', '2023-10-29', 5, 9),
(30, 'Julia', 'Une recette que je vais garder dans mes favoris.', '2023-10-30', 5, 10),
(31, 'Alice', 'La tarte aux pommes est un classique, délicieuse avec de la crème.', '2023-10-31', 6, 11),
(32, 'Bob', 'Un peu trop sucrée à mon goût, mais la croûte est parfaite.', '2023-11-01', 6, 2),
(33, 'Charlie', 'Une recette facile et rapide, parfaite pour le dessert.', '2023-11-02', 6, 3),
(34, 'Diana', 'J\'ai adoré la texture, je vais l\'essayer avec d\'autres fruits.', '2023-11-03', 6, 4),
(35, 'Ethan', 'Ma famille a adoré, je vais la refaire bientôt.', '2023-11-04', 6, 5),
(36, 'Fiona', 'Les pâtes à la carbonara sont un vrai régal, très crémeuses.', '2023-11-05', 7, 6),
(37, 'George', 'J\'ai utilisé du bacon, c\'était vraiment savoureux.', '2023-11-06', 7, 7),
(38, 'Hannah', 'Un plat réconfortant, parfait pour les soirées d\'hiver.', '2023-11-07', 7, 8),
(39, 'Ian', 'Facile à préparer, mais j\'ai ajouté plus de fromage.', '2023-11-08', 7, 9),
(40, 'Julia', 'Une recette que je recommande à tous mes amis.', '2023-11-09', 7, 10),
(41, 'Alice', 'Le curry de crevettes était épicé à la perfection.', '2023-11-10', 8, 11),
(42, 'Bob', 'J\'ai adoré, mais un peu trop de lait de coco pour moi.', '2023-11-11', 8, 2),
(43, 'Charlie', 'Une belle combinaison de saveurs, je vais le refaire.', '2023-11-12', 8, 3),
(44, 'Diana', 'Parfait avec du riz basmati, un vrai délice.', '2023-11-13', 8, 4),
(45, 'Ethan', 'Un plat que j\'ai adoré, bien épicé.', '2023-11-14', 8, 5),
(46, 'Fiona', 'La quiche lorraine était savoureuse et bien cuite.', '2023-11-15', 9, 6),
(47, 'George', 'Un peu trop de lardons pour moi, mais très bonne.', '2023-11-16', 9, 7),
(48, 'Hannah', 'Une recette facile, parfaite pour le brunch.', '2023-11-17', 9, 8),
(49, 'Ian', 'J\'ai ajouté des épinards, c\'était délicieux.', '2023-11-18', 9, 9),
(50, 'Julia', 'Une quiche que je vais refaire souvent.', '2023-11-19', 9, 10),
(51, 'Alice', 'Le chili con carne est épicé et réconfortant.', '2023-11-20', 10, 11),
(52, 'Bob', 'J\'ai adoré la texture, mais un peu trop de haricots.', '2023-11-21', 10, 2),
(53, 'Charlie', 'Facile à préparer et parfait pour les soirées.', '2023-11-22', 10, 3),
(54, 'Diana', 'Un plat que je vais garder dans mes recettes préférées.', '2023-11-23', 10, 4),
(55, 'Ethan', 'Un peu trop épicé pour mes enfants, mais délicieux.', '2023-11-24', 10, 5),
(56, 'Fiona', 'La salade de quinoa est fraîche et nutritive.', '2023-11-25', 11, 6),
(57, 'George', 'Un excellent choix pour un déjeuner léger, j\'adore.', '2023-11-26', 11, 7),
(58, 'Hannah', 'J\'ai ajouté des noix pour le croquant, c\'était parfait.', '2023-11-27', 11, 8),
(59, 'Ian', 'Facile à préparer et plein de saveurs, je recommande.', '2023-11-28', 11, 9),
(60, 'Julia', 'Une recette que je vais refaire souvent, très savoureuse.', '2023-11-29', 11, 10),
(61, 'Alice', 'La salade composée multisensorielle est vraiment originale.', '2023-11-30', 12, 11),
(62, 'Bob', 'Une explosion de saveurs, j\'adore !', '2023-12-01', 12, 2),
(63, 'Charlie', 'Parfaite pour un repas léger, je la recommande.', '2023-12-02', 12, 3),
(64, 'Diana', 'Une belle présentation, idéale pour les invités.', '2023-12-03', 12, 4),
(65, 'Ethan', 'J\'ai adoré les textures variées, un vrai régal.', '2023-12-04', 12, 5),
(66, 'Alice', 'Recette délicieuse ! Mes enfants ont adoré, je la referais.', '2024-01-01', 13, 11),
(67, 'Alain', 'Délicieuse recette, je l\'ai adorée !', '2023-10-01', 1, 13),
(68, 'Alain', 'Un peu trop salé à mon goût.', '2023-10-02', 2, 13),
(69, 'Alain', 'Facile à préparer et très savoureux.', '2023-10-03', 3, 13),
(70, 'Alain', 'Je ne recommanderais pas cette recette.', '2023-10-04', 4, 13),
(71, 'Alain', 'Une excellente idée de plat pour les invités.', '2023-10-05', 5, 13),
(72, 'Alice', 'Une panna cotta délicieuse et crémeuse!', '2024-11-30', 14, 1),
(73, 'Bob', 'Le meilleur boeuf bourguignon que j\'ai goûté!', '2024-12-01', 15, 2),
(74, 'Charlie', 'La ratatouille était savoureuse et bien assaisonnée.', '2024-12-02', 16, 3),
(75, 'Diana', 'Une mousse au chocolat parfaite pour les amateurs de chocolat!', '2024-12-03', 17, 1),
(76, 'Ethan', 'Les sushis étaient frais et délicieux!', '2024-12-04', 18, 2),
(77, 'Fiona', 'Une paella bien parfumée, j\'adore!', '2024-12-05', 19, 3),
(78, 'George', 'Les tacos étaient croustillants et pleins de saveurs.', '2024-12-06', 20, 1),
(79, 'Alain', 'Les blinis au saumon étaient un excellent apéritif.', '2024-12-07', 21, 2),
(80, 'Ian', 'Le gaspacho était rafraîchissant et parfait pour l\'été.', '2024-12-08', 22, 3),
(81, 'Julia', 'Les pêches Melba étaient un dessert léger et délicieux.', '2024-12-09', 23, 1),
(82, 'Alice', 'Les lasagnes étaient bien garnies et savoureuses.', '2024-12-10', 24, 2),
(83, 'Alain', 'Les crêpes Suzette étaient un vrai régal!', '2024-12-11', 25, 3),
(84, 'Charlie', 'La fondue savoyarde était parfaite pour un repas d\'hiver.', '2024-12-12', 26, 1),
(85, 'Diana', 'La choucroute garnie était très copieuse et réconfortante.', '2024-12-13', 27, 2),
(86, 'Ethan', 'Le pâté en croûte était délicieux, j\'en reprendrai!', '2024-12-14', 28, 3),
(87, 'Fiona', 'Le baba au rhum était bien imbibé et savoureux.', '2024-12-15', 29, 1),
(88, 'George', 'Le soufflé au fromage était léger et aérien.', '2024-12-16', 30, 2),
(89, 'Hannah', 'Le couscous était bien épicé et très satisfaisant.', '2024-12-17', 31, 3),
(90, 'Alain', 'Le tiramisu était crémeux et délicieux!', '2024-12-18', 32, 1),
(91, 'Julia', 'Les œufs cocotte étaient parfaits pour le brunch.', '2024-12-19', 33, 2);

-- --------------------------------------------------------

--
-- Structure de la table `difficultes`
--

DROP TABLE IF EXISTS `difficultes`;
CREATE TABLE IF NOT EXISTS `difficultes` (
  `id_difficulte` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_difficulte`),
  UNIQUE KEY `unique_nom_difficulte` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `difficultes`
--

INSERT INTO `difficultes` (`id_difficulte`, `nom`) VALUES
(3, 'Difficile'),
(1, 'Facile'),
(2, 'Moyen');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

DROP TABLE IF EXISTS `evaluations`;
CREATE TABLE IF NOT EXISTS `evaluations` (
  `id_evaluation` int NOT NULL,
  `id_recette` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `note` int DEFAULT NULL,
  `date_evaluation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_evaluation`),
  KEY `id_recette` (`id_recette`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id_evaluation`, `id_recette`, `id_utilisateur`, `note`, `date_evaluation`) VALUES
(0, 13, 7, 4, '2024-11-17 17:22:47'),
(1, 1, 1, 5, '2024-11-14 10:26:07'),
(2, 1, 4, 4, '2024-11-14 10:26:07'),
(3, 2, 1, 5, '2024-11-14 10:26:07'),
(4, 2, 8, 4, '2024-11-14 10:26:07'),
(5, 3, 2, 3, '2024-11-14 10:26:07'),
(6, 3, 4, 5, '2024-11-14 10:26:07'),
(7, 4, 5, 2, '2024-11-14 10:26:07'),
(8, 4, 6, 3, '2024-11-14 10:26:07'),
(9, 5, 7, 4, '2024-11-14 10:26:07'),
(10, 5, 8, 5, '2024-11-14 10:26:07'),
(11, 6, 9, 3, '2024-11-14 10:26:07'),
(12, 6, 10, 4, '2024-11-14 10:26:07'),
(13, 7, 13, 5, '2024-11-14 10:26:07'),
(14, 8, 2, 4, '2024-11-14 10:26:07'),
(15, 9, 5, 5, '2024-11-14 10:26:07'),
(16, 10, 4, 2, '2024-11-14 10:26:07'),
(17, 10, 5, 3, '2024-11-14 10:26:07'),
(18, 11, 1, 4, '2024-11-15 09:00:00'),
(19, 11, 7, 5, '2024-11-15 09:05:00'),
(20, 11, 3, 4, '2024-11-15 09:10:00'),
(21, 12, 1, 5, '2024-11-15 09:15:00'),
(22, 12, 2, 4, '2024-11-15 09:20:00'),
(23, 12, 3, 5, '2024-11-15 09:25:00'),
(24, 14, 10, 5, '2024-11-16 18:20:53'),
(25, 15, 7, 4, '2024-11-16 18:20:53'),
(26, 16, 3, 5, '2024-11-16 18:20:53'),
(27, 17, 1, 5, '2024-11-16 18:20:53'),
(28, 18, 2, 4, '2024-11-16 18:20:53'),
(29, 19, 3, 5, '2024-11-16 18:20:53'),
(30, 20, 1, 5, '2024-11-16 18:20:53'),
(31, 21, 13, 4, '2024-11-16 18:20:53'),
(32, 22, 3, 5, '2024-11-16 18:20:53'),
(33, 23, 10, 5, '2024-11-16 18:20:53'),
(34, 24, 5, 4, '2024-11-16 18:20:53'),
(35, 25, 3, 5, '2024-11-16 18:20:53'),
(36, 26, 1, 5, '2024-11-16 18:20:53'),
(37, 27, 9, 4, '2024-11-16 18:20:53'),
(38, 28, 6, 5, '2024-11-16 18:20:53'),
(39, 29, 8, 5, '2024-11-16 18:20:53'),
(40, 30, 10, 4, '2024-11-16 18:20:53'),
(41, 31, 11, 5, '2024-11-16 18:20:53'),
(42, 32, 12, 5, '2024-11-16 18:20:53'),
(43, 33, 13, 4, '2024-11-16 18:20:53');

-- --------------------------------------------------------

--
-- Structure de la table `ingredients`
--

DROP TABLE IF EXISTS `ingredients`;
CREATE TABLE IF NOT EXISTS `ingredients` (
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_ingredient` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_ingredient`),
  UNIQUE KEY `unique_nom_ingredient` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredients`
--

INSERT INTO `ingredients` (`nom`, `id_ingredient`) VALUES
('Ail', 3),
('Aubergine', 57),
('Avocat', 41),
('Basilic', 15),
('Beurre', 11),
('Bouillon de légumes', 30),
('Carotte', 17),
('Champignon', 20),
('Chocolat noir', 31),
('Citron', 43),
('Concombre', 42),
('Courgette', 18),
('Crème', 29),
('Crevettes', 22),
('Epinards', 44),
('Farine', 6),
('Feta', 45),
('Fromage', 14),
('Gélatine', 55),
('Gingembre', 35),
('Haricots', 59),
('Haricots rouges', 36),
('Huile d\'olive', 10),
('Jambon', 61),
('Lait', 12),
('Lait de coco', 33),
('Lardons', 28),
('Lentilles', 23),
('Maïs', 47),
('Miel', 46),
('Oeufs', 13),
('Oignon', 2),
('Origan', 54),
('Pain', 62),
('Pain rassis', 49),
('Parmesan', 40),
('Parmesan râpé', 50),
('Pâte à tarte', 26),
('Pâte brisée', 37),
('Pâte de curry', 34),
('Pâtes', 25),
('Pêche', 60),
('Persil frais', 51),
('Piment', 39),
('Poivre', 9),
('Poivron', 19),
('Pommes', 27),
('Poulet', 4),
('Quinoa', 24),
('Riz', 5),
('Saumon', 21),
('Saumon fumé', 58),
('Sel', 8),
('Sucre', 7),
('Thym', 16),
('Tomate', 1),
('Tomates concassées', 53),
('Viande hachée', 38),
('Viande hachée (bœuf ou mélange)', 48),
('Vin blanc', 32),
('Vin rouge', 56);

-- --------------------------------------------------------

--
-- Structure de la table `ingredients_recettes`
--

DROP TABLE IF EXISTS `ingredients_recettes`;
CREATE TABLE IF NOT EXISTS `ingredients_recettes` (
  `id_ingredient` int DEFAULT NULL,
  `id_recette` int DEFAULT NULL,
  `id_quantite` int DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  KEY `fk_id_ingredient` (`id_ingredient`),
  KEY `fk_id_recette` (`id_recette`),
  KEY `fk_id_quantite` (`id_quantite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredients_recettes`
--

INSERT INTO `ingredients_recettes` (`id_ingredient`, `id_recette`, `id_quantite`, `quantite`) VALUES
(1, 1, 1, 200),
(2, 1, 1, 50),
(10, 1, 1, 30),
(8, 1, 1, 5),
(9, 1, 1, 5),
(4, 2, 1, 1),
(3, 2, 1, 5),
(10, 2, 1, 30),
(8, 2, 1, 5),
(9, 2, 1, 5),
(31, 3, 1, 200),
(7, 3, 1, 150),
(6, 3, 1, 100),
(12, 3, 1, 100),
(13, 3, 1, 3),
(10, 3, 1, 100),
(5, 4, 1, 300),
(20, 4, 1, 150),
(2, 4, 1, 50),
(30, 4, 1, 500),
(10, 4, 1, 30),
(11, 4, 1, 30),
(17, 5, 1, 100),
(2, 5, 1, 50),
(30, 5, 1, 500),
(10, 5, 1, 30),
(8, 5, 1, 5),
(9, 5, 1, 5),
(27, 6, 1, 3),
(7, 6, 1, 100),
(11, 6, 1, 50),
(26, 6, 1, 1),
(25, 7, 1, 250),
(28, 7, 1, 150),
(13, 7, 1, 3),
(40, 7, 1, 50),
(9, 7, 1, 5),
(22, 8, 1, 200),
(34, 8, 1, 100),
(33, 8, 1, 200),
(35, 8, 1, 20),
(3, 8, 1, 5),
(37, 9, 1, 1),
(28, 9, 1, 150),
(13, 9, 1, 3),
(12, 9, 1, 200),
(14, 9, 1, 100),
(48, 10, 1, 300),
(36, 10, 1, 150),
(2, 10, 1, 100),
(19, 10, 1, 50),
(7, 10, 1, 5),
(9, 10, 1, 5),
(24, 11, 1, 200),
(41, 11, 1, 100),
(42, 11, 1, 50),
(15, 11, 1, 10),
(10, 11, 1, 30),
(1, 12, 1, 200),
(2, 12, 1, 50),
(15, 12, 1, 10),
(10, 12, 1, 30),
(8, 12, 1, 5),
(9, 12, 1, 5),
(38, 13, 1, 300),
(1, 13, 1, 200),
(2, 13, 1, 50),
(3, 13, 1, 5),
(8, 13, 1, 5),
(55, 14, 1, 10),
(12, 14, 1, 200),
(29, 14, 1, 100),
(56, 15, 1, 250),
(48, 15, 1, 500),
(2, 15, 1, 100),
(17, 15, 1, 150),
(28, 15, 1, 100),
(3, 15, 1, 10),
(57, 16, 1, 300),
(18, 16, 1, 150),
(19, 16, 1, 100),
(53, 16, 1, 200),
(2, 16, 1, 100),
(44, 16, 1, 150),
(31, 17, 1, 200),
(12, 17, 1, 100),
(13, 17, 1, 4),
(5, 18, 1, 300),
(41, 18, 1, 1),
(58, 18, 1, 100),
(5, 19, 1, 400),
(4, 19, 1, 300),
(56, 19, 1, 150),
(10, 20, 1, 50),
(41, 20, 1, 1),
(61, 20, 1, 100),
(58, 21, 1, 100),
(29, 21, 1, 200),
(1, 22, 1, 500),
(42, 22, 1, 200),
(2, 22, 1, 100),
(60, 23, 1, 4),
(48, 24, 1, 500),
(53, 24, 1, 400),
(6, 25, 1, 250),
(12, 25, 1, 500),
(13, 25, 1, 4),
(14, 26, 1, 300),
(32, 26, 1, 200),
(59, 27, 1, 500),
(37, 28, 1, 1),
(46, 29, 1, 100),
(14, 30, 1, 200),
(4, 31, 1, 300),
(31, 32, 1, 200),
(13, 33, 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `ingredients_regimes`
--

DROP TABLE IF EXISTS `ingredients_regimes`;
CREATE TABLE IF NOT EXISTS `ingredients_regimes` (
  `id_ingredient` int DEFAULT NULL,
  `id_regime` int DEFAULT NULL,
  KEY `fk_id_ingredient1` (`id_ingredient`),
  KEY `fk_id_regime` (`id_regime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredients_regimes`
--

INSERT INTO `ingredients_regimes` (`id_ingredient`, `id_regime`) VALUES
(62, 1),
(60, 1),
(59, 1),
(57, 1),
(56, 1),
(55, 1),
(54, 1),
(53, 1),
(51, 1),
(50, 1),
(49, 1),
(47, 1),
(46, 1),
(45, 1),
(44, 1),
(43, 1),
(42, 1),
(41, 1),
(40, 1),
(39, 1),
(37, 1),
(36, 1),
(35, 1),
(34, 1),
(33, 1),
(32, 1),
(31, 1),
(30, 1),
(29, 1),
(27, 1),
(26, 1),
(25, 1),
(24, 1),
(23, 1),
(20, 1),
(19, 1),
(18, 1),
(17, 1),
(16, 1),
(15, 1),
(14, 1),
(13, 1),
(12, 1),
(11, 1),
(10, 1),
(9, 1),
(8, 1),
(7, 1),
(6, 1),
(5, 1),
(62, 2),
(60, 2),
(59, 2),
(57, 2),
(54, 2),
(53, 2),
(51, 2),
(47, 2),
(46, 2),
(44, 2),
(43, 2),
(42, 2),
(41, 2),
(39, 2),
(36, 2),
(35, 2),
(34, 2),
(33, 2),
(32, 2),
(31, 2),
(30, 2),
(27, 2),
(25, 2),
(24, 2),
(23, 2),
(20, 2),
(19, 2),
(18, 2),
(17, 2),
(16, 2),
(15, 2),
(62, 3),
(1, 3),
(60, 3),
(59, 3),
(57, 3),
(56, 3),
(55, 3),
(54, 3),
(53, 3),
(51, 3),
(50, 3),
(49, 3),
(47, 3),
(46, 3),
(45, 3),
(44, 3),
(43, 3),
(42, 3),
(41, 3),
(40, 3),
(39, 3),
(37, 3),
(36, 3),
(35, 3),
(34, 3),
(33, 3),
(32, 3),
(31, 3),
(30, 3),
(29, 3),
(27, 3),
(26, 3),
(25, 3),
(24, 3),
(23, 3),
(22, 3),
(20, 3),
(19, 3),
(18, 3),
(17, 3),
(16, 3),
(15, 3),
(14, 3),
(13, 3),
(12, 3),
(11, 3),
(10, 3),
(9, 3),
(8, 3),
(7, 3),
(6, 3),
(5, 3),
(4, 3),
(3, 3),
(2, 3),
(1, 3),
(62, 4),
(1, 4),
(60, 4),
(59, 4),
(61, 4),
(57, 4),
(56, 4),
(55, 4),
(54, 4),
(53, 4),
(51, 4),
(50, 4),
(49, 4),
(64, 4),
(47, 4),
(46, 4),
(45, 4),
(44, 4),
(43, 4),
(42, 4),
(41, 4),
(40, 4),
(39, 4),
(64, 4),
(37, 4),
(36, 4),
(35, 4),
(34, 4),
(33, 4),
(32, 4),
(31, 4),
(30, 4),
(29, 4),
(65, 4),
(27, 4),
(26, 4),
(25, 4),
(24, 4),
(23, 4),
(22, 4),
(61, 4),
(20, 4),
(19, 4),
(18, 4),
(17, 4),
(16, 4),
(15, 4),
(14, 4),
(13, 4),
(12, 4),
(11, 4),
(10, 4),
(9, 4),
(8, 4),
(7, 4),
(6, 4),
(5, 4),
(4, 4),
(3, 4),
(2, 4),
(1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `ingredients_types_aliments`
--

DROP TABLE IF EXISTS `ingredients_types_aliments`;
CREATE TABLE IF NOT EXISTS `ingredients_types_aliments` (
  `id_ingredient` int DEFAULT NULL,
  `id_aliment` int DEFAULT NULL,
  KEY `fk_id_aliment` (`id_aliment`),
  KEY `fk_id_ingredient2` (`id_ingredient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ingredients_types_aliments`
--

INSERT INTO `ingredients_types_aliments` (`id_ingredient`, `id_aliment`) VALUES
(3, 6),
(57, 1),
(41, 1),
(15, 6),
(11, 5),
(30, 1),
(17, 1),
(20, 1),
(31, 10),
(43, 2),
(42, 1),
(18, 1),
(29, 5),
(22, 9),
(44, 1),
(6, 4),
(45, 5),
(14, 5),
(55, 10),
(35, 6),
(59, 1),
(36, 1),
(10, 7),
(61, 9),
(12, 5),
(33, 5),
(28, 9),
(23, 1),
(47, 1),
(46, 2),
(13, 5),
(2, 1),
(54, 6),
(62, 11),
(49, 11),
(40, 5),
(50, 5),
(26, 11),
(37, 11),
(34, 10),
(25, 10),
(60, 2),
(51, 6),
(39, 6),
(9, 6),
(19, 1),
(27, 2),
(4, 9),
(24, 1),
(5, 4),
(21, 9),
(58, 9),
(8, 10),
(7, 10),
(16, 1),
(1, 1),
(53, 10),
(38, 9),
(48, 9),
(32, 10),
(56, 10);

-- --------------------------------------------------------

--
-- Structure de la table `instructions`
--

DROP TABLE IF EXISTS `instructions`;
CREATE TABLE IF NOT EXISTS `instructions` (
  `id_instruction` int NOT NULL,
  `id_recette` int NOT NULL,
  `ordre` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_instruction`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `instructions`
--

INSERT INTO `instructions` (`id_instruction`, `id_recette`, `ordre`, `description`) VALUES
(0, 1, 1, 'Laver les tomates.'),
(1, 1, 2, 'Couper les tomates en dés.'),
(2, 1, 3, 'Assaisonner avec de l\'huile d\'olive et du sel.'),
(3, 1, 4, 'Servir frais.'),
(4, 2, 1, 'Préchauffer le four à 200°C.'),
(5, 2, 2, 'Assaisonner le poulet avec des épices.'),
(6, 2, 3, 'Rôtir le poulet au four pendant 1 heure.'),
(7, 3, 1, 'Préchauffer le four à 180°C.'),
(8, 3, 2, 'Mélanger les ingrédients secs.'),
(9, 3, 3, 'Ajouter les œufs et le lait, puis mélanger.'),
(10, 3, 4, 'Verser dans un moule et cuire au four pendant 30 minutes.'),
(11, 4, 1, 'Faire chauffer de l\'huile dans une casserole.'),
(12, 4, 2, 'Ajouter les champignons et cuire jusqu\'à ce qu\'ils soient dorés.'),
(13, 4, 3, 'Ajouter le riz et remuer.'),
(14, 4, 4, 'Ajouter du bouillon progressivement et cuire jusqu\'à ce que le riz soit tendre.'),
(15, 5, 1, 'Faire revenir les légumes dans une casserole.'),
(16, 5, 2, 'Ajouter de l\'eau et porter à ébullition.'),
(17, 5, 3, 'Laisser mijoter jusqu\'à ce que les légumes soient tendres.'),
(18, 6, 1, 'Préparer la pâte à tarte.'),
(19, 6, 2, 'Éplucher et couper les pommes.'),
(20, 6, 3, 'Garnir la pâte avec les pommes et cuire au four à 180°C.'),
(21, 7, 1, 'Faire cuire les pâtes dans de l\'eau bouillante.'),
(22, 7, 2, 'Préparer la sauce avec des œufs et du fromage.'),
(23, 7, 3, 'Mélanger les pâtes avec la sauce et servir.'),
(24, 8, 1, 'Faire sauter les crevettes dans une poêle.'),
(25, 8, 2, 'Ajouter des épices et cuire jusqu\'à ce qu\'elles soient roses.'),
(26, 9, 1, 'Mélanger les œufs et le lait.'),
(27, 9, 2, 'Ajouter le fromage et le jambon.'),
(28, 9, 3, 'Verser dans une croûte et cuire au four.'),
(29, 10, 1, 'Faire revenir la viande dans une poêle.'),
(30, 10, 2, 'Ajouter les haricots et les épices.'),
(31, 10, 3, 'Laisser mijoter pendant 30 minutes.'),
(32, 11, 1, 'Rincer le quinoa et le cuire dans de l\'eau bouillante pendant 15 minutes.'),
(33, 11, 2, 'Couper les tomates, le concombre et l\'avocat en dés.'),
(34, 11, 3, 'Faire cuire le saumon à la poêle avec un peu d\'huile d\'olive.'),
(35, 11, 4, 'Mélanger le quinoa cuit avec les légumes coupés.'),
(36, 11, 5, 'Ajouter le saumon émietté dans le mélange.'),
(37, 11, 6, 'Assaisonner avec de l\'huile d\'olive, du jus de citron, du sel et du basilic.'),
(38, 11, 7, 'Servir frais.'),
(39, 11, 1, 'Rincer le quinoa sous l\'eau froide.'),
(40, 11, 2, 'Cuire le quinoa dans de l\'eau bouillante pendant 15 minutes.'),
(41, 11, 3, 'Couper les légumes en dés.'),
(42, 11, 4, 'Faire cuire le saumon à la poêle avec un peu d\'huile d\'olive.'),
(43, 11, 5, 'Mélanger le quinoa cuit avec les légumes et le saumon.'),
(44, 11, 6, 'Assaisonner avec du jus de citron, de l\'huile d\'olive, du sel et du basilic.'),
(45, 11, 7, 'Servir frais.'),
(46, 12, 1, 'Laver tous les légumes.'),
(47, 12, 2, 'Couper les légumes en morceaux.'),
(48, 12, 3, 'Ajouter des herbes fraîches.'),
(49, 12, 4, 'Préparer une vinaigrette avec de l\'huile d\'olive et du vinaigre.'),
(50, 12, 5, 'Mélanger les légumes avec la vinaigrette.'),
(51, 12, 6, 'Servir frais en entrée.'),
(52, 13, 1, 'Dans un bol, trempez le pain rassis dans le lait pour le ramollir. Écrasez-le ensuite.'),
(53, 13, 2, 'Dans un saladier, mélangez la viande hachée, l\'oignon et l\'ail finement hachés, le pain trempé, le parmesan, le persil, l\'œuf, le sel et le poivre. Formez des boulettes de la taille d\'une balle de golf.'),
(54, 13, 3, 'Dans une grande poêle, faites chauffer l\'huile d\'olive à feu moyen. Ajoutez les boulettes et faites-les dorer de tous les côtés pendant environ 5 minutes. Retirez-les de la poêle et réservez.'),
(55, 13, 4, 'Dans la même poêle, ajoutez les tomates concassées, l\'origan, le sel et le poivre. Portez à ébullition.'),
(56, 13, 5, 'Remettez les boulettes dans la poêle avec la sauce. Réduisez le feu et laissez mijoter pendant 20-25 minutes, jusqu\'à ce que les boulettes soient bien cuites.'),
(57, 13, 6, 'Servez chaud, accompagné de pâtes, de riz ou de pain croustillant.'),
(58, 14, 1, 'Faire chauffer la crème et le sucre jusqu’à dissolution.'),
(59, 14, 2, 'Ajouter la gélatine préalablement ramollie dans de l’eau froide.'),
(60, 14, 3, 'Verser dans des ramequins et laisser refroidir au réfrigérateur.'),
(61, 15, 1, 'Faire mariner la viande dans le vin rouge avec des épices.'),
(62, 15, 2, 'Faire revenir la viande dans une cocotte avec des oignons et des carottes.'),
(63, 15, 3, 'Ajouter le vin et laisser mijoter pendant plusieurs heures.'),
(64, 16, 1, 'Couper les légumes en dés.'),
(65, 16, 2, 'Faire revenir les légumes dans une poêle avec de l’huile d’olive.'),
(66, 16, 3, 'Laisser mijoter jusqu’à ce que les légumes soient tendres.'),
(67, 17, 1, 'Faire fondre le chocolat au bain-marie.'),
(68, 17, 2, 'Battre les blancs d\'œufs en neige et les incorporer délicatement au chocolat.'),
(69, 17, 3, 'Verser dans des ramequins et laisser reposer au réfrigérateur.'),
(70, 18, 1, 'Préparer le riz en le cuisant à la vapeur ou à l\'eau.'),
(71, 18, 2, 'Découper le poisson et les légumes en fines lamelles.'),
(72, 18, 3, 'Rouler le riz avec le poisson et les légumes dans une feuille d\'algue.'),
(73, 19, 1, 'Faire chauffer l\'huile dans une grande poêle.'),
(74, 19, 2, 'Ajouter le riz et les fruits de mer, puis assaisonner.'),
(75, 19, 3, 'Laisser cuire jusqu\'à ce que le riz soit tendre et que les fruits de mer soient cuits.'),
(76, 20, 1, 'Chauffer les tortillas dans une poêle.'),
(77, 20, 2, 'Garnir avec de la viande, des légumes et de la salsa.'),
(78, 21, 1, 'Préparer la pâte à blinis et les cuire dans une poêle.'),
(79, 21, 2, 'Garnir les blinis avec du saumon fumé et de la crème fraîche.'),
(82, 23, 1, 'Faire cuire les pêches dans de l\'eau bouillante pour les peler.'),
(83, 23, 2, 'Servir les pêches avec de la glace et de la crème.'),
(84, 24, 1, 'Préparer les pâtes et les superposer avec la viande et la béchamel.'),
(85, 24, 2, 'Cuire au four jusqu’à ce que le tout soit bien doré.'),
(86, 25, 1, 'Préparer la pâte à crêpes et la cuire dans une poêle.'),
(87, 25, 2, 'Flamber les crêpes avec du Grand Marnier avant de servir.'),
(88, 26, 1, 'Faire cuire les pommes de terre et le chou ensemble.'),
(89, 26, 2, 'Ajouter les viandes et laisser mijoter.'),
(90, 27, 1, 'Préparer la pâte à pâté et garnir avec la viande hachée.'),
(91, 27, 2, 'Cuire au four jusqu’à ce que la pâte soit dorée.'),
(92, 28, 1, 'Préparer la pâte à baba et la cuire au four.'),
(93, 28, 2, 'Imbiber le gâteau avec du rhum et servir avec de la crème chantilly.'),
(94, 29, 1, 'Mélanger le fromage avec des œufs et la crème.'),
(95, 29, 2, 'Verser dans un moule et cuire au four.'),
(96, 30, 1, 'Cuire le couscous à la vapeur.'),
(97, 30, 2, 'Garnir avec des légumes et de la viande cuite.'),
(98, 31, 1, 'Préparer le café et le laisser refroidir.'),
(99, 31, 2, 'Alterner les couches de café et de mascarpone dans un plat.'),
(100, 32, 1, 'Casser les œufs et les cuire au four dans des ramequins.'),
(101, 32, 2, 'Ajouter de la crème et des herbes avant de servir.'),
(102, 22, 1, 'Lavez les tomates et le poivron.'),
(103, 22, 2, 'Coupez les tomates en quartiers et épépinez-les.'),
(104, 22, 3, 'Pelez le concombre et coupez-le en morceaux.'),
(105, 22, 4, 'Coupez le poivron rouge en deux, retirez les graines et coupez-le en morceaux.'),
(106, 22, 5, 'Émincez l\'oignon et l\'ail.'),
(107, 22, 6, 'Dans un mixeur, ajoutez les tomates, le concombre, le poivron, l\'oignon, l\'ail et le pain rassis si vous en utilisez.'),
(108, 22, 7, 'Ajoutez l\'huile d\'olive, le vinaigre balsamique, le sel et le poivre.'),
(109, 22, 8, 'Mixez le tout jusqu\'à obtenir une consistance lisse.'),
(110, 22, 9, 'Laissez le gaspacho au réfrigérateur pendant au moins une heure pour qu\'il soit bien frais.'),
(111, 22, 10, 'Servez le gaspacho dans des bols, garni de dés de concombre ou de poivron pour la décoration.'),
(112, 33, 1, 'Préchauffer le four à 180°C.'),
(113, 33, 2, 'Beurrer des ramequins.'),
(114, 33, 3, 'Casser un œuf dans chaque ramequin.'),
(115, 33, 4, 'Ajouter de la crème, du sel, et du poivre.'),
(116, 33, 5, 'Enfourner pendant 10-15 minutes jusqu\'à ce que les blancs soient cuits mais les jaunes encore coulants.');

-- --------------------------------------------------------

--
-- Structure de la table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `id_photo` int NOT NULL AUTO_INCREMENT,
  `chemin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_recette` int NOT NULL,
  PRIMARY KEY (`id_photo`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `photos`
--

INSERT INTO `photos` (`id_photo`, `chemin`, `nom`, `id_recette`) VALUES
(1, 'images/salade_tomates.png', 'Salade de tomates', 1),
(2, 'images/poulet_roti.png', 'Poulet rôti', 2),
(3, 'images/gateau_chocolat.png', 'Gâteau au chocolat', 3),
(4, 'images/risotto_champignons.png', 'Risotto aux champignons', 4),
(5, 'images/soupe_legumes.png', 'Soupe de légumes', 5),
(6, 'images/tarte_pommes.png', 'Tarte aux pommes', 6),
(7, 'images/pates_carbonara.png', 'Pâtes à la carbonara', 7),
(8, 'images/curry_crevettes.png', 'Curry de crevettes', 8),
(9, 'images/quiche_lorraine.png', 'Quiche lorraine', 9),
(10, 'images/chili_con_carne.png', 'Chili con carne', 10),
(11, 'images/salade_de_quinoa_aux_légumes_et_saumon.png', 'Salade de quinoa aux légumes et saumon\r\n', 11),
(12, 'images/salade_composée_multisensorielle.png', 'Salade Composée Multisensorielle', 12),
(13, 'images/boulettes-de-viande-a-la-sauce-tomate.png', 'Boulettes de Viande à la Sauce Tomate', 13),
(14, 'images/panna_cotta.png', 'Panna Cotta', 14),
(15, 'images/boeuf_bourguignon.png', 'Boeuf Bourguignon', 15),
(16, 'images/ratatouille.png', 'Ratatouille', 16),
(17, 'images/mousse_au_chocolat.png', 'Mousse au Chocolat', 17),
(18, 'images/sushi.png', 'Sushi', 18),
(19, 'images/paella.png', 'Paella', 19),
(20, 'images/tacos.png', 'Tacos', 20),
(21, 'images/blinis_au_saumon.png', 'Blinis au Saumon', 21),
(22, 'images/gaspacho.png', 'Gaspacho', 22),
(23, 'images/peches_melba.png', 'Pêches Melba', 23),
(24, 'images/lasagnes.png', 'Lasagnes', 24),
(25, 'images/crepes_suzette.png', 'Crêpes Suzette', 25),
(26, 'images/fondue_savoyarde.png', 'Fondue Savoyarde', 26),
(27, 'images/choucroute_garnie.png', 'Choucroute Garnie', 27),
(28, 'images/pate_en_croute.png', 'Pâté en Croûte', 28),
(29, 'images/baba_au_rhum.png', 'Baba au Rhum', 29),
(30, 'images/souffle_au_fromage.png', 'Soufflé au Fromage', 30),
(31, 'images/couscous.png', 'Couscous', 31),
(32, 'images/tiramisu.png', 'Tiramisu', 32),
(33, 'images/oeufs_cocotte.png', 'Oeufs Cocotte', 33);

-- --------------------------------------------------------

--
-- Structure de la table `recettes`
--

DROP TABLE IF EXISTS `recettes`;
CREATE TABLE IF NOT EXISTS `recettes` (
  `id_recette` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_difficulte` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `id_catg` int NOT NULL,
  PRIMARY KEY (`id_recette`),
  UNIQUE KEY `unique_nom_recette` (`nom`),
  KEY `id_difficulte` (`id_difficulte`),
  KEY `id_utilisateur` (`id_utilisateur`),
  KEY `fk_id_categorie` (`id_catg`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recettes`
--

INSERT INTO `recettes` (`id_recette`, `nom`, `id_difficulte`, `id_utilisateur`, `id_catg`) VALUES
(1, 'Salade de tomates', 1, 1, 1),
(2, 'Poulet rôti', 3, 2, 2),
(3, 'Gâteau au chocolat', 1, 3, 3),
(4, 'Risotto aux champignons', 3, 2, 2),
(5, 'Soupe de légumes', 1, 1, 4),
(6, 'Tarte aux pommes', 1, 2, 3),
(7, 'Pâtes à la carbonara', 2, 11, 2),
(8, 'Curry de crevettes', 2, 2, 2),
(9, 'Quiche lorraine', 2, 2, 2),
(10, 'Chili con carne', 3, 11, 2),
(11, 'Salade de quinoa aux légumes et saumon', 1, 1, 1),
(12, 'Salade Composée Multisensorielle', 1, 1, 1),
(13, 'Boulettes de Viande à la Sauce Tomate', 2, 5, 2),
(14, 'Panna Cotta', 1, 1, 3),
(15, 'Boeuf Bourguignon', 3, 2, 2),
(16, 'Ratatouille', 2, 2, 2),
(17, 'Mousse au Chocolat', 1, 3, 3),
(18, 'Sushi', 3, 3, 2),
(19, 'Paella', 2, 2, 2),
(20, 'Tacos', 1, 3, 2),
(21, 'Blinis au Saumon', 2, 1, 1),
(22, 'Gaspacho', 1, 11, 4),
(23, 'Pêches Melba', 1, 2, 3),
(24, 'Lasagnes', 2, 2, 2),
(25, 'Crêpes Suzette', 1, 3, 3),
(26, 'Fondue Savoyarde', 1, 2, 2),
(27, 'Choucroute Garnie', 3, 1, 2),
(28, 'Pâté en Croûte', 2, 3, 2),
(29, 'Baba au Rhum', 2, 1, 3),
(30, 'Soufflé au Fromage', 3, 1, 3),
(31, 'Couscous', 3, 2, 2),
(32, 'Tiramisu', 1, 3, 3),
(33, 'Oeufs Cocotte', 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `recettes_regimes`
--

DROP TABLE IF EXISTS `recettes_regimes`;
CREATE TABLE IF NOT EXISTS `recettes_regimes` (
  `id_recette` int NOT NULL,
  `id_regime` int NOT NULL,
  PRIMARY KEY (`id_recette`,`id_regime`),
  KEY `id_regime` (`id_regime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `recettes_regimes`
--

INSERT INTO `recettes_regimes` (`id_recette`, `id_regime`) VALUES
(1, 1),
(4, 1),
(5, 1),
(6, 1),
(11, 1),
(12, 1),
(16, 1),
(22, 1),
(23, 1),
(25, 1),
(33, 1),
(3, 2),
(5, 2),
(17, 2),
(29, 2),
(32, 2),
(3, 3),
(14, 3),
(2, 4),
(7, 4),
(8, 4),
(9, 4),
(10, 4),
(13, 4),
(15, 4),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(24, 4),
(26, 4),
(27, 4),
(28, 4),
(30, 4),
(31, 4);

-- --------------------------------------------------------

--
-- Structure de la table `regimes`
--

DROP TABLE IF EXISTS `regimes`;
CREATE TABLE IF NOT EXISTS `regimes` (
  `id_regime` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_regime`),
  UNIQUE KEY `unique_nom_regime` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `regimes`
--

INSERT INTO `regimes` (`id_regime`, `nom`) VALUES
(3, 'Sans gluten'),
(4, 'Toute alimentation'),
(2, 'Végétalien'),
(1, 'Végétarien');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`),
  UNIQUE KEY `unique_nom_role` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id_role`, `nom`) VALUES
(1, 'Administrateur'),
(2, 'Modérateur'),
(3, 'Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `temps_preparation`
--

DROP TABLE IF EXISTS `temps_preparation`;
CREATE TABLE IF NOT EXISTS `temps_preparation` (
  `id_temps` int NOT NULL AUTO_INCREMENT,
  `id_recette` int NOT NULL,
  `temps_preparation` int NOT NULL,
  `temps_cuisson` int NOT NULL,
  PRIMARY KEY (`id_temps`),
  KEY `id_recette` (`id_recette`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `temps_preparation`
--

INSERT INTO `temps_preparation` (`id_temps`, `id_recette`, `temps_preparation`, `temps_cuisson`) VALUES
(1, 1, 15, 0),
(2, 2, 20, 90),
(3, 3, 20, 0),
(4, 4, 10, 30),
(5, 5, 15, 30),
(6, 6, 30, 40),
(7, 7, 15, 10),
(8, 8, 20, 0),
(9, 9, 20, 40),
(10, 10, 15, 60),
(11, 11, 20, 0),
(12, 12, 15, 0),
(13, 13, 30, 40),
(14, 14, 15, 240),
(15, 15, 20, 180),
(16, 16, 15, 40),
(17, 17, 20, 120),
(18, 18, 30, 0),
(19, 19, 20, 40),
(20, 20, 20, 0),
(21, 21, 30, 0),
(22, 22, 15, 0),
(23, 23, 15, 0),
(24, 24, 30, 40),
(25, 25, 30, 0),
(26, 26, 15, 20),
(27, 27, 20, 120),
(28, 28, 30, 60),
(29, 29, 20, 240),
(30, 30, 15, 40),
(31, 31, 15, 30),
(32, 32, 20, 240),
(33, 33, 10, 20);

-- --------------------------------------------------------

--
-- Structure de la table `types_aliments`
--

DROP TABLE IF EXISTS `types_aliments`;
CREATE TABLE IF NOT EXISTS `types_aliments` (
  `id_aliment` int NOT NULL AUTO_INCREMENT,
  `nom_aliment` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_aliment`),
  UNIQUE KEY `unique_nom_aliment` (`nom_aliment`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types_aliments`
--

INSERT INTO `types_aliments` (`id_aliment`, `nom_aliment`) VALUES
(15, 'Boissons'),
(3, 'Céréale'),
(10, 'Condiment'),
(16, 'Desserts'),
(6, 'Épice'),
(2, 'Fruit'),
(7, 'Graisse'),
(12, 'Herbes et Plantes Aromatiques'),
(1, 'Légume'),
(13, 'Noix et Graines'),
(5, 'Produit laitier'),
(11, 'Produits de Boulangerie et Pâtisserie'),
(14, 'Produits de Snack'),
(4, 'Protéine'),
(8, 'Sucre'),
(9, 'Viande et Poisson');

-- --------------------------------------------------------

--
-- Structure de la table `unites_quantites`
--

DROP TABLE IF EXISTS `unites_quantites`;
CREATE TABLE IF NOT EXISTS `unites_quantites` (
  `id_quantite` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_quantite`),
  UNIQUE KEY `unique_nom_unite` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `unites_quantites`
--

INSERT INTO `unites_quantites` (`id_quantite`, `nom`) VALUES
(4, 'Cuillères'),
(1, 'Grammes'),
(2, 'Kilogrammes'),
(3, 'Litres'),
(5, 'Pincées'),
(6, 'Unités');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `unique_pseudo` (`pseudo`),
  KEY `id_role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `pseudo`, `mdp`, `id_role`, `date_inscription`) VALUES
(1, 'Mélanie', 'azerty', '1', '2023-11-17 20:08:42'),
(2, 'Bob', 'mdpBob456', '2', '2024-11-17 20:08:42'),
(3, 'Charlie', 'mdpCharlie789', '3', '2024-11-17 20:08:42'),
(4, 'Diana', 'mdpDiana321', '3', '2024-11-17 20:08:42'),
(5, 'Ethan', 'mdpEthan654', '3', '2024-11-17 20:08:42'),
(6, 'Fiona', 'mdpFiona987', '3', '2024-11-17 20:08:42'),
(7, 'George', 'mdpGeorge159', '3', '2024-07-17 20:08:42'),
(8, 'Hannah', 'mdpHannah753', '3', '2024-11-17 20:08:42'),
(9, 'Ian', 'mdpIan852', '3', '2024-11-17 20:08:42'),
(10, 'Julia', 'mdpJulia369', '3', '2022-11-17 20:08:42'),
(11, 'Alice', '123', '3', '2024-11-17 20:08:42'),
(12, 'Alain', '14071992', '3', '2024-11-17 20:08:42'),
(13, 'Jean', 'abc', '3', '2024-11-17 20:08:42'),
(14, 'Hélène', 'wxcv', '3', '2024-11-17 20:08:42'),
(15, 'Tom', 'ybcd', '3', '2024-11-17 20:08:42'),
(16, 'Marie', 'csfs', '3', '2024-11-17 20:08:42'),
(17, 'Marc', 'dqvgzqe', '3', '2024-11-17 20:08:42'),
(18, 'Pauline', 'ceff564', '3', '2024-11-17 20:08:42'),
(19, 'Alfred', 'sqqf', '3', '2024-11-17 20:08:42');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `appellations`
--
ALTER TABLE `appellations`
  ADD CONSTRAINT `fk_id_recette3` FOREIGN KEY (`id_recette`) REFERENCES `recettes` (`id_recette`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `ingredients_recettes`
--
ALTER TABLE `ingredients_recettes`
  ADD CONSTRAINT `fk_id_ingredient` FOREIGN KEY (`id_ingredient`) REFERENCES `ingredients` (`id_ingredient`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_id_quantite` FOREIGN KEY (`id_quantite`) REFERENCES `unites_quantites` (`id_quantite`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Contraintes pour la table `recettes_regimes`
--
ALTER TABLE `recettes_regimes`
  ADD CONSTRAINT `recettes_regimes_ibfk_1` FOREIGN KEY (`id_recette`) REFERENCES `recettes` (`id_recette`) ON DELETE CASCADE,
  ADD CONSTRAINT `recettes_regimes_ibfk_2` FOREIGN KEY (`id_regime`) REFERENCES `regimes` (`id_regime`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
