<?php
require_once 'config.php';

// Récupérer la catégorie depuis l'URL ou par défaut
$categorie = $_GET['categorie'] ?? 'Lieux de réception';

// Récupérer les sous-catégories distinctes pour cette catégorie
$stmtSub = $pdo->prepare("SELECT DISTINCT sous_categorie FROM prestataire WHERE categorie = ? AND sous_categorie IS NOT NULL");
$stmtSub->execute([$categorie]);
$sousCategories = $stmtSub->fetchAll(PDO::FETCH_COLUMN);

// Récupérer toutes les régions distinctes pour cette catégorie
$stmtReg = $pdo->prepare("SELECT DISTINCT region FROM prestataire WHERE categorie = ? AND region IS NOT NULL");
$stmtReg->execute([$categorie]);
$regions = $stmtReg->fetchAll(PDO::FETCH_COLUMN);

// Récupérer tous les prestataires de cette catégorie
$stmt = $pdo->prepare("SELECT * FROM prestataire WHERE categorie = ?");
$stmt->execute([$categorie]);
$prestataires = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($categorie); ?> - Mariage Parfait</title>
    <meta name="description" content="Découvrez notre sélection de <?php echo htmlspecialchars($categorie); ?> pour votre mariage">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .category-hero {
            position: relative;
            height: 400px;
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: var(--spacing-8);
        }
        
        .category-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .category-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: var(--spacing-6);
            color: white;
        }
        
        .category-hero-content h1 {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-2);
        }
        
        .category-hero-content p {
            font-size: 1.25rem;
            max-width: 800px;
        }
        
        .category-info {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-8);
            margin-bottom: var(--spacing-8);
        }
        
        @media (min-width: 992px) {
            .category-info {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        .category-description {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-6);
            border: 1px solid var(--color-border);
        }
        
        .category-description h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-4);
            padding-bottom: var(--spacing-2);
            border-bottom: 1px solid var(--color-border);
        }
        
        .category-description p {
            margin-bottom: var(--spacing-4);
            line-height: 1.8;
        }
        
        .category-tips {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-6);
            border: 1px solid var(--color-border);
        }
        
        .category-tips h3 {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-4);
            padding-bottom: var(--spacing-2);
            border-bottom: 1px solid var(--color-border);
        }
        
        .tip-item {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-4);
        }
        
        .tip-item:last-child {
            margin-bottom: 0;
        }
        
        .tip-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .tip-content h4 {
            font-size: 1rem;
            margin-bottom: var(--spacing-1);
        }
        
        .tip-content p {
            font-size: 0.875rem;
            color: var(--color-muted-foreground);
        }
        
        .filters-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-8);
        }
        
        @media (min-width: 768px) {
            .filters-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        .filter-input {
            width: 100%;
            padding: var(--spacing-3) var(--spacing-4);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            background-color: var(--color-background);
            color: var(--color-foreground);
            font-family: var(--font-sans);
        }
        
        .search-input {
            position: relative;
        }
        
        .search-input i {
            position: absolute;
            left: var(--spacing-3);
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-muted-foreground);
        }
        
        .search-input input {
            padding-left: var(--spacing-8);
        }
        
        .select-container {
            position: relative;
        }
        
        .select-container select {
            appearance: none;
            padding-right: var(--spacing-8);
        }
        
        .select-container::after {
            content: '\f107';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            right: var(--spacing-4);
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
        
        .providers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: var(--spacing-6);
        }
        
        .provider-card {
            background-color: var(--color-background);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .provider-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .provider-image {
            height: 200px;
            overflow: hidden;
        }
        
        .provider-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s ease;
        }
        
        .provider-card:hover .provider-image img {
            transform: scale(1.1);
        }
        
        .provider-content {
            padding: var(--spacing-6);
        }
        
        .provider-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--spacing-4);
        }
        
        .provider-title {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-2);
        }
        
        .provider-price {
            font-weight: 600;
        }
        
        .provider-rating {
            display: flex;
            align-items: center;
            gap: var(--spacing-1);
            margin-bottom: var(--spacing-2);
        }
        
        .provider-rating i {
            color: #f59e0b;
        }
        
        .provider-reviews {
            color: var(--color-muted-foreground);
            font-size: 0.875rem;
        }
        
        .provider-tags {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-2);
            margin-bottom: var(--spacing-4);
        }
        
        .provider-tag {
            background-color: var(--color-primary-light);
            color: var(--color-primary);
            padding: var(--spacing-1) var(--spacing-2);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
        }
        
        .provider-tag.secondary {
            background-color: var(--color-secondary-light);
            color: var(--color-secondary);
        }
        
        .provider-description {
            margin-bottom: var(--spacing-4);
            display: -webkit-box;
            -web0kit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .provider-actions {
            display: flex;
            justify-content: space-between;
            gap: var(--spacing-2);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: var(--spacing-2);
            margin-top: var(--spacing-8);
        }
        
        .pagination-item {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            border: 1px solid var(--color-border);
            cursor: pointer;
            transition: var(--transition-base);
        }
        
        .pagination-item:hover {
            background-color: var(--color-primary-light);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }
        
        .pagination-item.active {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }
        
        .subcategories {
            display: flex;
            flex-wrap: wrap;
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-8);
        }
        
        .subcategory-tag {
            background-color: var(--color-background);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            padding: var(--spacing-2) var(--spacing-4);
            font-weight: 500;
            transition: var(--transition-base);
            cursor: pointer;
        }
        
        .subcategory-tag:hover {
            background-color: var(--color-primary-light);
            border-color: var(--color-primary);
            color: var(--color-primary);
        }
        
        .subcategory-tag.active {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }
    </style>
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

                    <!-- Navigation principale                   
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
                -->
                    
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
                <section>
                    <!-- Hero de la catégorie -->
                    <div class="category-hero">
                        <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($categorie); ?>" class="category-hero-image">
                        <div class="category-hero-overlay">
                            <div class="category-hero-content">
                                <h1><?php echo htmlspecialchars($categorie); ?></h1>
                                <p>Découvrez notre sélection de <?php echo htmlspecialchars($categorie); ?> pour votre mariage.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informations sur la catégorie (statique ou à dynamiser selon la catégorie) -->
                    <div class="category-info">
                        <div class="category-description">
                            <h2>Choisir le <?php echo htmlspecialchars(strtolower($categorie)); ?> parfait</h2>
                            <p>Le choix du <?php echo htmlspecialchars(strtolower($categorie)); ?> est essentiel pour la réussite de votre mariage. Comparez les options, visitez les lieux et contactez les prestataires pour plus d'informations.</p>
                        </div>
                        <div class="category-tips">
                            <h3>Conseils pour choisir</h3>
                            <div class="tip-item">
                                <div class="tip-icon"><i class="fas fa-calendar-alt"></i></div>
                                <div class="tip-content">
                                    <h4>Réservez à l'avance</h4>
                                    <p>Les meilleurs <?php echo htmlspecialchars(strtolower($categorie)); ?> sont souvent réservés longtemps à l'avance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sous-catégories dynamiques -->
                    <div class="subcategories">
                        <div class="subcategory-tag <?php if (!isset($_GET['sous_categorie'])) echo 'active'; ?>">
                            Tous les <?php echo htmlspecialchars(strtolower($categorie)); ?>
                        </div>
                        <?php foreach($sousCategories as $sc): ?>
                            <div class="subcategory-tag<?php if(($_GET['sous_categorie'] ?? '') === $sc) echo ' active'; ?>">
                                <?php echo htmlspecialchars($sc); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Filtres dynamiques -->
                    <form method="get" class="filters-container" style="margin-bottom:2rem;">
                        <input type="hidden" name="categorie" value="<?php echo htmlspecialchars($categorie); ?>">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" class="filter-input" name="q" placeholder="Rechercher un lieu..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                        </div>
                        <div class="select-container">
                            <select class="filter-input" name="region">
                                <option value="">Toutes les régions</option>
                                <?php foreach($regions as $reg): ?>
                                    <option value="<?php echo htmlspecialchars($reg); ?>" <?php if(($_GET['region'] ?? '') === $reg) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($reg); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="select-container">
                            <select class="filter-input" name="sort">
                                <option value="">Recommandation</option>
                                <option value="note" <?php if(($_GET['sort'] ?? '') === 'note') echo 'selected'; ?>>Note</option>
                                <option value="prix-croissant" <?php if(($_GET['sort'] ?? '') === 'prix-croissant') echo 'selected'; ?>>Prix croissant</option>
                                <option value="prix-decroissant" <?php if(($_GET['sort'] ?? '') === 'prix-decroissant') echo 'selected'; ?>>Prix décroissant</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-rounded">Filtrer</button>
                    </form>
                    
                    <!-- Liste des prestataires dynamiques -->
                    <div class="providers-grid" id="providers-container">
                        <?php
                        // Filtrage côté PHP
                        $q = strtolower($_GET['q'] ?? '');
                        $region = $_GET['region'] ?? '';
                        $sous_categorie = $_GET['sous_categorie'] ?? '';
                        $sort = $_GET['sort'] ?? '';

                        $filtered = array_filter($prestataires, function($p) use ($q, $region, $sous_categorie) {
                            $match = true;
                            if ($q) {
                                $match = stripos($p['nom_entreprise'], $q) !== false
                                    || stripos($p['description'], $q) !== false;
                            }
                            if ($match && $region) {
                                $match = $p['region'] === $region;
                            }
                            if ($match && $sous_categorie) {
                                $match = $p['sous_categorie'] === $sous_categorie;
                            }
                            return $match;
                        });

                        // Tri
                        if ($sort === 'note') {
                            usort($filtered, function($a, $b) use ($pdo) {
                                $stmtA = $pdo->prepare("SELECT AVG(note) FROM commentaire WHERE id_prestataire = ?");
                                $stmtA->execute([$a['id_prestataire']]);
                                $noteA = $stmtA->fetchColumn() ?: 0;
                                $stmtB = $pdo->prepare("SELECT AVG(note) FROM commentaire WHERE id_prestataire = ?");
                                $stmtB->execute([$b['id_prestataire']]);
                                $noteB = $stmtB->fetchColumn() ?: 0;
                                return $noteB <=> $noteA;
                            });
                        } elseif ($sort === 'prix-croissant') {
                            usort($filtered, function($a, $b) {
                                return ($a['prix'] ?? 0) <=> ($b['prix'] ?? 0);
                            });
                        } elseif ($sort === 'prix-decroissant') {
                            usort($filtered, function($a, $b) {
                                return ($b['prix'] ?? 0) <=> ($a['prix'] ?? 0);
                            });
                        }

                        if (empty($filtered)): ?>
                            <div class="no-results">Aucun lieu de réception ne correspond à vos critères de recherche.</div>
                        <?php else:
                            foreach($filtered as $provider):
                                $img = $provider['image_profil'] ?: 'images/placeholder.jpg';
                                // Calcul de la note moyenne
                                $stmtNote = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as nb FROM commentaire WHERE id_prestataire = ?");
                                $stmtNote->execute([$provider['id_prestataire']]);
                                $noteData = $stmtNote->fetch();
                                $moyenne = $noteData['moyenne'] ? round($noteData['moyenne'], 1) : '—';
                                $nbAvis = $noteData['nb'];
                        ?>
                        <div class="provider-card">
                            <div class="provider-image">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($provider['nom_entreprise']); ?>">
                            </div>
                            <div class="provider-content">
                                <div class="provider-header">
                                    <h3 class="provider-title"><?php echo htmlspecialchars($provider['nom_entreprise']); ?></h3>
                                    <div class="provider-price"><?php echo htmlspecialchars($provider['prix'] ?? ''); ?></div>
                                </div>
                                <div class="provider-rating">
                                    <?php
                                    for ($i=0; $i<5; $i++) {
                                        if ($i < floor($moyenne)) echo '<i class="fas fa-star"></i>';
                                        elseif ($i == floor($moyenne) && ($moyenne-floor($moyenne))>=0.5) echo '<i class="fas fa-star-half-alt"></i>';
                                        else echo '<i class="far fa-star"></i>';
                                    }
                                    ?>
                                    <span><?php echo $moyenne; ?></span>
                                    <span class="provider-reviews">(<?php echo $nbAvis; ?> avis)</span>
                                </div>
                                <div class="provider-tags">
                                    <span class="provider-tag"><?php echo htmlspecialchars($provider['sous_categorie']); ?></span>
                                    <span class="provider-tag secondary"><?php echo htmlspecialchars($provider['region']); ?></span>
                                </div>
                                <p class="provider-description"><?php echo htmlspecialchars($provider['description']); ?></p>
                                <div class="provider-actions">
                                    <a href="prestataire-detail.php?id=<?php echo $provider['id_prestataire']; ?>" class="btn btn-outline btn-rounded">Voir le profil</a>
                                    <a href="contact-prestataire.php?id=<?php echo $provider['id_prestataire']; ?>" class="btn btn-primary btn-rounded">Contacter</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; ?>
                    </div>
                </section>
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-column">
                        <h3 class="fancy-title">Mariage Parfait</h3>
                        <p>La plateforme qui vous aide à trouver les meilleurs prestataires pour votre mariage.</p>
                        <div class="social-links">
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="footer-column">
                        <h3>Liens rapides</h3>
                        <ul class="footer-links">
                            <li><a href="index.php">Accueil</a></li>
                            <li><a href="prestataires.php">Prestataires</a></li>
                            <li><a href="actualites.php">Actualités</a></li>
                            <li><a href="offres.php">Offres spéciales</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3>Informations</h3>
                        <ul class="footer-links">
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="contact.php">Devenir prestataire</a></li>
                            <li><a href="contact.php">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="footer-bottom">
                    <p>&copy; 2025 Mariage Parfait. Tous droits réservés.</p>
                    <p>Fait avec <i class="fas fa-heart"></i> pour les futurs mariés</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Script principal -->
    <script src="js/script.js"></script>
</body>
</html>