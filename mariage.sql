-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 29 juin 2025 à 11:21
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mariage`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualite`
--

CREATE TABLE `actualite` (
  `id_actualite` int(11) NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_publication` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `actualite`
--

INSERT INTO `actualite` (`id_actualite`, `titre`, `contenu`, `image`, `date_publication`) VALUES
(8, 'Top mariage 2025', 'Les mariages classé parmi les mieux organiser cette année sont les mariage organiser par les prestataires VIP de De chez kin', '68604358280f0.webp', '2025-06-28');

-- --------------------------------------------------------

--
-- Structure de la table `biographie_proprietaire`
--

CREATE TABLE `biographie_proprietaire` (
  `id_bio` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `texte_biographique` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `biographie_proprietaire`
--

INSERT INTO `biographie_proprietaire` (`id_bio`, `nom`, `photo`, `texte_biographique`) VALUES
(1, 'Marie Dupont', '/placeholder.svg?height=300&width=300', 'Après 10 ans d\'expérience en tant que wedding planner, j\'ai créé Mariage Parfait avec une vision claire : simplifier l\'organisation des mariages en mettant en relation les futurs mariés avec les meilleurs prestataires de France.');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Lieux de réception'),
(2, 'Traiteurs'),
(3, 'Photographes / Vidéastes'),
(4, 'DJ / Musiciens'),
(5, 'Robes & Costumes'),
(6, 'Fleuristes'),
(7, 'Décorateurs'),
(8, 'Voiture de mariage'),
(9, 'Wedding planners');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_prestataire` int(11) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 0 and 5),
  `date_commentaire` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_utilisateur`, `id_prestataire`, `contenu`, `note`, `date_commentaire`) VALUES
(2, 2, 2, 'Photos sublimes, équipe très professionnelle. Je recommande vivement Studio Lumière.', 5, '2024-01-18');

-- --------------------------------------------------------

--
-- Structure de la table `contact_prestataire`
--

CREATE TABLE `contact_prestataire` (
  `id_contact` int(11) NOT NULL,
  `id_prestataire` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text DEFAULT NULL,
  `date_envoi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demande_offre`
--

CREATE TABLE `demande_offre` (
  `id_demande` int(11) NOT NULL,
  `id_offre` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text DEFAULT NULL,
  `date_demande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faq`
--

CREATE TABLE `faq` (
  `id_faq` int(11) NOT NULL,
  `question` text DEFAULT NULL,
  `reponse` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `faq`
--

INSERT INTO `faq` (`id_faq`, `question`, `reponse`) VALUES
(1, 'Comment réserver un prestataire ?', 'Pour réserver un prestataire, consultez sa page de profil et cliquez sur le bouton \"Contacter\". Vous pourrez alors lui envoyer un message avec vos besoins spécifiques et vos dates.'),
(2, 'Comment devenir prestataire sur la plateforme ?', 'Pour devenir prestataire partenaire, rendez-vous sur la page \"Devenir prestataire\" et remplissez le formulaire de candidature. Notre équipe examinera votre demande et vous contactera sous 48h.'),
(3, 'Les prix affichés sont-ils définitifs ?', 'Les prix affichés sur les profils des prestataires sont généralement des estimations ou des tarifs de base. Le prix final dépendra de vos besoins spécifiques.'),
(4, 'Comment fonctionnent les avis clients ?', 'Les avis clients sont publiés par des couples ayant effectivement fait appel aux services du prestataire via notre plateforme. Nous vérifions l\'authenticité de chaque avis.');

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

CREATE TABLE `galerie` (
  `id_photo` int(11) NOT NULL,
  `id_prestataire` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `legende` varchar(255) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `galerie`
--

INSERT INTO `galerie` (`id_photo`, `id_prestataire`, `image`, `legende`, `date_ajout`) VALUES
(4, 7, 'uploads/galerie/galerie_6860461c70e4a_product-image-1890613794.webp', 'Robe ZARA albama', '2025-06-28 21:44:28');

-- --------------------------------------------------------

--
-- Structure de la table `lien_rapide`
--

CREATE TABLE `lien_rapide` (
  `id_lien` int(11) NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message_contact`
--

