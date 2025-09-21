<?php
require_once 'header.php';

// Suppression d'une demande
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_panier = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM panier WHERE id_panier = ?");
    $stmt->execute([$id_panier]);
    header("Location: PANIER.php?deleted=1");
    exit;
}

// Récupérer toutes les demandes de devis (panier)
$stmt = $conn->query("
    SELECT p.*, pr.nom_entreprise 
    FROM panier p
    LEFT JOIN prestataire pr ON p.id_prestataire = pr.id_prestataire
    ORDER BY p.date_ajout DESC
");
$demandes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demandes de devis - Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="content">
    <h2>Demandes de devis (Panier)</h2>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="notification notification-success">Demande supprimée avec succès.</div>
    <?php endif; ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Prestataire</th>
                <th>Nom client</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Date mariage</th>
                <th>Invités</th>
                <th>Budget</th>
                <th>Demandes spéciales</th>
                <th>Répondre</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($demandes as $demande): ?>
            <tr>
                <td><?= $demande['id_panier'] ?></td>
                <td><?= htmlspecialchars($demande['date_ajout']) ?></td>
                <td><?= htmlspecialchars($demande['nom_entreprise']) ?></td>
                <td><?= htmlspecialchars($demande['nom']) ?></td>
                <td><?= htmlspecialchars($demande['email']) ?></td>
                <td><?= htmlspecialchars($demande['telephone']) ?></td>
                <td><?= htmlspecialchars($demande['date_mariage']) ?></td>
                <td><?= htmlspecialchars($demande['nb_invites']) ?></td>
                <td><?= htmlspecialchars($demande['budget']) ?></td>
                <td><?= nl2br(htmlspecialchars($demande['demandes_speciales'])) ?></td>
                <td>
                    <?php if (!empty($demande['email'])): ?>
                        <a href="mailto:<?= htmlspecialchars($demande['email']) ?>?subject=Réponse à votre demande de devis">Répondre</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="PANIER.php?delete=<?= $demande['id_panier'] ?>" onclick="return confirm('Supprimer cette demande ?')">
                        <i class="fas fa-trash"></i> Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>