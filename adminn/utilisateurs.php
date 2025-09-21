<?php
require_once 'header.php';

// Traitement de la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    try {
        $stmt = $conn->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $message = "Utilisateur supprimé avec succès";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Récupération des utilisateurs
$stmt = $conn->query("SELECT * FROM utilisateur ORDER BY date_inscription DESC");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des Utilisateurs</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($erreur)): ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php endif; ?>

<div class="actions">
    <a href="ajouter_utilisateur.php" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un utilisateur
    </a>
</div>

<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Date d'inscription</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?php echo $utilisateur['id_utilisateur']; ?></td>
                <td><?php echo $utilisateur['nom']; ?></td>
                <td><?php echo $utilisateur['prenom']; ?></td>
                <td><?php echo $utilisateur['email']; ?></td>
                <td><?php echo $utilisateur['type_utilisateur']; ?></td>
                <td><?php echo $utilisateur['date_inscription']; ?></td>
                <td class="actions">
                    <a href="modifier_utilisateur.php?id=<?php echo $utilisateur['id_utilisateur']; ?>" class="btn btn-sm btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="utilisateurs.php?supprimer=<?php echo $utilisateur['id_utilisateur']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once 'footer.php'; ?>