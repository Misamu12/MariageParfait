<?php
require_once 'header.php';

// Liste des photos
$stmt = $conn->query("SELECT g.*, p.nom_entreprise 
    FROM galerie g 
    LEFT JOIN prestataire p ON g.id_prestataire = p.id_prestataire 
    ORDER BY g.date_ajout DESC");
$photos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_photo = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT image FROM galerie WHERE id_photo=?");
    $stmt->execute([$id_photo]);
    $img = $stmt->fetchColumn();
    if ($img && file_exists("../$img")) unlink("../$img");
    $conn->prepare("DELETE FROM galerie WHERE id_photo=?")->execute([$id_photo]);
    header("Location: galerie_admin.php?deleted=1");
    exit;
}
?>
<div class="content">
    <h2>Galerie des prestataires</h2>
    <div style="margin-bottom:1.5rem;">
        <a href="galerie_ajouter.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter une photo</a>
    </div>
    <div class="data-table">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Prestataire</th>
                    <th>Image</th>
                    <th>Légende</th>
                    <th>Date ajout</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($photos as $photo): ?>
                <tr>
                    <td><?php echo $photo['id_photo']; ?></td>
                    <td><?php echo htmlspecialchars($photo['nom_entreprise']); ?></td>
                    <td>
                        <?php if ($photo['image']): ?>
                            <img src="../<?php echo htmlspecialchars($photo['image']); ?>" alt="" style="max-width:80px;max-height:60px;">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($photo['legende']); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($photo['date_ajout'])); ?></td>
                    <td>
                        <a href="galerie_admin.php?delete=<?php echo $photo['id_photo']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Supprimer cette photo ?');">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($photos)): ?>
                <tr><td colspan="6">Aucune photo dans la galerie.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once 'footer.php'; ?>