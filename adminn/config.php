<?php
// Configuration de la base de données
$host = "localhost";
$dbname = "mariage";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

// Fonction pour vérifier si l'utilisateur est connecté
function estConnecte() {
    return isset($_SESSION['id_utilisateur']) && $_SESSION['type_utilisateur'] == 'admin';
}

// Fonction pour rediriger si non connecté
function verifierConnexion() {
    if (!estConnecte()) {
        header("Location: login.php");
        exit();
    }
}

// Fonction pour obtenir le nombre d'emails envoyés
function getNombreEmails() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM message_contact");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre d'utilisateurs
function getNombreUtilisateurs() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM utilisateur");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre de prestataires
function getNombrePrestataires() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM prestataire");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre de prestataires actifs
function getNombrePrestatairesActifs() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM prestataire");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre d'actualités
function getNombreActualites() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM actualite");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre d'actualités publiées
function getNombreActualitesPubliees() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM actualite WHERE date_publication <= CURRENT_DATE()");
    return $stmt->fetchColumn();
}

// Fonction pour calculer la variation en pourcentage par rapport au mois dernier
function getVariationPourcentage($table) {
    global $conn;

    // Détermine la colonne de date selon la table
    switch ($table) {
        case 'utilisateur':
            $dateCol = 'date_inscription';
            break;
        case 'message_contact':
            $dateCol = 'date_envoi';
            break;
        case 'actualite':
            $dateCol = 'date_publication';
            break;
        default:
            $dateCol = 'date_enregistrement'; // à adapter selon tes tables
    }

    // Nombre actuel
    $stmt = $conn->query("SELECT COUNT(*) FROM $table");
    $nombreActuel = $stmt->fetchColumn();

    // Nombre du mois dernier
    $stmt = $conn->query("SELECT COUNT(*) FROM $table WHERE MONTH($dateCol) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR($dateCol) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)");
    $nombreMoisDernier = $stmt->fetchColumn();

    if ($nombreMoisDernier == 0) {
        return 100;
    }

    return round((($nombreActuel - $nombreMoisDernier) / $nombreMoisDernier) * 100, 1);
}

// Fonction pour obtenir le nombre de demandes d'offre
function getNombreDemandesOffre() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM demande_offre");
    return $stmt->fetchColumn();
}

// Fonction pour obtenir le nombre de contacts prestataire
function getNombreContactsPrestataire() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM contact_prestataire");
    return $stmt->fetchColumn();
}

// Fonction pour la variation des demandes d'offre
function getVariationDemandesOffre() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM demande_offre WHERE MONTH(date_demande) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR(date_demande) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)");
    $moisDernier = $stmt->fetchColumn();
    $stmt = $conn->query("SELECT COUNT(*) FROM demande_offre");
    $actuel = $stmt->fetchColumn();
    if ($moisDernier == 0) return 100;
    return round((($actuel - $moisDernier) / $moisDernier) * 100, 1);
}

// Fonction pour la variation des contacts prestataire
function getVariationContactsPrestataire() {
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM contact_prestataire WHERE MONTH(date_envoi) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) AND YEAR(date_envoi) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)");
    $moisDernier = $stmt->fetchColumn();
    $stmt = $conn->query("SELECT COUNT(*) FROM contact_prestataire");
    $actuel = $stmt->fetchColumn();
    if ($moisDernier == 0) return 100;
    return round((($actuel - $moisDernier) / $moisDernier) * 100, 1);
}
?>