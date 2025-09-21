<?php
require_once 'header.php';

// Récupération des statistiques
$nombreEmails = getNombreEmails();
$nombreUtilisateurs = getNombreUtilisateurs();
$nombrePrestataires = getNombrePrestataires();
$nombrePrestatairesActifs = getNombrePrestatairesActifs();
$nombreActualites = getNombreActualites();
$nombreActualitesPubliees = getNombreActualitesPubliees();

// Calcul des variations
$variationEmails = getVariationPourcentage('message_contact');
$variationUtilisateurs = getVariationPourcentage('utilisateur');

// Récupération de la dernière campagne email
$stmt = $conn->query("SELECT * FROM message_contact ORDER BY date_envoi DESC LIMIT 1");
$dernierEmail = $stmt->fetch(PDO::FETCH_ASSOC);

$nombreDemandesOffre = getNombreDemandesOffre();
$variationDemandesOffre = getVariationDemandesOffre();

$nombreContactsPrestataire = getNombreContactsPrestataire();
$variationContactsPrestataire = getVariationContactsPrestataire();
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3>E-mails envoyés</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombreEmails); ?></p>
                <p class="stat-change <?php echo $variationEmails > 0 ? 'positive' : 'negative'; ?>">
                    <?php echo ($variationEmails > 0 ? '+' : '') . $variationEmails; ?>% vs mois dernier
                </p>
            </div>
            <div class="stat-icon email">
                <i class="fas fa-envelope"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <h3>Utilisateurs</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombreUtilisateurs); ?></p>
                <p class="stat-change <?php echo $variationUtilisateurs > 0 ? 'positive' : 'negative'; ?>">
                    <?php echo ($variationUtilisateurs > 0 ? '+' : '') . $variationUtilisateurs; ?>% vs mois dernier
                </p>
            </div>
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <h3>Prestataires</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombrePrestataires); ?></p>
                <p class="stat-subtext"><?php echo $nombrePrestatairesActifs; ?> actifs</p>
            </div>
            <div class="stat-icon providers">
                <i class="fas fa-store"></i>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid small">
    <div class="stat-card">
        <h3>Actualités</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombreActualites); ?></p>
                <p class="stat-subtext"><?php echo $nombreActualitesPubliees; ?> publiées</p>
            </div>
            <div class="stat-icon news">
                <i class="fas fa-newspaper"></i>
            </div>
        </div>
    </div>
</div>

<div class="activity-section">
    <h2>Activité récente</h2>
    <?php if ($dernierEmail): ?>
    <div class="activity-item">
        <div class="activity-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="activity-content">
            <h4>Campagne e-mail "Promotion Été" envoyée</h4>
            <p>892 destinataires • Il y a 2h</p>
        </div>
        <button class="btn btn-dark">Envoyé</button>
    </div>
    <?php else: ?>
    <p>Aucune activité récente</p>
    <?php endif; ?>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Demandes d'offre</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombreDemandesOffre); ?></p>
                <p class="stat-change <?php echo $variationDemandesOffre > 0 ? 'positive' : 'negative'; ?>">
                    <?php echo ($variationDemandesOffre > 0 ? '+' : '') . $variationDemandesOffre; ?>% vs mois dernier
                </p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-gift"></i>
            </div>
        </div>
    </div>

    <div class="stat-card">
        <h3>Contacts prestataire</h3>
        <div class="stat-content">
            <div>
                <p class="stat-value"><?php echo number_format($nombreContactsPrestataire); ?></p>
                <p class="stat-change <?php echo $variationContactsPrestataire > 0 ? 'positive' : 'negative'; ?>">
                    <?php echo ($variationContactsPrestataire > 0 ? '+' : '') . $variationContactsPrestataire; ?>% vs mois dernier
                </p>
            </div>
            <div class="stat-icon">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>