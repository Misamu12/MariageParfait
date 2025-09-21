<?php
require_once 'config.php';

// Récupérer toutes les catégories et régions distinctes pour les filtres
$categories = $pdo->query("SELECT DISTINCT categorie FROM prestataire WHERE categorie IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);
$regions = $pdo->query("SELECT DISTINCT region FROM prestataire WHERE region IS NOT NULL")->fetchAll(PDO::FETCH_COLUMN);

// Récupérer tous les prestataires
$stmt = $pdo->query("SELECT * FROM prestataire");
$prestataires = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestataires - Mariage Parfait</title>
    <meta name="description" content="Découvrez notre sélection de prestataires de qualité pour votre mariage">
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Styles spécifiques à la page prestataires -->
    <style>
        /* ===== PAGE HEADER ===== */
        .page-header {
            background-color: var(--color-primary-light);
            padding: var(--spacing-16) 0 var(--spacing-8);
            text-align: center;
            margin-top: 64px;
        }

        .page-header h1 {
            font-size: 3rem;
            margin-bottom: var(--spacing-4);
            color: var(--color-primary);
        }

        .page-header p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: 0 auto;
            color: var(--color-muted-foreground);
        }

        .section-title{
            margin-top: 64px;
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
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .provider-actions {
            display: flex;
            justify-content: space-between;
            gap: var(--spacing-2);
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

                    <nav class="main-nav">
                        <ul class="nav-list">
                            <li><a href="prestataires.php" class="nav-link">Préstataires</a></li>
                            <li><a href="actualites.php" class="nav-link">Actualités</a></li>
                            <li>
                                <a href="panier-prestataires.php" class="nav-link" >panier</a>
                            </li>
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
            <!-- Page Header -->
                    <div class="page-header">
                        <div class="container">
                            <h1 class="fancy-title">Nos prestataires</h1>
                            <p>Une large selection des préstataires de qualité à votre disposition</p>
                        </div>
                    </div>
            <div class="container">
                <div class="section-header">
                        <h1 class="section-title decorative-line">Nos prestataires</h1>
                        <p class="section-description">Découvrez notre sélection de prestataires de qualité pour votre mariage. Filtrez par catégorie, région ou utilisez la recherche pour trouver le prestataire idéal.</p>
                    </div>
                <section class="section">
                    <!-- Filtres dynamiques -->
                    <form method="get" class="filters-container" style="margin-bottom:2rem;">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text" class="filter-input" name="q" placeholder="Rechercher un prestataire..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
                        </div>
                        <div class="select-container">
                            <select class="filter-input" name="categorie">
                                <option value="">Toutes les catégories</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php if(($_GET['categorie'] ?? '') === $cat) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                                <option value="nom" <?php if(($_GET['sort'] ?? '') === 'nom') echo 'selected'; ?>>Nom</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-rounded">Filtrer</button>
                    </form>
                    
                    <!-- Liste des prestataires -->
                    <div class="providers-grid" id="providers-container">
                        <?php
                        // Filtrage côté PHP
                        $q = strtolower($_GET['q'] ?? '');
                        $cat = $_GET['categorie'] ?? '';
                        $reg = $_GET['region'] ?? '';
                        $sort = $_GET['sort'] ?? '';

                        $filtered = array_filter($prestataires, function($p) use ($q, $cat, $reg) {
                            $match = true;
                            if ($q) {
                                $match = stripos($p['nom_entreprise'], $q) !== false
                                    || stripos($p['description'], $q) !== false
                                    || stripos($p['categorie'], $q) !== false;
                            }
                            if ($match && $cat) {
                                $match = $p['categorie'] === $cat;
                            }
                            if ($match && $reg) {
                                $match = $p['region'] === $reg;
                            }
                            return $match;
                        });

                        if ($sort === 'nom') {
                            usort($filtered, fn($a, $b) => strcmp($a['nom_entreprise'], $b['nom_entreprise']));
                        }

                        if (empty($filtered)): ?>
                            <div class="no-results">Aucun prestataire ne correspond à vos critères de recherche.</div>
                        <?php else:
                            foreach($filtered as $provider):
                                $img = $provider['image_profil'] ?: 'images/placeholder.jpg';
                        ?>
                        <div class="provider-card">
                            <div class="provider-image">
                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($provider['nom_entreprise']); ?>">
                            </div>
                            <div class="provider-content">
                                <div class="provider-header">
                                    <h3 class="provider-title"><?php echo htmlspecialchars($provider['nom_entreprise']); ?></h3>
                                </div>
                                <div class="provider-tags">
                                    <span class="provider-tag"><?php echo htmlspecialchars($provider['categorie']); ?></span>
                                    <span class="provider-tag secondary"><?php echo htmlspecialchars($provider['region']); ?></span>
                                </div>
                                <p class="provider-description"><?php echo htmlspecialchars($provider['description']); ?></p>
                                <div class="provider-actions">
                                    <a href="prestataire-detail.php?id=<?php echo $provider['id_prestataire']; ?>" class="btn btn-outline btn-rounded">Voir le profil</a><a href="#" class="btn btn-primary btn-rounded add-to-cart-btn" data-id="<?php echo $provider['id_prestataire']; ?>" data-name="<?php echo htmlspecialchars($provider['nom_entreprise']); ?>">Ajouter</a>
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
                            <li><a href="prestataires.php">Prestataires</a></li>
                            <li><a href="categories.php">Catégories</a></li>
                            <li><a href="actualites.php">Actualités</a></li>
                            <li><a href="offres.php">Offres spéciales</a></li>
                            <li><a href="a-propos.php">À propos</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-column">
                        <h3>Informations</h3>
                        <ul class="footer-links">
                            <li><a href="contact.php">Contact</a></li>
                            <li><a href="devenir-prestataire.php">Devenir prestataire</a></li>
                            <li><a href="faq.php">FAQ</a></li>
                            <li><a href="mentions-legales.php">Mentions légales</a></li>
                            <li><a href="confidentialite.php">Politique de confidentialité</a></li>
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
    
    <!-- Script spécifique à la page prestataires -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    // ...ton code existant...

    // Gestion du bouton "Ajouter"
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = parseInt(this.getAttribute('data-id'));
            const name = this.getAttribute('data-name');

            // Récupère le panier actuel
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            // Cherche si le prestataire est déjà dans le panier
            let found = cart.find(item => item.id === id);
            if (found) {
                found.quantity += 1;
            } else {
                cart.push({id: id, name: name, quantity: 1});
            }
            // Sauvegarde le panier
            localStorage.setItem('cart', JSON.stringify(cart));
            // Redirige vers la page panier
            window.location.href = 'panier-prestataires.php';
        });
    });
});
        document.addEventListener('DOMContentLoaded', function() {
            // Données fictives des prestataires
            const providers = [
                {
                    id: 1,
                    name: "Château des Roses",
                    category: "Lieux de réception",
                    region: "Île-de-France",
                    rating: 4.8,
                    reviewCount: 124,
                    description: "Magnifique château du XVIIIe siècle entouré d'un parc de 5 hectares. Capacité jusqu'à 200 personnes.",
                    price: "€€€",
                    image: "images/placeholder.jpg"
                },
                {
                    id: 2,
                    name: "Studio Lumière",
                    category: "Photographes / Vidéastes",
                    region: "Provence-Alpes-Côte d'Azur",
                    rating: 4.9,
                    reviewCount: 87,
                    description: "Équipe de photographes et vidéastes passionnés, spécialisés dans le reportage de mariage naturel et authentique.",
                    price: "€€",
                    image: "images/placeholder.jpg"
                },
                {
                    id: 3,
                    name: "Traiteur Délices",
                    category: "Traiteurs",
                    region: "Grand Est",
                    rating: 4.7,
                    reviewCount: 156,
                    description: "Cuisine raffinée mêlant tradition française et influences internationales. Produits locaux et de saison.",
                    price: "€€",
                    image: "images/placeholder.jpg"
                },
                {
                    id: 4,
                    name: "DJ Ambiance",
                    category: "DJ / Musiciens",
                    region: "Nouvelle-Aquitaine",
                    rating: 4.6,
                    reviewCount: 92,
                    description: "DJ expérimenté spécialisé dans les mariages. Large répertoire musical et équipement son et lumière haut de gamme.",
                    price: "€",
                    image: "images/placeholder.jpg"
                },
                {
                    id: 5,
                    name: "Fleurs d'Élégance",
                    category: "Fleuristes",
                    region: "Bretagne",
                    rating: 4.9,
                    reviewCount: 68,
                    description: "Créations florales sur mesure pour votre mariage. Bouquets, centres de table, arches et décorations florales.",
                    price: "€€",
                    image: "images/placeholder.jpg"
                },
                {
                    id: 6,
                    name: "Robes de Rêve",
                    category: "Robes & Costumes",
                    region: "Auvergne-Rhône-Alpes",
                    rating: 4.8,
                    reviewCount: 103,
                    description: "Boutique proposant une sélection de robes de mariée et costumes de grandes marques et de créateurs.",
                    price: "€€€",
                    image: "images/placeholder.jpg"
                }
            ];
            
            const providersContainer = document.getElementById('providers-container');
            const searchInput = document.getElementById('search-provider');
            const categoryFilter = document.getElementById('category-filter');
            const regionFilter = document.getElementById('region-filter');
            const sortFilter = document.getElementById('sort-filter');
            
            // Fonction pour générer les cartes de prestataires
            function renderProviders(providersToRender) {
                providersContainer.innerHTML = '';
                
                if (providersToRender.length === 0) {
                    providersContainer.innerHTML = '<div class="no-results">Aucun prestataire ne correspond à vos critères de recherche.</div>';
                    return;
                }
                
                providersToRender.forEach(provider => {
                    const providerCard = document.createElement('div');
                    providerCard.className = 'provider-card animate-on-scroll';
                    
                    // Générer les étoiles pour la notation
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        if (i <= Math.floor(provider.rating)) {
                            starsHtml += '<i class="fas fa-star"></i>';
                        } else if (i - 0.5 <= provider.rating) {
                            starsHtml += '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            starsHtml += '<i class="far fa-star"></i>';
                        }
                    }
                    
                    providerCard.innerHTML = `
                        <div class="provider-image">
                            <img src="${provider.image}" alt="${provider.name}">
                        </div>
                        <div class="provider-content">
                            <div class="provider-header">
                                <h3 class="provider-title">${provider.name}</h3>
                                <div class="provider-price">${provider.price}</div>
                            </div>
                            <div class="provider-rating">
                                ${starsHtml}
                                <span>${provider.rating}</span>
                                <span class="provider-reviews">(${provider.reviewCount} avis)</span>
                            </div>
                            <div class="provider-tags">
                                <span class="provider-tag">${provider.category}</span>
                                <span class="provider-tag secondary">${provider.region}</span>
                            </div>
                            <p class="provider-description">${provider.description}</p>
                            <div class="provider-actions">
                                <a href="prestataires/${provider.id}.php" class="btn btn-outline btn-rounded">Voir le profil</a>
                                <a href="contact.php?prestataire=${provider.id}" class="btn btn-primary btn-rounded">Contacter</a>
                            </div>
                        </div>
                    `;
                    
                    providersContainer.appendChild(providerCard);
                });
            }
            
            // Fonction pour filtrer les prestataires
            function filterProviders() {
                const searchTerm = searchInput.value.toLowerCase();
                const categoryValue = categoryFilter.value;
                const regionValue = regionFilter.value;
                const sortValue = sortFilter.value;
                
                let filteredProviders = providers.filter(provider => {
                    // Filtre de recherche
                    const matchesSearch = provider.name.toLowerCase().includes(searchTerm) || 
                                         provider.description.toLowerCase().includes(searchTerm) ||
                                         provider.category.toLowerCase().includes(searchTerm);
                    
                    // Filtre de catégorie
                    const matchesCategory = categoryValue === 'all' || 
                                           (categoryValue === 'lieu' && provider.category === 'Lieux de réception') ||
                                           (categoryValue === 'traiteur' && provider.category === 'Traiteurs') ||
                                           (categoryValue === 'photo' && provider.category === 'Photographes / Vidéastes') ||
                                           (categoryValue === 'musique' && provider.category === 'DJ / Musiciens') ||
                                           (categoryValue === 'tenue' && provider.category === 'Robes & Costumes') ||
                                           (categoryValue === 'fleurs' && provider.category === 'Fleuristes');
                    
                    // Filtre de région
                    const matchesRegion = regionValue === 'all' || 
                                         (regionValue === 'idf' && provider.region === 'Île-de-France') ||
                                         (regionValue === 'paca' && provider.region === 'Provence-Alpes-Côte d\'Azur\') ||
                                         (regionValue === 'grand-est' && provider.region === 'Grand Est') ||
                                         (regionValue === 'nouvelle-aquitaine' && provider.region === 'Nouvelle-Aquitaine') ||
                                         (regionValue === 'bretagne' && provider.region === 'Bretagne') ||
                                         (regionValue === 'auvergne' && provider.region === 'Auvergne-Rhône-Alpes');
                    
                    return matchesSearch && matchesCategory && matchesRegion;
                });
                
                // Tri des prestataires
                switch (sortValue) {
                    case 'note':
                        filteredProviders.sort((a, b) => b.rating - a.rating);
                        break;
                    case 'prix-croissant':
                        filteredProviders.sort((a, b) => a.price.length - b.price.length);
                        break;
                    case 'prix-decroissant':
                        filteredProviders.sort((a, b) => b.price.length - a.price.length);
                        break;
                    default:
                        // Par défaut, tri par recommandation (id)
                        filteredProviders.sort((a, b) => a.id - b.id);
                }
                
                renderProviders(filteredProviders);
            }
            
            // Ajouter les écouteurs d'événements pour les filtres
            searchInput.addEventListener('input', filterProviders);
            categoryFilter.addEventListener('change', filterProviders);
            regionFilter.addEventListener('change', filterProviders);
            sortFilter.addEventListener('change', filterProviders);
            
            // Pagination
            const paginationItems = document.querySelectorAll('.pagination-item');
            paginationItems.forEach(item => {
                item.addEventListener('click', function() {
                    paginationItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Simuler un changement de page
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            });
            
            // Initialiser l'affichage des prestataires
            renderProviders(providers);
        });
    </script>
</body>
</html>