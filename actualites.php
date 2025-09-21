<?php
// filepath: c:\xampp\htdocs\mariage_v2\wedding-Presto-last\actualites.php
require_once 'config.php';

// Récupérer toutes les actualités (adapter les champs selon ta table)
$stmt = $pdo->query("SELECT * FROM actualite ORDER BY date_publication DESC");
$actualites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualités - Mariage Parfait</title>
    <meta name="description" content="Toutes les actualités et conseils pour organiser votre mariage parfait">
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
                <section class="news-section section">
                    <div class="section-header">
                        <h1 class="section-title decorative-line">Actualités & Conseils</h1>
                        <p class="section-description">Retrouvez ici toutes les actualités, conseils et inspirations pour organiser votre mariage parfait.</p>
                    </div>
                    <div class="news-grid">
                        <?php foreach($actualites as $news): ?>
                            <?php
                                $img = !empty($news['image']) ? $news['image'] : 'images/placeholder.jpg';
                                $date = !empty($news['date_publication']) ? date('d/m/Y', strtotime($news['date_publication'])) : '';
                            ?>
                            <div class="news-card hover-lift">
                                <div class="news-image">
                                    <img src="images/<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($news['titre'] ?? 'Actualité'); ?>">
                                </div>
                                <div class="news-content">
                                    <h3><?php echo htmlspecialchars($news['titre'] ?? ''); ?></h3>
                                    <p class="news-date"><i class="fas fa-calendar-alt"></i> <?php echo $date; ?></p>
                                    <p class="news-description"><?php echo htmlspecialchars(mb_strimwidth($news['contenu'] ?? '', 0, 120, '...')); ?></p>
                                    <a href="actualite-detail.php?id=<?php echo $news['id_actualite']; ?>" class="btn btn-outline btn-rounded">Lire la suite</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($actualites)): ?>
                            <div class="no-results">Aucune actualité n'est disponible pour le moment.</div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
        <!-- Footer (optionnel) -->
    </div>
    <script src="js/script.js"></script>
</body>
</html>