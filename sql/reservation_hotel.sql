-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 23 nov. 2026 à 16:04
-- Version du serveur :  10.1.35-MariaDB
-- Version de PHP :  7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `reservation_hotel`
--

-- --------------------------------------------------------

--
-- Structure de la table `chambres`
--

CREATE TABLE `chambres` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_chambre_id` int(10) UNSIGNED NOT NULL,
  `numero` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etage` int(11) NOT NULL,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `chambres`
--

INSERT INTO `chambres` (`id`, `type_chambre_id`, `numero`, `etage`, `statut`) VALUES
(1, 1, '101', 1, 'disponible'),
(2, 1, '102', 1, 'disponible'),
(3, 1, '103', 1, 'occupee'),
(4, 1, '104', 1, 'disponible'),
(5, 1, '105', 1, 'disponible'),
(6, 2, '201', 2, 'disponible'),
(7, 2, '202', 2, 'disponible'),
(8, 2, '203', 2, 'occupee'),
(9, 2, '204', 2, 'disponible'),
(10, 2, '205', 2, 'disponible');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(10) UNSIGNED NOT NULL,
  `utilisateur_id` int(10) UNSIGNED NOT NULL,
  `chambre_id` int(10) UNSIGNED NOT NULL,
  `date_arrivee` date NOT NULL,
  `date_depart` date NOT NULL,
  `statut` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'confirmee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `utilisateur_id`, `chambre_id`, `date_arrivee`, `date_depart`, `statut`) VALUES
(1, 2, 3, '2026-11-24', '2026-11-27', 'confirmee'),
(2, 2, 8, '2026-11-25', '2026-11-28', 'confirmee');

-- --------------------------------------------------------

--
-- Structure de la table `types_chambres`
--

CREATE TABLE `types_chambres` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_nuit` decimal(10,2) NOT NULL,
  `capacite` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `types_chambres`
--

INSERT INTO `types_chambres` (`id`, `nom`, `prix_nuit`, `capacite`) VALUES
(1, 'Standard', '80.00', 2),
(2, 'Suite', '160.00', 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Ayoub Aitouche', 'ayoub.aitouche@hotel.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'Fateh Mamri', 'fateh.mamri@hotel.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chambres`
--
ALTER TABLE `chambres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `fk_chambres_type` (`type_chambre_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reservations_utilisateur` (`utilisateur_id`),
  ADD KEY `fk_reservations_chambre` (`chambre_id`);

--
-- Index pour la table `types_chambres`
--
ALTER TABLE `types_chambres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chambres`
--
ALTER TABLE `chambres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `types_chambres`
--
ALTER TABLE `types_chambres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `chambres`
--
ALTER TABLE `chambres`
  ADD CONSTRAINT `fk_chambres_type` FOREIGN KEY (`type_chambre_id`) REFERENCES `types_chambres` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservations_chambre` FOREIGN KEY (`chambre_id`) REFERENCES `chambres` (`id`),
  ADD CONSTRAINT `fk_reservations_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