CREATE TABLE `message_contact` (
  `id_message` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sujet` varchar(150) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `date_envoi` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offre_speciale`
--

CREATE TABLE `offre_speciale` (
  `id_offre` int(11) NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `id_prestataire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offre_speciale`
--

INSERT INTO `offre_speciale` (`id_offre`, `titre`, `description`, `date_debut`, `date_fin`, `image`, `id_prestataire`) VALUES
(7, 'Top mariage 2025', 'De chez Kin ouvre une réduction de 30% à la location ou achat de la robe ZARA albama. Depêcher vous de demander vos devis pour en bénéficier !', '2025-06-28', '2025-06-30', 'uploads/offres/offre_686042f290bd7_product-image-1890613794.webp', 7);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id_panier` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `id_prestataire` int(11) NOT NULL,
  `quantite` int(11) DEFAULT 1,
  `date_ajout` datetime DEFAULT current_timestamp(),
  `nom` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `date_mariage` date DEFAULT NULL,
  `nb_invites` int(11) DEFAULT NULL,
  `budget` varchar(50) DEFAULT NULL,
  `demandes_speciales` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id_panier`, `session_id`, `id_prestataire`, `quantite`, `date_ajout`, `nom`, `email`, `telephone`, `date_mariage`, `nb_invites`, `budget`, `demandes_speciales`) VALUES
(2, 'ua63l55p2sgoutcg4b3ho3o7p7', 2, 1, '2025-06-23 23:56:57', 'Gedeon Banyi Bantu', 'gbelsalvador6@gmail.com', '0851944783', '2025-06-17', 23, '5000-10000', 'ijifje'),
(3, 'edan0r581ejls127jtgrk1g356', 2, 1, '2025-06-28 19:06:33', 'nom', 'ordidimbi@gmail.com', '0830854244', '2025-11-11', 300, '5000-10000', 'J\'ai besoin que vous me fassiez un bon prix !'),
(4, 'edan0r581ejls127jtgrk1g356', 2, 1, '2025-06-28 20:32:19', 'nom', 'ordidimbi@gmail.com', '0830854244', '2025-11-11', 300, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `prestataire`
--

CREATE TABLE `prestataire` (
  `id_prestataire` int(11) NOT NULL,
  `nom_entreprise` varchar(150) DEFAULT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `image_profil` varchar(255) DEFAULT NULL,
  `contact_telephone` varchar(50) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `date_enregistrement` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prestataire`
--

INSERT INTO `prestataire` (`id_prestataire`, `nom_entreprise`, `categorie`, `description`, `region`, `image_profil`, `contact_telephone`, `contact_email`, `date_enregistrement`) VALUES
(2, 'Studio CORA', 'Photographes / Vidéastes', 'Équipe de photographes et vidéastes passionnés, spécialisés dans le reportage de mariage naturel et authentique.', 'Provence-Alpes-Côte d\'Azur', 'uploads/prestataires/prest_6860360ec0996_CORA 2.png', '04 56 78 90 12', 'contact@studiolumiere.fr', '2025-06-07'),
(7, 'De chez kin', 'Costume/Robe', 'Une maison d\'habillemnt spécialiser dans des sérémonie de mariage.  Nous metons une large gamme des costumes et robes', 'Ngombe', 'uploads/prestataires/prest_68603dd8c8368_logo dck.png', '0991243221', 'ordi@gmail.com', '2025-06-28');

-- --------------------------------------------------------

--
-- Structure de la table `publicite`
--

CREATE TABLE `publicite` (
  `id_publicite` int(11) NOT NULL,
  `titre` varchar(150) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `nom_client` varchar(100) DEFAULT NULL,
  `email_client` varchar(150) DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `publicite`
--

INSERT INTO `publicite` (`id_publicite`, `titre`, `video_url`, `nom_client`, `email_client`, `date_debut`, `date_fin`, `date_creation`) VALUES
(3, 'Promotion shoting', 'uploads/publicites/pub_68603748d3dc3_Snapchat-843750373.mp4', 'Ordi DIMBI', 'ordi@gmail.com', '2025-06-28', '2025-06-29', '2025-06-28 20:41:12');

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id_service` int(11) NOT NULL,
  `id_prestataire` int(11) DEFAULT NULL,
  `nom_service` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `prix_location` decimal(10,2) DEFAULT NULL,
  `image_service` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `service_categorie`
--

CREATE TABLE `service_categorie` (
  `id_service` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `type_utilisateur` varchar(50) DEFAULT 'user',
  `date_inscription` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `type_utilisateur`, `date_inscription`) VALUES
(1, 'Admin', 'System', 'admin@mariageparfait.fr', '$2y$10$uipWAwAY7O8WGQjF0UpDjOe0j.CgVeSRfpZHj66A0wDLhWfx9ru5q', 'admin', '2025-06-07'),
(2, 'Dupont', 'Marie', 'marie.dupont@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2025-06-07'),
(3, 'Martin', 'Pierre', 'pierre.martin@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'prestataire', '2025-06-07'),
(4, 'Bernard', 'Sophie', 'sophie.bernard@email.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'prestataire', '2025-06-07'),
(7, 'jean', 'ordi', 'ordi@gmail.com', '$2y$10$P6WeKo6NYutkg0nn8HgtAedCVYZOyYy/pIHJnldH7sEmxqvZTXbAq', 'user', '2025-06-28');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actualite`
--
ALTER TABLE `actualite`
  ADD PRIMARY KEY (`id_actualite`);

--
-- Index pour la table `biographie_proprietaire`
--
ALTER TABLE `biographie_proprietaire`
  ADD PRIMARY KEY (`id_bio`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `contact_prestataire`
--
ALTER TABLE `contact_prestataire`
  ADD PRIMARY KEY (`id_contact`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `demande_offre`
--
ALTER TABLE `demande_offre`
  ADD PRIMARY KEY (`id_demande`),
  ADD KEY `id_offre` (`id_offre`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id_faq`);

--
-- Index pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD PRIMARY KEY (`id_photo`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `lien_rapide`
--
ALTER TABLE `lien_rapide`
  ADD PRIMARY KEY (`id_lien`);

--
-- Index pour la table `message_contact`
--
ALTER TABLE `message_contact`
  ADD PRIMARY KEY (`id_message`);

--
-- Index pour la table `offre_speciale`
--
ALTER TABLE `offre_speciale`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id_panier`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `prestataire`
--
ALTER TABLE `prestataire`
  ADD PRIMARY KEY (`id_prestataire`);

--
-- Index pour la table `publicite`
--
ALTER TABLE `publicite`
  ADD PRIMARY KEY (`id_publicite`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id_service`),
  ADD KEY `id_prestataire` (`id_prestataire`);

--
-- Index pour la table `service_categorie`
--
ALTER TABLE `service_categorie`
  ADD PRIMARY KEY (`id_service`,`id_categorie`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actualite`
--
ALTER TABLE `actualite`
  MODIFY `id_actualite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `biographie_proprietaire`
--
ALTER TABLE `biographie_proprietaire`
  MODIFY `id_bio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `contact_prestataire`
--
ALTER TABLE `contact_prestataire`
  MODIFY `id_contact` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `demande_offre`
--
ALTER TABLE `demande_offre`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `galerie`
--
ALTER TABLE `galerie`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `lien_rapide`
--
ALTER TABLE `lien_rapide`
  MODIFY `id_lien` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message_contact`
--
ALTER TABLE `message_contact`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offre_speciale`
--
ALTER TABLE `offre_speciale`
  MODIFY `id_offre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id_panier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `prestataire`
--
ALTER TABLE `prestataire`
  MODIFY `id_prestataire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `publicite`
--
ALTER TABLE `publicite`
  MODIFY `id_publicite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id_service` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id_utilisateur`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `contact_prestataire`
--
ALTER TABLE `contact_prestataire`
  ADD CONSTRAINT `contact_prestataire_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `demande_offre`
--
ALTER TABLE `demande_offre`
  ADD CONSTRAINT `demande_offre_ibfk_1` FOREIGN KEY (`id_offre`) REFERENCES `offre_speciale` (`id_offre`);

--
-- Contraintes pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD CONSTRAINT `galerie_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `offre_speciale`
--
ALTER TABLE `offre_speciale`
  ADD CONSTRAINT `offre_speciale_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`id_prestataire`) REFERENCES `prestataire` (`id_prestataire`);

--
-- Contraintes pour la table `service_categorie`
--
ALTER TABLE `service_categorie`
  ADD CONSTRAINT `service_categorie_ibfk_1` FOREIGN KEY (`id_service`) REFERENCES `service` (`id_service`),
  ADD CONSTRAINT `service_categorie_ibfk_2` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
