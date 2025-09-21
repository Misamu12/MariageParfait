<?php
require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récup info catégorie
$stmt = $pdo->prepare("SELECT * FROM categorie WHERE id_categorie = ?");
$stmt->execute([$id]);
$categorie = $stmt->fetch();

if (!$categorie) {
    echo "Catégorie introuvable.";
    exit;
}

// Récup prestataires liés à cette catégorie via le champ texte
$stmt = $pdo->prepare("
    SELECT *
    FROM prestataire
    WHERE categorie = ?
");
$stmt->execute([$categorie['nom_categorie']]);
$prestataires = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categorie['nom_categorie']) ?> - Prestataires</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <section class="section">
            <div class="section-header">
                <h1 class="section-title decorative-line"><?= htmlspecialchars($categorie['nom_categorie']) ?></h1>
                <p class="section-description">Découvrez les prestataires de la catégorie <strong><?= htmlspecialchars($categorie['nom_categorie']) ?></strong>.</p>
            </div>
            <div class="categories-grid">
                <?php if (count($prestataires)): ?>
                    <?php foreach($prestataires as $p): ?>
                        <div class="category-card card-fancy hover-lift bg-pink-light">
                            <div class="category-icon bg-white text-pink">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3><?= htmlspecialchars($p['nom_entreprise']) ?></h3>
                            <p><?= htmlspecialchars(mb_strimwidth($p['description'], 0, 100, '...')) ?></p>
                            <a href="prestataire-detail.php?id=<?= $p['id_prestataire'] ?>" class="btn btn-outline btn-rounded" style="margin-top:1rem;">Voir le profil</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="section-description" style="width:100%;">Aucun prestataire trouvé pour cette catégorie.</div>
                <?php endif; ?>
            </div>
            <div class="section-footer">
                <a href="index.php" class="btn btn-primary btn-rounded">Retour à l'accueil</a>
            </div>
        </section>
    </div>
</body>
</html>