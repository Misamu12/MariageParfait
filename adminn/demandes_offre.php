<?php
require_once 'header.php';

// Récupérer les demandes d'offre
$stmt = $conn->query("SELECT d.*, o.titre AS offre, p.nom_entreprise 
    FROM demande_offre d 
    LEFT JOIN offre_speciale o ON d.id_offre = o.id_offre 
    LEFT JOIN prestataire p ON o.id_prestataire = p.id_prestataire 
    ORDER BY d.date_demande DESC");
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Demandes d'offre</h2>
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Offre</th>
                <th>Prestataire</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($demandes as $demande): ?>
            <tr>
                <td><?php echo $demande['id_demande']; ?></td>
                <td><?php echo htmlspecialchars($demande['offre']); ?></td>
                <td><?php echo htmlspecialchars($demande['nom_entreprise']); ?></td>
                <td><?php echo htmlspecialchars($demande['nom']); ?></td>
                <td><?php echo htmlspecialchars($demande['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($demande['message'])); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($demande['date_demande'])); ?></td>
                <td class="actions">
                    <a href="mailto:<?php echo htmlspecialchars($demande['email']); ?>?subject=Réponse à votre demande d'offre" class="btn btn-sm btn-view" title="Répondre">
                        <i class="fas fa-comment"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($demandes)): ?>
            <tr><td colspan="8">Aucune demande d'offre.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once 'footer.php'; ?>