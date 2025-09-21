<?php
require_once 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Vérifier si l'offre existe
    $stmt = $conn->prepare("SELECT * FROM offre_speciale WHERE id_offre = ?");
    $stmt->execute([$id]);
    $offre = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($offre) {
        // Supprimer l'offre
        $stmt = $conn->prepare("DELETE FROM offre_speciale WHERE id_offre = ?");
        $stmt->execute([$id]);
        // Redirection après suppression
        header("Location: offres_admin.php?suppr=ok");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Offre introuvable.</div>";
    }
} else {
    echo "<div class='alert alert-danger'>ID invalide.</div>";
}

require_once 'footer.php';
?>