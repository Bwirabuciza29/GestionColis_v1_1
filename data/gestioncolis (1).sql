-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 19 août 2024 à 11:11
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestioncolis`
--

-- --------------------------------------------------------

--
-- Structure de la table `bureau`
--

CREATE TABLE `bureau` (
  `id_bureau` int(11) NOT NULL,
  `nom_bureau` varchar(100) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `type_bureau` enum('Origine','Destination') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `bureau`
--

INSERT INTO `bureau` (`id_bureau`, `nom_bureau`, `adresse`, `type_bureau`) VALUES
(1, 'Bureau Kampala', 'Kampala 35; roudabout Nyarugenge', 'Origine'),
(3, 'Kinshasa', 'Kinshasa, ngaliema cd', 'Origine'),
(4, 'Bureau Central', 'Goma', 'Origine');

-- --------------------------------------------------------

--
-- Structure de la table `colis`
--

CREATE TABLE `colis` (
  `id_colis` int(11) NOT NULL,
  `reference_colis` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `poids` decimal(10,2) DEFAULT NULL,
  `photo` varchar(200) NOT NULL,
  `date_embarquement` datetime NOT NULL,
  `date_arrivee` datetime NOT NULL,
  `bureau_depart` varchar(100) NOT NULL,
  `bureau_arrivee` varchar(100) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `colis`
--

INSERT INTO `colis` (`id_colis`, `reference_colis`, `description`, `poids`, `photo`, `date_embarquement`, `date_arrivee`, `bureau_depart`, `bureau_arrivee`, `created_by`, `id_utilisateur`) VALUES
(17, 'REF0001', 'Mukate', '3.00', 'img/45505.jpg', '2024-08-18 19:43:00', '2024-08-06 00:00:00', '1', '3', 1, 1),
(20, 'REF0002', 'Jordan 1', '12.00', 'img/IMG-20240815-WA0321.jpg', '2024-08-18 20:36:38', '2024-08-18 00:00:00', '3', '1', 3, 3),
(21, 'REF0003', 'Matelas', '10.00', 'img/45505.jpg', '2024-08-19 09:47:32', '2024-08-21 00:00:00', '1', '3', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `envoyer`
--

CREATE TABLE `envoyer` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_colis` int(11) DEFAULT NULL,
  `date_sortie` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `envoyer`
--

INSERT INTO `envoyer` (`id`, `id_utilisateur`, `id_colis`, `date_sortie`, `created_by`) VALUES
(20, 3, 17, '2024-08-18 17:49:16', 1),
(21, 1, 20, '2024-08-18 20:23:39', 3),
(22, 3, 21, '2024-08-19 07:48:13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `historique_stock`
--

CREATE TABLE `historique_stock` (
  `id_historique` int(11) NOT NULL,
  `id_colis` int(11) NOT NULL,
  `date_mouvement` datetime NOT NULL,
  `type_mouvement` enum('Entrée','Sortie') NOT NULL,
  `quantite_avant` int(11) NOT NULL,
  `quantite_apres` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_notification` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id`, `id_utilisateur`, `message`, `date_notification`) VALUES
(1, 1, 'Un colis avec la référence REF0002 et la description \"Jordan 1\" est en route depuis Kinshasa. Nombre de jours restants : 0', '2024-08-18 22:23:39'),
(2, 3, 'Un colis avec la référence REF0003 et la description \"Matelas\" est en route depuis Bureau Kampala. Nombre de jours restants : 1', '2024-08-19 09:48:13');

-- --------------------------------------------------------

--
-- Structure de la table `stock`
--

CREATE TABLE `stock` (
  `id_stock` int(11) NOT NULL,
  `id_colis` int(11) NOT NULL,
  `date_entree` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `date_sortie` datetime DEFAULT NULL,
  `quantite` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom_utilisateur` varchar(100) NOT NULL,
  `role` enum('Gestionnaire','Employé') NOT NULL,
  `bureau_affecte` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `role`, `bureau_affecte`, `email`, `mot_de_passe`, `salt`, `date_creation`) VALUES
(1, 'Bureau Kampala', 'Gestionnaire', 1, 'kamplala@gmail.com', '$2y$10$9oIJxuhMf1M4B12XPCLEeOk6Ao94Vja8tV9Gmspmq0OeS4mrteeJe', NULL, '2024-08-12 18:52:00'),
(3, 'Kinshasa', 'Gestionnaire', 3, 'kinshasa@gmail.com', '$2y$10$/csiwXpDnlgkzFWPmFJQMeZYilkIchHGxMOswHbIsyEVbxEToxZgO', NULL, '2024-08-12 19:55:38'),
(4, 'Bureau Central', 'Employé', 4, 'goma@gmail.com', '$2y$10$9oIJxuhMf1M4B12XPCLEeOk6Ao94Vja8tV9Gmspmq0OeS4mrteeJe', NULL, '2024-08-12 19:59:23');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bureau`
--
ALTER TABLE `bureau`
  ADD PRIMARY KEY (`id_bureau`);

--
-- Index pour la table `colis`
--
ALTER TABLE `colis`
  ADD PRIMARY KEY (`id_colis`),
  ADD KEY `fk_utilisateur_colis` (`id_utilisateur`);

--
-- Index pour la table `envoyer`
--
ALTER TABLE `envoyer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `no_me` (`id_utilisateur`),
  ADD KEY `me_no` (`id_colis`);

--
-- Index pour la table `historique_stock`
--
ALTER TABLE `historique_stock`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `id_colis` (`id_colis`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`id_stock`),
  ADD KEY `fy_col` (`id_colis`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD KEY `bureau_affecte` (`bureau_affecte`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bureau`
--
ALTER TABLE `bureau`
  MODIFY `id_bureau` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `colis`
--
ALTER TABLE `colis`
  MODIFY `id_colis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `envoyer`
--
ALTER TABLE `envoyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `historique_stock`
--
ALTER TABLE `historique_stock`
  MODIFY `id_historique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `stock`
--
ALTER TABLE `stock`
  MODIFY `id_stock` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `colis`
--
ALTER TABLE `colis`
  ADD CONSTRAINT `fk_utilisateur_colis` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `envoyer`
--
ALTER TABLE `envoyer`
  ADD CONSTRAINT `me_no` FOREIGN KEY (`id_colis`) REFERENCES `colis` (`id_colis`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `no_me` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `historique_stock`
--
ALTER TABLE `historique_stock`
  ADD CONSTRAINT `historique_stock_ibfk_1` FOREIGN KEY (`id_colis`) REFERENCES `colis` (`id_colis`);

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`);

--
-- Contraintes pour la table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `fy_col` FOREIGN KEY (`id_colis`) REFERENCES `colis` (`id_colis`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`bureau_affecte`) REFERENCES `bureau` (`id_bureau`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
