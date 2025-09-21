<?php
require_once 'header.php';

// Récupérer les contacts prestataire
$stmt = $conn->query("SELECT c.*, p.nom_entreprise 
    FROM contact_prestataire c 
    LEFT JOIN prestataire p ON c.id_prestataire = p.id_prestataire 
    ORDER BY c.date_envoi DESC");
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Contacts prestataire</h2>
<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Prestataire</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contacts as $contact): ?>
            <tr>
                <td><?php echo $contact['id_contact']; ?></td>
                <td><?php echo htmlspecialchars($contact['nom_entreprise']); ?></td>
                <td><?php echo htmlspecialchars($contact['nom']); ?></td>
                <td><?php echo htmlspecialchars($contact['email']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($contact['message'])); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($contact['date_envoi'])); ?></td>
                <td class="actions">
                    <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>?subject=Réponse à votre message" class="btn btn-sm btn-view" title="Répondre">
                        <i class="fas fa-comment"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($contacts)): ?>
            <tr><td colspan="7">Aucun message de contact prestataire.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once 'footer.php'; ?>