<?php
require_once 'header.php';

// Récupérer toutes les offres spéciales
$stmt = $conn->query("SELECT o.*, p.nom_entreprise FROM offre_speciale o LEFT JOIN prestataire p ON o.id_prestataire = p.id_prestataire ORDER BY o.date_debut DESC");
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Gestion des Offres Spéciales</h2>
<div style="margin-bottom:1.5rem;">
    <a href="offre_creer.php" class="btn btn-primary"><i class="fas fa-plus"></i> Créer une offre</a>
</div>
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Prestataire</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offres as $offre): ?>
            <tr>
                <td><?php echo $offre['id_offre']; ?></td>
                <td><?php echo htmlspecialchars($offre['titre']); ?></td>
                <td><?php echo htmlspecialchars($offre['nom_entreprise']); ?></td>
                <td><?php echo htmlspecialchars($offre['date_debut']); ?></td>
                <td><?php echo htmlspecialchars($offre['date_fin']); ?></td>
                <td class="actions">
                    <a href="offre_modifier.php?id=<?php echo $offre['id_offre']; ?>" class="btn btn-sm btn-edit" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="offre_supprimer.php?id=<?php echo $offre['id_offre']; ?>" class="btn btn-sm btn-delete" title="Supprimer" onclick="return confirm('Supprimer cette offre ?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($offres)): ?>
            <tr><td colspan="6">Aucune offre spéciale.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once 'footer.php'; ?>