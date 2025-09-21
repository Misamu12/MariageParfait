<?php
require_once 'config.php';

// Récupérer l'ID du prestataire depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les infos du prestataire
$stmt = $pdo->prepare("SELECT * FROM prestataire WHERE id_prestataire = ?");
$stmt->execute([$id]);
$prestataire = $stmt->fetch();

if (!$prestataire) {
    echo "<h2>Prestataire introuvable.</h2>";
    exit;
}

// Récupérer les avis clients
$stmtAvis = $pdo->prepare("SELECT c.*, u.nom, u.prenom FROM commentaire c LEFT JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur WHERE c.id_prestataire = ? ORDER BY c.date_commentaire DESC LIMIT 3");
$stmtAvis->execute([$id]);
$avis = $stmtAvis->fetchAll();

// Récupérer quelques prestataires similaires (même catégorie, exclure le courant)
$stmtSimilaires = $pdo->prepare("SELECT * FROM prestataire WHERE categorie = ? AND id_prestataire != ? LIMIT 3");
$stmtSimilaires->execute([$prestataire['categorie'], $id]);
$similaires = $stmtSimilaires->fetchAll();

// Récupérer la galerie photos du prestataire
$stmtGalerie = $pdo->prepare("SELECT * FROM galerie WHERE id_prestataire = ? ORDER BY date_ajout DESC");
$stmtGalerie->execute([$id]);
$galerie = $stmtGalerie->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($prestataire['nom_entreprise']); ?> - Mariage Parfait</title>
    <meta name="description" content="<?php echo htmlspecialchars(substr($prestataire['description'],0,150)); ?>">
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Styles spécifiques à la page détail prestataire -->
    <style>
        .provider-detail-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .provider-hero {
            position: relative;
            height: 400px;
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: var(--spacing-8);
        }
        
        .provider-hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .provider-hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: var(--spacing-6);
            color: white;
        }
        
        .provider-hero-content {
            display: flex;
            align-items: flex-end;
            gap: var(--spacing-4);
        }
        
        .provider-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid white;
            box-shadow: var(--shadow-lg);
        }
        
        .provider-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .provider-hero-info h1 {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-2);
        }
        
        .provider-hero-info p {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-2);
        }
        
        .provider-rating {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }
        
        .provider-rating i {
            color: #f59e0b;
        }
        
        .provider-actions {
            position: absolute;
            bottom: var(--spacing-6);
            right: var(--spacing-6);
            display: flex;
            gap: var(--spacing-2);
        }
        
        .provider-content {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-8);
        }
        
        @media (min-width: 992px) {
            .provider-content {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        .provider-main {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-8);
        }
        
        .provider-section {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-6);
            border: 1px solid var(--color-border);
        }
        
        .provider-section h2 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-4);
            padding-bottom: var(--spacing-2);
            border-bottom: 1px solid var(--color-border);
        }
        
        .provider-description p {
            margin-bottom: var(--spacing-4);
            line-height: 1.8;
        }
        
        .provider-features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-4);
            margin-top: var(--spacing-4);
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-2);
        }
        
        .feature-item i {
            color: var(--color-primary);
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: var(--spacing-2);
        }
        
        .gallery-item {
            height: 150px;
            border-radius: var(--radius);
            overflow: hidden;
            cursor: pointer;
        }
        
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.1);
        }
        
        .review-item {
            padding: var(--spacing-4) 0;
            border-bottom: 1px solid var(--color-border);
        }
        
        .review-item:last-child {
            border-bottom: none;
        }
        
        .review-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: var(--spacing-2);
        }
        
        .review-author {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }
        
        .review-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }
        
        .review-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .review-author-info h4 {
            font-size: 1rem;
            margin-bottom: var(--spacing-1);
        }
        
        .review-date {
            color: var(--color-muted-foreground);
            font-size: 0.875rem;
        }
        
        .review-rating {
            color: #f59e0b;
        }
        
        .review-content {
            margin-bottom: var(--spacing-2);
        }
        
        .review-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: var(--color-muted-foreground);
        }
        
        .review-helpful {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }
        
        .review-helpful button {
            display: flex;
            align-items: center;
            gap: var(--spacing-1);
            color: var(--color-muted-foreground);
            transition: var(--transition-base);
        }
        
        .review-helpful button:hover {
            color: var(--color-primary);
        }
        
        .provider-sidebar {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-6);
        }
        
        .provider-card {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-6);
            border: 1px solid var(--color-border);
        }
        
        .provider-card h3 {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-4);
            padding-bottom: var(--spacing-2);
            border-bottom: 1px solid var(--color-border);
        }
        
        .provider-detail {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-4);
        }
        
        .provider-detail:last-child {
            margin-bottom: 0;
        }
        
        .provider-detail i {
            color: var(--color-primary);
            font-size: 1.25rem;
            margin-top: var(--spacing-1);
        }
        
        .provider-detail-content p {
            margin-bottom: var(--spacing-1);
        }
        
        .provider-detail-content p:first-child {
            font-weight: 600;
        }
        
        .availability-calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-top: var(--spacing-4);
        }
        
        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            margin-bottom: var(--spacing-2);
        }
        
        .calendar-header span {
            text-align: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            border-radius: var(--radius);
            cursor: pointer;
        }
        
        .calendar-day.available {
            background-color: var(--color-green-light);
            color: var(--color-green);
        }
        
        .calendar-day.unavailable {
            background-color: var(--color-red-light);
            color: var(--color-red);
            text-decoration: line-through;
        }
        
        .calendar-day.other-month {
            background-color: var(--color-muted);
            color: var(--color-muted-foreground);
        }
        
        .similar-providers {
            margin-top: var(--spacing-16);
        }
        
        .similar-providers h2 {
            font-size: 1.75rem;
            margin-bottom: var(--spacing-6);
            text-align: center;
        }
        
        .similar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: var(--spacing-6);
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            z-index: 1000;
            overflow: hidden;
        }
        
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }
        
        .modal-content img {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
        }
        
        .modal-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 2rem;
            cursor: pointer;
        }
        
        .modal-prev,
        .modal-next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: white;
            font-size: 2rem;
            cursor: pointer;
            padding: var(--spacing-2);
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-prev {
            left: 20px;
        }
        
        .modal-next {
            right: 20px;
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
                <section class="provider-detail-container">
                    <!-- Hero section -->
                    <div class="provider-hero">
                        <img src="<?php echo htmlspecialchars($prestataire['image_profil'] ?: 'images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($prestataire['nom_entreprise']); ?>" class="provider-hero-image">
                        <div class="provider-hero-overlay">
                            <div class="provider-hero-content">
                                <div class="provider-avatar">
                                    <img src="<?php echo htmlspecialchars($prestataire['image_profil'] ?: 'images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($prestataire['nom_entreprise']); ?>">
                                </div>
                                <div class="provider-hero-info">
                                    <h1><?php echo htmlspecialchars($prestataire['nom_entreprise']); ?></h1>
                                    <p><?php echo htmlspecialchars($prestataire['categorie']); ?></p>
                                    <!-- Note moyenne et nombre d'avis -->
                                    <?php
                                    $stmtNote = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as nb FROM commentaire WHERE id_prestataire = ?");
                                    $stmtNote->execute([$id]);
                                    $noteData = $stmtNote->fetch();
                                    $moyenne = $noteData['moyenne'] ? round($noteData['moyenne'], 1) : '—';
                                    $nbAvis = $noteData['nb'];
                                    ?>
                                    <div class="provider-rating">
                                        <?php
                                        $fullStars = floor($moyenne);
                                        $halfStar = ($moyenne - $fullStars) >= 0.5;
                                        for ($i=0; $i<5; $i++) {
                                            if ($i < $fullStars) echo '<i class="fas fa-star"></i>';
                                            elseif ($i == $fullStars && $halfStar) echo '<i class="fas fa-star-half-alt"></i>';
                                            else echo '<i class="far fa-star"></i>';
                                        }
                                        ?>
                                        <span><?php echo $moyenne; ?> (<?php echo $nbAvis; ?> avis)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="provider-actions">
                                <button class="btn btn-outline btn-rounded" id="favorite-btn">
                                    <i class="far fa-heart"></i> Favoris
                                </button>
                                <a href="contact_prestataire.php?id=<?php echo $prestataire['id_prestataire']; ?>" class="btn btn-primary btn-rounded">
                                    <i class="fas fa-envelope"></i> Contacter
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenu principal -->
                    <div class="provider-content">
                        <div class="provider-main">
                            <!-- Description -->
                            <div class="provider-section">
                                <h2>À propos</h2>
                                <div class="provider-description">
                                    <p><?php echo nl2br(htmlspecialchars($prestataire['description'])); ?></p>
                                </div>
                                <!-- Ici tu peux ajouter des prestations/services si tu les lies à la table service -->
                            </div>
                            
                            <!-- Galerie photos (exemple simple, à adapter si tu ajoutes une table galerie) -->
                            <div class="provider-section">
                                <h2>Galerie photos</h2>
                                <div class="gallery-grid">
                                    <?php if (empty($galerie)): ?>
                                        <div>Aucune photo pour ce prestataire.</div>
                                    <?php else: foreach ($galerie as $index => $photo): ?>
                                        <div class="gallery-item" data-index="<?php echo $index; ?>">
                                            <img src="<?php echo htmlspecialchars($photo['image']); ?>" alt="<?php echo htmlspecialchars($photo['legende'] ?: $prestataire['nom_entreprise']); ?>">
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                            </div>
                            
                            <!-- Avis -->
                            <div class="provider-section">
                                <h2>Avis clients</h2>
                                <div class="review-list">
                                    <?php if (empty($avis)): ?>
                                        <div class="review-item">Aucun avis pour ce prestataire.</div>
                                    <?php else: foreach ($avis as $a): ?>
                                        <div class="review-item">
                                            <div class="review-header">
                                                <div class="review-author">
                                                    <div class="review-avatar">
                                                        <img src="images/placeholder.jpg" alt="<?php echo htmlspecialchars($a['nom'].' '.$a['prenom']); ?>">
                                                    </div>
                                                    <div class="review-author-info">
                                                        <h4><?php echo htmlspecialchars($a['nom'].' '.$a['prenom']); ?></h4>
                                                        <span class="review-date"><?php echo htmlspecialchars($a['date_commentaire']); ?></span>
                                                    </div>
                                                </div>
                                                <div class="review-rating">
                                                    <?php
                                                    for ($i=0; $i<5; $i++) {
                                                        if ($i < $a['note']) echo '<i class="fas fa-star"></i>';
                                                        else echo '<i class="far fa-star"></i>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="review-content">
                                                <p><?php echo nl2br(htmlspecialchars($a['contenu'])); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; endif; ?>
                                </div>
                                <div style="text-align: center; margin-top: var(--spacing-6);">
                                    <a href="#" class="btn btn-outline btn-rounded">Voir tous les avis (<?php echo $nbAvis; ?>)</a>
                                </div>
                            </div>
                        </div>
                        
                        
                            <!-- Disponibilités (statique ou à relier à une table de réservations si tu veux) -->
                            
                           
                    
                    <!-- Prestataires similaires -->

                        
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Modal pour la galerie -->
        <div class="modal" id="gallery-modal">
            <span class="modal-close" id="modal-close">&times;</span>
            <div class="modal-content">
                <img id="modal-image" src="/placeholder.svg" alt="Image en plein écran">
            </div>
            <div class="modal-prev" id="modal-prev">
                <i class="fas fa-chevron-left"></i>
            </div>
            <div class="modal-next" id="modal-next">
                <i class="fas fa-chevron-right"></i>
            </div>
        </div>

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
    
    <!-- Script spécifique à la page détail prestataire -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer l'ID du prestataire depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const providerId = urlParams.get('id') || 1;
            
            // Gestion des favoris
            const favoriteBtn = document.getElementById('favorite-btn');
            
            favoriteBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                
                if (icon.classList.contains('far')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#d6246e';
                    this.innerHTML = '<i class="fas fa-heart"></i> Ajouté aux favoris';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                    this.innerHTML = '<i class="far fa-heart"></i> Favoris';
                }
            });
            
            // Gestion du signalement
            const reportBtn = document.getElementById('report-btn');
            
            reportBtn.addEventListener('click', function() {
                if (confirm('Souhaitez-vous signaler ce prestataire ? Un modérateur examinera votre signalement.')) {
                    alert('Merci pour votre signalement. Notre équipe va l\'examiner dans les plus brefs délais.');
                }
            });
            
            // Gestion de la galerie
            const galleryItems = document.querySelectorAll('.gallery-item');
            const modal = document.getElementById('gallery-modal');
            const modalImage = document.getElementById('modal-image');
            const modalClose = document.getElementById('modal-close');
            const modalPrev = document.getElementById('modal-prev');
            const modalNext = document.getElementById('modal-next');
            
            let currentImageIndex = 0;
            
            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    currentImageIndex = parseInt(this.getAttribute('data-index'));
                    const imgSrc = this.querySelector('img').src;
                    const imgAlt = this.querySelector('img').alt;
                    
                    modalImage.src = imgSrc;
                    modalImage.alt = imgAlt;
                    modal.style.display = 'block';
                    document.body.style.overflow = 'hidden';
                });
            });
            
            modalClose.addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
            
            modalPrev.addEventListener('click', function() {
                currentImageIndex = (currentImageIndex - 1 + galleryItems.length) % galleryItems.length;
                const imgSrc = galleryItems[currentImageIndex].querySelector('img').src;
                const imgAlt = galleryItems[currentImageIndex].querySelector('img').alt;
                
                modalImage.src = imgSrc;
                modalImage.alt = imgAlt;
            });
            
            modalNext.addEventListener('click', function() {
                currentImageIndex = (currentImageIndex + 1) % galleryItems.length;
                const imgSrc = galleryItems[currentImageIndex].querySelector('img').src;
                const imgAlt = galleryItems[currentImageIndex].querySelector('img').alt;
                
                modalImage.src = imgSrc;
                modalImage.alt = imgAlt;
            });
            
            // Fermer la modal en cliquant en dehors de l'image
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
            
            // Gestion du calendrier de disponibilités
            const calendarContainer = document.getElementById('availability-calendar');
            
            function generateCalendar() {
                calendarContainer.innerHTML = '';
                
                // Simuler des dates disponibles et indisponibles
                const daysInMonth = 31;
                const unavailableDays = [5, 6, 12, 13, 19, 20, 26, 27]; // Week-ends
                const bookedDays = [7, 14, 21, 28]; // Jours réservés
                
                // Jours du mois précédent
                for (let i = 1; i <= 3; i++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day other-month';
                    dayElement.textContent = 28 + i;
                    calendarContainer.appendChild(dayElement);
                }
                
                // Jours du mois actuel
                for (let i = 1; i <= daysInMonth; i++) {
                    const dayElement = document.createElement('div');
                    
                    if (unavailableDays.includes(i) || bookedDays.includes(i)) {
                        dayElement.className = 'calendar-day unavailable';
                    } else {
                        dayElement.className = 'calendar-day available';
                    }
                    
                    dayElement.textContent = i;
                    calendarContainer.appendChild(dayElement);
                    
                    // Ajouter un tooltip pour les jours disponibles
                    if (dayElement.classList.contains('available')) {
                        dayElement.title = 'Disponible - Cliquez pour vérifier';
                        dayElement.addEventListener('click', function() {
                            window.location.href = `contact-prestataire.php?id=${providerId}&date=2024-06-${i}`;
                        });
                    } else if (bookedDays.includes(i)) {
                        dayElement.title = 'Déjà réservé';
                    } else {
                        dayElement.title = 'Non disponible';
                    }
                }
                
                // Jours du mois suivant
                for (let i = 1; i <= 4; i++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day other-month';
                    dayElement.textContent = i;
                    calendarContainer.appendChild(dayElement);
                }
            }
            
            generateCalendar();
        });
    </script>
</body>
</html>