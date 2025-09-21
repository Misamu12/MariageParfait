<?php
// filepath: c:\xampp\htdocs\mariage_v2\wedding-Presto-last\offres.php
require_once 'config.php';

// Récupération de toutes les offres spéciales
$stmtOffers = $pdo->query("SELECT * FROM offre_speciale ORDER BY date_debut DESC");
$offers = $stmtOffers->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres spéciales - Mariage Parfait</title>
    <meta name="description" content="Toutes les offres spéciales proposées par nos prestataires partenaires pour votre mariage">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="page-wrapper">
        <!-- Navigation -->
        <header class="header" id="header">
            <div class="container">
                <div class="header-content">
                    <a href="index.php" class="logo">
                        <span class="fancy-title">Mariage Parfait</span>
                    </a>
                 
                    <nav class="main-nav">
                        <ul class="nav-list">
                            <li><a href="prestataires.php" class="nav-link">Préstataires</a></li>
                            <li><a href="actualites.php" class="nav-link">Actualités</a></li>
                            <li><a href="contact.php" class="nav-link">Contact</a></li>
                            <li>
                                <button class="theme-toggle" id="theme-toggle" aria-label="Changer de thème">
                                    <i class="fas fa-sun"></i>
                                    <i class="fas fa-moon"></i>
                                </button>
                            </li>
                            <?php if(!isset($_SESSION['user_id'])): ?>
                            <li>
                                <a href="connexion.php" class="btn btn-primary btn-rounded">Connexion</a>
                            </li>
                            <?php else: ?>
                            <li>
                                <a href="logout.php" class="btn btn-primary btn-rounded">Déconnexion</a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                    
                    <div class="mobile-nav-toggle">
                        <button class="menu-toggle" id="menu-toggle" aria-label="Menu">
                            <i class="fas fa-bars"></i>
                            <i class="fas fa-times"></i>
                        </button>
                        <button class="theme-toggle" id="mobile-theme-toggle" aria-label="Changer de thème">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="mobile-menu" id="mobile-menu">
                <nav class="mobile-nav">
                    <ul class="mobile-nav-list">
                        <li><a href="prestataires.php" class="nav-link">Préstataires</a></li>
                        <li><a href="actualites.php" class="mobile-nav-link">Actualités</a></li>
                        <li><a href="panier.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            Mon Panier (<span id="cart-count-nav">0</span>)
                            </a></li>
                        <li><a href="contact.php" class="mobile-nav-link">Contact</a></li>
                        <li>
                            <a href="connexion.php" class="btn btn-primary btn-rounded btn-block">Connexion</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <div class="container">
                <section class="offers-section section">
                    <div class="section-header">
                        <h1 class="section-title decorative-line">Toutes les offres spéciales</h1>
                        <p class="section-description">Retrouvez ici toutes les promotions et offres exclusives proposées par nos prestataires partenaires.</p>
                    </div>
                    <div class="offers-grid">
                        <?php foreach($offers as $offer): ?>
                            <?php
                                // Récupérer le nom du prestataire lié à l'offre
                                $providerName = '';
                                if (!empty($offer['id_prestataire'])) {
                                    $stmtProvider = $pdo->prepare("SELECT nom_entreprise FROM prestataire WHERE id_prestataire = ?");
                                    $stmtProvider->execute([$offer['id_prestataire']]);
                                    $provider = $stmtProvider->fetch();
                                    $providerName = $provider['nom_entreprise'] ?? '';
                                }
                                // Image de l'offre ou image par défaut
                                $img = !empty($offer['image']) ? $offer['image'] : 'images/placeholder.jpg';
                                // Badge selon le type ou la réduction
                                $badge = '';
                                if (!empty($offer['reduction'])) {
                                    $badge = '-' . intval($offer['reduction']) . '%';
                                    $badgeClass = 'bg-pink';
                                } elseif (!empty($offer['cadeau'])) {
                                    $badge = htmlspecialchars($offer['cadeau']);
                                    $badgeClass = 'bg-purple';
                                } else {
                                    $badge = 'Offre';
                                    $badgeClass = 'bg-cyan';
                                }
                                // Dates
                                $validity = '';
                                if (!empty($offer['date_fin'])) {
                                    $validity = 'Valable jusqu\'au ' . date('d/m/Y', strtotime($offer['date_fin']));
                                }
                            ?>
                            <div class="offer-card hover-lift">
                                <div class="offer-image">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($offer['titre'] ?? 'Offre spéciale'); ?>">
                                    <span class="offer-badge <?php echo $badgeClass; ?>"><?php echo $badge; ?></span>
                                </div>
                                <div class="offer-content">
                                    <h3><?php echo htmlspecialchars($offer['titre']); ?></h3>
                                    <p class="offer-provider"><?php echo htmlspecialchars($providerName); ?></p>
                                    <p class="offer-description"><?php echo htmlspecialchars($offer['description']); ?></p>
                                    <p class="offer-validity"><?php echo $validity; ?></p>
                                    <a href="offres-detail.php?id=<?php echo $offer['id_offre']; ?>" class="btn btn-primary btn-rounded btn-block">En profiter</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($offers)): ?>
                            <div class="no-results">Aucune offre spéciale n'est disponible pour le moment.</div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
        <!-- Footer (optionnel, à reprendre depuis index.php si besoin) -->
    </div>
    <script src="js/script.js"></script>
</body>
</html>