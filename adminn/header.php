<?php
session_start();
require_once 'config.php';
verifierConnexion();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wedding</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="logo">
                <div class="logo-icon"><i class="fas fa-cog"></i></div>
                <span>Admin Panel</span>
            </div>
            <nav>
                <ul>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <a href="index.php">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                            <?php if (basename($_SERVER['PHP_SELF']) == 'index.php'): ?>
                                <span class="badge"></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'actualites.php' ? 'active' : ''; ?>">
                        <a href="actualites.php">
                            <i class="fas fa-newspaper"></i>
                            <span>Actualités</span>
                            <span class="badge"><?php echo getNombreActualites(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'prestataires.php' ? 'active' : ''; ?>">
                        <a href="prestataires.php">
                            <i class="fas fa-store"></i>
                            <span>Prestataires</span>
                            <span class="badge"><?php echo getNombrePrestataires(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'emails.php' ? 'active' : ''; ?>">
                        <a href="emails.php">
                            <i class="fas fa-envelope"></i>
                            <span>E-mails</span>
                            <span class="badge"><?php echo getNombreEmails(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'utilisateurs.php' ? 'active' : ''; ?>">
                        <a href="utilisateurs.php">
                            <i class="fas fa-users"></i>
                            <span>Utilisateurs</span>
                            <span class="badge"><?php echo getNombreUtilisateurs(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'demandes_offre.php' ? 'active' : ''; ?>">
                        <a href="demandes_offre.php">
                            <i class="fas fa-gift"></i>
                            <span>Demandes d'offre</span>
                            <span class="badge"><?php echo getNombreDemandesOffre(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'contacts_prestataire.php' ? 'active' : ''; ?>">
                        <a href="contacts_prestataire.php">
                            <i class="fas fa-envelope-open-text"></i>
                            <span>Contacts prestataire</span>
                            <span class="badge"><?php echo getNombreContactsPrestataire(); ?></span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'offres_admin.php' ? 'active' : ''; ?>">
                        <a href="offres_admin.php">
                            <i class="fas fa-tags"></i>
                            <span>Ajouter / Gérer les offres</span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'galerie_admin.php' ? 'active' : ''; ?>">
                        <a href="galerie_admin.php">
                            <i class="fas fa-images"></i>
                            <span>Galerie prestataires</span>
                        </a>
                    </li>
                    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'publicite.php' ? 'active' : ''; ?>">
                        <a href="publicite.php">
                            <i class="fas fa-bullhorn"></i>
                            <span>Publicité</span>
                            <span class="badge">
                                <?php
                                // Affiche le nombre de publicités
                                $nbPub = 0;
                                try {
                                    $stmt = $conn->query("SELECT COUNT(*) FROM publicite");
                                    $nbPub = $stmt->fetchColumn();
                                } catch (Exception $e) {}
                                echo $nbPub;
                                ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="main-content">
            <header>
                <h1>Dashboard</h1>
                <div class="header-right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Rechercher...">
                    </div>
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">A</div>
                        <span>Administrateur</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>
            <div class="content">