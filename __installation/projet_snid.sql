-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 28 Mai 2014 à 10:12
-- Version du serveur: 5.5.37
-- Version de PHP: 5.4.4-14+deb7u9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `projet_snid`
--
DROP TABLE IF EXISTS `bo_utilisateurs`;
DROP TABLE IF EXISTS `fo_configuration`;
DROP TABLE IF EXISTS `fo_liens`;
DROP TABLE IF EXISTS `fo_templates`;
DROP TABLE IF EXISTS `fo_noeuds`;
DROP TABLE IF EXISTS `fo_categories`;

-- --------------------------------------------------------

--
-- Structure de la table `bo_utilisateurs`
--

DROP TABLE IF EXISTS `bo_utilisateurs`;
CREATE TABLE IF NOT EXISTS `bo_utilisateurs` (
  `id_utilisateur` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identifiant` varchar(15) NOT NULL,
  `mot_de_passe` varchar(40) DEFAULT NULL,
  `droits` varchar(2) DEFAULT '00',
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `bo_utilisateurs`
--
-- 072e1ad689499ae2d4abdab8fb5ce9cf13541a54 ==> "projet_snid" avec salt ==> "inra_mapping+2013-2014"

INSERT INTO `bo_utilisateurs` (`id_utilisateur`, `identifiant`, `mot_de_passe`, `droits`) VALUES
(1, 'administrateur', '072e1ad689499ae2d4abdab8fb5ce9cf13541a54', '11');

-- --------------------------------------------------------

--
-- Structure de la table `fo_categories`
--

DROP TABLE IF EXISTS `fo_categories`;
CREATE TABLE IF NOT EXISTS `fo_categories` (
  `id_categorie` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nom_entier` text,
  `nom_partiel` varchar(10) DEFAULT NULL,
  `couleur_liaisons` varchar(11) DEFAULT '&num;808080',
  `couleur_liaisons_select` varchar(11) DEFAULT '&num;A0A0A0',
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fo_configurations`
--

DROP TABLE IF EXISTS `fo_configurations`;
CREATE TABLE IF NOT EXISTS `fo_configurations` (
  `id_configuration` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `liens_visible` enum('true','false') DEFAULT 'true',
  `mode_navigation` enum('normal','direct') DEFAULT 'normal',
  `is_active` enum('true','false') DEFAULT 'false',
  `couleur_noeud_interne` varchar(11) DEFAULT '&num;000000',
  `couleur_noeud_externe` varchar(11) DEFAULT '&num;000000',
  PRIMARY KEY (`id_configuration`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `fo_configurations`
--

INSERT INTO `fo_configurations` (`id_configuration`, `liens_visible`, `mode_navigation`, `is_active`, `couleur_noeud_interne`, `couleur_noeud_externe`) VALUES
(1, 'false', 'normal', 'true', '&num;828282', '&num;B4B4B4');

-- --------------------------------------------------------

--
-- Structure de la table `fo_liens`
--

DROP TABLE IF EXISTS `fo_liens`;
CREATE TABLE IF NOT EXISTS `fo_liens` (
  `id_lien` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_noeud_1` int(10) unsigned NOT NULL,
  `id_noeud_2` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_lien`),
  KEY `id_noeud_1_idx` (`id_noeud_1`),
  KEY `id_noeud_2_idx` (`id_noeud_2`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fo_noeuds`
--

DROP TABLE IF EXISTS `fo_noeuds`;
CREATE TABLE IF NOT EXISTS `fo_noeuds` (
  `id_noeud` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_categorie` int(10) unsigned NOT NULL,
  `nom_entier` text,
  `nom_partiel` varchar(10) DEFAULT NULL,
  `url_redirection` text,
  PRIMARY KEY (`id_noeud`),
  KEY `id_categorie_idx` (`id_categorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fo_templates`
--

DROP TABLE IF EXISTS `fo_templates`;
CREATE TABLE IF NOT EXISTS `fo_templates` (
  `id_template` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_noeud` int(10) unsigned NOT NULL,
  `contenu` text,
  PRIMARY KEY (`id_template`),
  KEY `id_noeud_idx` (`id_noeud`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `fo_liens`
--
ALTER TABLE `fo_liens`
  ADD CONSTRAINT `id_noeud_1` FOREIGN KEY (`id_noeud_1`) REFERENCES `fo_noeuds` (`id_noeud`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_noeud_2` FOREIGN KEY (`id_noeud_2`) REFERENCES `fo_noeuds` (`id_noeud`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `fo_noeuds`
--
ALTER TABLE `fo_noeuds`
  ADD CONSTRAINT `id_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `fo_categories` (`id_categorie`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `fo_templates`
--
ALTER TABLE `fo_templates`
  ADD CONSTRAINT `fo_noeud_templates` FOREIGN KEY (`id_noeud`) REFERENCES `fo_noeuds` (`id_noeud`) ON DELETE CASCADE ON UPDATE NO ACTION;
