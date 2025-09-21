<?php
require_once 'config.php';
// Récupérer les catégories
$stmt = $pdo->query("SELECT * FROM categorie LIMIT 6");
$categories = $stmt->fetchAll();

// Récupérer les actualités
$stmt = $pdo->query("SELECT * FROM actualite ORDER BY date_publication DESC LIMIT 3");
$actualites = $stmt->fetchAll();

// Récupérer les offres spéciales
$stmt = $pdo->query("SELECT * FROM offre_speciale ORDER BY date_debut DESC LIMIT 3");
$offres = $stmt->fetchAll();

// Récupérer les publicités actives (date)
$today = date('Y-m-d');
$stmt = $pdo->prepare("SELECT * FROM publicite WHERE date_debut <= ? AND date_fin >= ? ORDER BY date_creation DESC");
$stmt->execute([$today, $today]);
$publicites = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mariage Parfait - Trouvez les meilleurs prestataires pour votre mariage</title>
    <meta name="description" content="Plateforme de location de prestataires de mariage - Trouvez les prestataires parfaits pour le plus beau jour de votre vie">
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Feuille de style principale -->
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
                                <a href="panier-prestataires.php" class="nav-link" >panier</a>
                            </li>
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
                        <li>
                            <a href="panier-prestataires.php" class="nav-link" >panier</a>
                        </li>
                        <li><a href="contact.php" class="mobile-nav-link">Contact</a></li>
                        <li>
                            <a href="connexion.php" class="btn btn-primary btn-rounded btn-block">Connexion</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <main>
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-bg"></div>
                <div class="container">
                    <div class="hero-content">
                        <h1 class="fancy-title fade-in">Mariage Parfait</h1>
                        <h2 class="slide-up">Trouvez les prestataires parfaits pour le plus beau jour de votre vie</h2>
                        <p class="slide-up delay-1">Des professionnels sélectionnés avec soin pour rendre votre mariage inoubliable. Lieux de réception, traiteurs, photographes, DJ et bien plus encore.</p>
                        <div class="hero-buttons slide-up delay-2">
                            <a href="prestataires.php" class="btn btn-primary btn-lg btn-rounded">Découvrez nos prestataires</a>
                            <a href="contact.php" class="btn btn-outline btn-lg btn-rounded">Nous contacter</a>
                        </div>
                        <div class="scroll-indicator bounce">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </section>

            <div class="contenaire">
            <!-- Espace Publicitaire -->
            <section class="section">
                <div class="ad-space" style="text-align:center;">
                    <?php if (!empty($publicites)): ?>
                        <div id="pub-carousel" style="max-width:480px;margin:0 auto;">
                            <?php foreach ($publicites as $i => $pub): ?>
                                <div class="pub-video-block" style="<?= $i === 0 ? '' : 'display:none;' ?>">
                                    <h3><?= htmlspecialchars($pub['titre']) ?></h3>
                                    <video
                                        src="<?= htmlspecialchars($pub['video_url']) ?>"
                                        width="100%"
                                        style="border-radius:10px;box-shadow:0 2px 8px #0002;"
                                        <?= $i === 0 ? 'autoplay muted playsinline' : '' ?>
                                        controls
                                    ></video>
                                    <?php if ($pub['nom_client']): ?>
                                        <div style="font-size:0.95em;color:#888;">Publicité : <?= htmlspecialchars($pub['nom_client']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <h3>Votre publicité ici</h3>
                        <p>Contactez-nous pour promouvoir vos services auprès de nos futurs mariés</p>
                        <a href="contact.php" class="btn btn-outline btn-rounded">En savoir plus</a>
                    <?php endif; ?>
                </div>
            </section>

            <div class="container">
                <!-- Categories Section - Organisée en 2 lignes -->
                <section id="categories" class="categories-section section">
                    <div class="section-header">
                        <h2 class="section-title decorative-line">Nos catégories de prestataires</h2>
                        <p class="section-description">Découvrez tous les professionnels qui rendront votre mariage unique et mémorable.</p>
                    </div>
                    
                    <div class="categories-grid">
                        <a href="" class="category-card hover-lift card-fancy bg-pink-light">
                            <div class="category-icon bg-white text-pink">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3>Lieux de réception</h3>
                            <p>Salles, châteaux, domaines...</p>
                        </a>
                        
                        <a href="" class="category-card hover-lift card-fancy bg-cyan-light">
                            <div class="category-icon bg-white text-cyan">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h3>Photographes</h3>
                            <p>Photos, vidéos, drone...</p>
                        </a>
                        
                        <a href="" class="category-card hover-lift card-fancy bg-purple-light">
                            <div class="category-icon bg-white text-purple">
                                <i class="fas fa-music"></i>
                            </div>
                            <h3>DJ & Musiciens</h3>
                            <p>Animation, orchestres, DJ...</p>
                        </a>
                        
                        <a href="" class="category-card hover-lift card-fancy bg-blue-light">
                            <div class="category-icon bg-white text-blue">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h3>Traiteurs</h3>
                            <p>Repas, cocktails, desserts...</p>
                        </a>
                        
                        <a href="" class="category-card hover-lift card-fancy bg-rose-light">
                            <div class="category-icon bg-white text-rose">
                                <i class="fas fa-car"></i>
                            </div>
                            <h3>Transport</h3>
                            <p>Voitures, limousines...</p>
                        </a>
                        
                        <a href="" class="category-card hover-lift card-fancy bg-green-light">
                            <div class="category-icon bg-white text-green">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3>Fleuristes & Décoration</h3>
                            <p>Bouquets, décorations...</p>
                        </a>

                        <a href="" class="category-card hover-lift card-fancy bg-purple-light">
                            <div class="category-icon bg-white text-purple">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <h3>Wedding Planner & Décoration</h3>
                            <p>Organisation, décoration de salle...</p>
                        </a>

                        <a href="" class="category-card hover-lift card-fancy bg-blue-light">
                            <div class="category-icon bg-white text-blue">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <h3>Robes & Costumes</h3>
                            <p>Tenues de mariés et demoiselles d'honneur</p>
                        </a>


                    </div>
                    
                    <div class="section-footer">
                        <a href="./prestataires.php" class="btn btn-outline btn-rounded">Voir toutes nos préstataires</a>
                    </div>
                </section>

                <!-- News Section -->
                <section class="news-section section bg-gradient" id="actualites">
                    <div class="section-header">
                        <h2 class="section-title decorative-line">Actualités des prestataires</h2>
                        <p class="section-description">Restez informés des dernières nouvelles et événements de nos prestataires partenaires.</p>
                    </div>
                    
                    <div class="news-grid">
                        <?php foreach($actualites as $news): ?>
                        <article class="news-card hover-lift">
                            <div class="news-image">
                                <img src="images/<?= htmlspecialchars($news['image'] ?? 'images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($news['titre']) ?>">
                            </div>
                            <div class="news-content">
                                <h3><?= htmlspecialchars($news['titre']) ?></h3>
                                <p class="news-date"><?= date('d M Y', strtotime($news['date_publication'])) ?></p>
                                <p class="news-excerpt"><?= htmlspecialchars(mb_strimwidth($news['contenu'], 0, 120, '...')) ?></p>
                                <a href="actualite-detail.php?id=<?= htmlspecialchars($news['id_actualite']) ?>" class="btn btn-outline btn-rounded">Lire la suite</a>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="section-footer">
                        <a href="actualites.php" class="btn btn-outline btn-rounded">Voir toutes les actualités</a>
                    </div>
                </section>

                <!-- Special Offers Section -->
                <section class="offers-section section" id="offres">
                    <div class="section-header">
                        <h2 class="section-title decorative-line">Offres spéciales</h2>
                        <p class="section-description">Profitez de ces promotions exclusives proposées par nos prestataires partenaires.</p>
                    </div>
                    
                    <div class="offers-grid">
                        <?php foreach($offres as $offre): ?>
                        <div class="offer-card hover-lift">
                            <div class="offer-image">
                                <img src="<?= htmlspecialchars($offre['image'] ?? 'images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($offre['titre']) ?>">
                                <!-- Badge à adapter selon la promo -->
                                <span class="offer-badge bg-pink">Promo</span>
                            </div>
                            <div class="offer-content">
                                <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                                <p class="offer-description"><?= htmlspecialchars(mb_strimwidth($offre['description'], 0, 100, '...')) ?></p>
                                <p class="offer-validity">Valable jusqu'au <?= date('d M Y', strtotime($offre['date_fin'])) ?></p>
                                <a href="offres-detail.php?id=<?= htmlspecialchars($offre['id_offre']) ?>" class="btn btn-primary btn-rounded btn-block">En profiter</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="section-footer">
                        <a href="offres.php" class="btn btn-outline btn-rounded">Voir toutes les offres</a>
                    </div>
                </section>

                <!-- About Section -->
                <section class="about-section section bg-gradient" id="propos">
                    <div class="section-header">
                        <h2 class="section-title decorative-line">À propos</h2>
                    </div>
                    
                    <div class="about-content">
                        
                        <div class="about-text">
                            <h4 class="fancy-title">Boligoré</h4>
                            
                            <div class="about-description">
                                <p>Votre mariage sur mesure, partout en RDC.</p>
                                <p>Nous mettons en relation les meilleurs prestataires et les futurs mariés pour organiser le mariage de vos rêves. Notre plateforme regroupe des professionnels sélectionnés dans tout le Congo, afin de vous offrir un large choix de services fiables et de qualité.</p>
                                <p>Obtenez votre devis personnalisé en moins de 24h et bénéficiez de notre accompagnement pour que chaque détail soit exactement conforme à vos souhaits. Avec nous, votre grand jour est entre de bonnes mains.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Testimonials Section -->
                <section class="testimonials-section section" id="avis">
                    <div class="section-header">
                        <h2 class="section-title decorative-line">Avis clients</h2>
                        <p class="section-description">Découvrez ce que nos clients pensent de nos prestataires partenaires.</p>
                    </div>
                    
                    <div class="testimonials-grid">
                        <div class="testimonial-card hover-lift">
                            <div class="testimonial-content">
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="testimonial-text">Grâce à Mariage Parfait, nous avons trouvé tous nos prestataires en un temps record ! Le photographe et le traiteur que nous avons choisis ont été exceptionnels. Notre mariage était parfait, merci !</p>
                            </div>

                            <div class="testimonial-author">
                                <div class="testimonial-avatar">
                                    <img src="images/placeholder.jpg" alt="Sophie et Thomas">
                                </div>
                                <div class="testimonial-info">
                                    <h4>Sophie et Thomas</h4>
                                    <p>Mariés en juin 2024</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-card hover-lift">
                            <div class="testimonial-content">
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <p class="testimonial-text">Nous étions perdus dans l'organisation de notre mariage jusqu'à ce que nous découvrions cette plateforme. Le lieu de réception que nous avons trouvé était magique et correspondait exactement à nos attentes.</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">
                                    <img src="images/placeholder2.jpg" alt="Camille et Antoine">
                                </div>
                                <div class="testimonial-info">
                                    <h4>Camille et Antoine</h4>
                                    <p>Mariés en septembre 2024</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="testimonial-card hover-lift">
                            <div class="testimonial-content">
                                <div class="testimonial-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                                <p class="testimonial-text">Excellente plateforme qui nous a permis de comparer facilement différents prestataires. Le DJ que nous avons choisi a mis une ambiance incroyable ! Seul petit bémol : certaines régions ont moins de choix que d'autres.</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="testimonial-avatar">
                                    <img src="images/placeholder3.jpg" alt="Julie et Marc">
                                </div>
                                <div class="testimonial-info">
                                    <h4>Julie et Marc</h4>
                                    <p>Mariés en mai 2024</p>
                                </div>
                            </div>
                        </div>
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
                            <li><a href="#categories">Catégories</a></li>
                            <li><a href="#actualites">Actualités</a></li>
                            <li><a href="#offres">Offres spéciales</a></li>
                            <li><a href="#propos">À propos</a></li>
                            <li><a href="#avis">Avis clients</a></li>
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
                
                <div class="footer-bottom">
                    <p>&copy; 2025 Mariage Parfait. Tous droits réservés.</p>
                    <p>Fait avec <i class="fas fa-heart"></i> pour les futurs mariés</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Script principal -->
    <script src="js/script.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const blocks = document.querySelectorAll('#pub-carousel .pub-video-block');
    if (blocks.length <= 1) return;

    let current = 0;
    const showBlock = idx => {
        blocks.forEach((b, i) => b.style.display = (i === idx ? '' : 'none'));
        const video = blocks[idx].querySelector('video');
        if (video) {
            video.muted = true;
            video.play();
        }
    };

    // Force le play de la première vidéo au chargement
    const firstVideo = blocks[0].querySelector('video');
    if (firstVideo) {
        firstVideo.muted = true;
        firstVideo.play().catch(()=>{});
    }

    blocks.forEach((block, idx) => {
        const video = block.querySelector('video');
        if (video) {
            video.onended = function() {
                current = (current + 1) % blocks.length;
                showBlock(current);
            };
        }
    });
});
</script>
</body>
</html>