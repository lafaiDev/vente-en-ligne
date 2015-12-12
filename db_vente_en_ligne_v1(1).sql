-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 29 Novembre 2015 à 16:41
-- Version du serveur: 5.5.29
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `db_vente_en_ligne_v1`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Piscine'),
(2, 'Electroménager');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `options`
--

INSERT INTO `options` (`id`, `nom`) VALUES
(1, 'chauffages '),
(2, 'ESCALIER ET PLAGES'),
(3, 'ECLAIRAGE PISCINE ET LUMINAIRES'),
(4, 'POMPES A CHALEUR');

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `updated_at` datetime DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id`, `updated_at`, `name`, `path`) VALUES
(1, '2015-11-28 00:05:07', 'picine1', 'http://www.piscinepassion.eu/site/images/normal/Diaporama-Bandeau54c238d8dbc35.jpg'),
(2, '2015-11-28 00:00:00', 'picine2', 'http://fr.ubergizmo.com/wp-content/uploads/2013/05/piscine-shutterstock_61753705.jpg'),
(3, '2015-11-28 08:23:00', 'picine3', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQVnQfxgmzM1Or1kKcYqLkWE7reUTq4QTeplmYfikKd-3kXTapX'),
(6, '2015-11-30 00:00:00', 'frigo', 'http://www.rouyn.radioenergie.ca/pics/Feeds/Articles/201591/752428/frigo.jpg'),
(8, '2015-11-30 03:08:20', 'vaissellethomson', 'http://image.darty.com/gros_electromenager/lave-vaisselle/lave-vaisselle/thomson_tdw_60_wh_p1506034079230B_142454451.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE IF NOT EXISTS `produit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `photo_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_29A5EC277E9E4C8C` (`photo_id`),
  KEY `IDX_29A5EC27BCF5E72D` (`categorie_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `produit`
--

INSERT INTO `produit` (`id`, `photo_id`, `categorie_id`, `libelle`) VALUES
(1, 1, 1, 'picine lux'),
(2, 2, 1, 'picine 2'),
(3, 3, 1, 'picine3'),
(4, 6, 2, 'frigo'),
(5, 8, 2, 'vaissellethomson');

-- --------------------------------------------------------

--
-- Structure de la table `produit_option`
--

CREATE TABLE IF NOT EXISTS `produit_option` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `produit_id` int(11) NOT NULL,
  `options_id` int(11) NOT NULL,
  `montant` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ADD4CB8AF347EFB` (`produit_id`),
  KEY `IDX_ADD4CB8A3ADB05F1` (`options_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `produit_option`
--

INSERT INTO `produit_option` (`id`, `produit_id`, `options_id`, `montant`) VALUES
(1, 1, 1, 300),
(2, 1, 2, 400),
(3, 2, 3, 100),
(4, 3, 4, 500);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_497B315E92FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_497B315EA0D96FBF` (`email_canonical`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC277E9E4C8C` FOREIGN KEY (`photo_id`) REFERENCES `photo` (`id`),
  ADD CONSTRAINT `FK_29A5EC27BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`);

--
-- Contraintes pour la table `produit_option`
--
ALTER TABLE `produit_option`
  ADD CONSTRAINT `FK_ADD4CB8A3ADB05F1` FOREIGN KEY (`options_id`) REFERENCES `options` (`id`),
  ADD CONSTRAINT `FK_ADD4CB8AF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
