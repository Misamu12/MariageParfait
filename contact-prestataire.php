<?php
require_once 'config.php';

// Récupérer l'ID du prestataire depuis l'URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer les infos du prestataire
$stmt = $pdo->prepare("SELECT * FROM prestataire WHERE id_prestataire = ?");
$stmt->execute([$id]);
$prestataire = $stmt->fetch();

// Gestion du formulaire
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom    = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $tel    = trim($_POST['phone'] ?? '');
    $date   = trim($_POST['wedding-date'] ?? '');
    $guests = trim($_POST['guests'] ?? '');
    $msg    = trim($_POST['message'] ?? '');

    if ($nom && $email && $msg && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sujet = "Contact prestataire #" . $id . " - " . ($prestataire['nom_entreprise'] ?? '');
        $message = "Téléphone : $tel\nDate mariage : $date\nInvités : $guests\n\n$msg";
        $stmt = $pdo->prepare("INSERT INTO message_contact (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
        $success = $stmt->execute([$nom, $email, $sujet, $message]);
    } else {
        $error = "Merci de remplir tous les champs obligatoires correctement.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter le prestataire - Mariage Parfait</title>
    <meta name="description" content="Contactez directement ce prestataire pour votre mariage">
    
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Feuille de style principale -->
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- Styles spécifiques à la page contact prestataire -->
    <style>
        .contact-provider-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-8);
            max-width: 1200px;
            margin: 0 auto;
        }
        
        @media (min-width: 992px) {
            .contact-provider-container {
                grid-template-columns: 2fr 1fr;
            }
        }
        
        .contact-form-container {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-8);
            border: 1px solid var(--color-primary-light);
        }
        
        .contact-form-container h2 {
            margin-top: 30px;
            margin-bottom: var(--spacing-4);
        }
        
        .contact-form-container p {
            color: var(--color-muted-foreground);
            margin-bottom: var(--spacing-6);
        }
        
        .form-group {
            margin-bottom: var(--spacing-4);
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: var(--spacing-3) var(--spacing-4);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            background-color: var(--color-background);
            color: var(--color-foreground);
            font-family: var(--font-sans);
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-4);
        }
        
        .provider-card {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--color-primary-light);
        }
        
        .provider-header {
            padding: var(--spacing-6);
            background: linear-gradient(to right, var(--color-primary-light), var(--color-secondary-light));
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
        }
        
        .provider-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid white;
            box-shadow: var(--shadow);
        }
        
        .provider-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .provider-info h3 {
            font-size: 1.5rem;
            margin-bottom: var(--spacing-1);
        }
        
        .provider-info p {
            color: var(--color-muted-foreground);
        }
        
        .provider-rating {
            display: flex;
            align-items: center;
            gap: var(--spacing-1);
            margin-top: var(--spacing-1);
        }
        
        .provider-rating i {
            color: #f59e0b;
        }
        
        .provider-content {
            padding: var(--spacing-6);
        }
        
        .provider-detail {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-4);
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
        
        .provider-actions {
            padding: var(--spacing-6);
            border-top: 1px solid var(--color-border);
            display: flex;
            flex-direction: column;
            gap: var(--spacing-3);
        }
        
        .success-message {
            display: none;
            background-color: #10b981;
            color: white;
            padding: var(--spacing-4);
            border-radius: var(--radius);
            margin-bottom: var(--spacing-6);
            text-align: center;
        }
        
        .success-message.show {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .date-picker {
            position: relative;
        }
        
        .date-picker input {
            padding-right: var(--spacing-8);
        }
        
        .date-picker i {
            position: absolute;
            right: var(--spacing-3);
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-muted-foreground);
            pointer-events: none;
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
                <section class="section">
                    <div class="section-header">
                        <h1 class="section-title decorative-line">Contacter le prestataire</h1>
                        <p class="section-description">Envoyez un message directement au prestataire pour discuter de votre projet de mariage</p>
                    </div>
                    
                    <div class="contact-provider-container">
                        <!-- Formulaire de contact -->
                        <div class="contact-form-container">
                            <h2>Envoyez un message à <?php echo htmlspecialchars($prestataire['nom_entreprise'] ?? 'Prestataire'); ?></h2>
                            <p>Remplissez le formulaire ci-dessous pour contacter directement ce prestataire.</p>
                            <?php if ($success): ?>
                                <div class="success-message show">
                                    <i class="fas fa-check-circle"></i> Message envoyé ! Le prestataire vous répondra dans les plus brefs délais.
                                </div>
                            <?php elseif ($error): ?>
                                <div class="success-message" style="background:#ef4444;">
                                    <i class="fas fa-times-circle"></i> <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>
                            <form id="contact-provider-form" method="post" action="contact-prestataire.php?id=<?php echo $id; ?>">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="name">Nom complet</label>
                                        <input type="text" id="name" name="name" placeholder="Votre nom" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" name="email" placeholder="votre@email.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="phone">Téléphone</label>
                                        <input type="tel" id="phone" name="phone" placeholder="Votre numéro de téléphone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="wedding-date">Date du mariage</label>
                                        <div class="date-picker">
                                            <input type="date" id="wedding-date" name="wedding-date" value="<?php echo htmlspecialchars($_POST['wedding-date'] ?? ''); ?>">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="guests">Nombre d'invités estimé</label>
                                    <select id="guests" name="guests">
                                        <option value="">Sélectionnez</option>
                                        <option value="1-50" <?php if(($_POST['guests'] ?? '')=='1-50') echo 'selected'; ?>>1-50 invités</option>
                                        <option value="51-100" <?php if(($_POST['guests'] ?? '')=='51-100') echo 'selected'; ?>>51-100 invités</option>
                                        <option value="101-150" <?php if(($_POST['guests'] ?? '')=='101-150') echo 'selected'; ?>>101-150 invités</option>
                                        <option value="151-200" <?php if(($_POST['guests'] ?? '')=='151-200') echo 'selected'; ?>>151-200 invités</option>
                                        <option value="201+" <?php if(($_POST['guests'] ?? '')=='201+') echo 'selected'; ?>>Plus de 200 invités</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="message">Votre message</label>
                                    <textarea id="message" name="message" placeholder="Détaillez votre demande, vos questions ou vos besoins spécifiques..." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-rounded btn-block">Envoyer le message</button>
                            </form>
                        </div>
                        
                        <!-- Informations du prestataire -->
                        <div>
                            <div class="provider-card">
                                <div class="provider-header">
                                    <div class="provider-avatar">
                                        <img src="<?php echo htmlspecialchars($prestataire['image_profil'] ?? 'images/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($prestataire['nom_entreprise'] ?? 'Prestataire'); ?>">
                                    </div>
                                    <div class="provider-info">
                                        <h3><?php echo htmlspecialchars($prestataire['nom_entreprise'] ?? 'Prestataire'); ?></h3>
                                        <p><?php echo htmlspecialchars($prestataire['categorie'] ?? ''); ?></p>
                                        <div class="provider-rating">
                                            <?php
                                            $stmtNote = $pdo->prepare("SELECT AVG(note) as moyenne, COUNT(*) as nb FROM commentaire WHERE id_prestataire = ?");
                                            $stmtNote->execute([$id]);
                                            $noteData = $stmtNote->fetch();
                                            $moyenne = $noteData['moyenne'] ? round($noteData['moyenne'], 1) : '—';
                                            $nbAvis = $noteData['nb'];
                                            for ($i=0; $i<5; $i++) {
                                                if ($i < floor($moyenne)) echo '<i class="fas fa-star"></i>';
                                                elseif ($i == floor($moyenne) && ($moyenne-floor($moyenne))>=0.5) echo '<i class="fas fa-star-half-alt"></i>';
                                                else echo '<i class="far fa-star"></i>';
                                            }
                                            ?>
                                            <span><?php echo $moyenne; ?> (<?php echo $nbAvis; ?> avis)</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="provider-content">
                                    <?php if (!empty($prestataire['region'])): ?>
                                    <div class="provider-detail">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <div class="provider-detail-content">
                                            <p>Région</p>
                                            <p><?php echo htmlspecialchars($prestataire['region']); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($prestataire['contact_telephone'])): ?>
                                    <div class="provider-detail">
                                        <i class="fas fa-phone-alt"></i>
                                        <div class="provider-detail-content">
                                            <p>Téléphone</p>
                                            <p><?php echo htmlspecialchars($prestataire['contact_telephone']); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($prestataire['contact_email'])): ?>
                                    <div class="provider-detail">
                                        <i class="fas fa-envelope"></i>
                                        <div class="provider-detail-content">
                                            <p>Email</p>
                                            <p><?php echo htmlspecialchars($prestataire['contact_email']); ?></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php if (!empty($prestataire['site_web'])): ?>
                                    <div class="provider-detail">
                                        <i class="fas fa-globe"></i>
                                        <div class="provider-detail-content">
                                            <p>Site web</p>
                                            <p><a href="<?php echo htmlspecialchars($prestataire['site_web']); ?>" target="_blank"><?php echo htmlspecialchars($prestataire['site_web']); ?></a></p>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="provider-actions">
                                    <a href="prestataire-detail.php?id=<?php echo $id; ?>" class="btn btn-outline btn-rounded btn-block">Voir le profil complet</a>
                                    <?php if (!empty($prestataire['region'])): ?>
                                    <a href="https://www.google.com/maps/search/<?php echo urlencode($prestataire['region']); ?>" target="_blank" class="btn btn-outline btn-rounded btn-block">Voir sur la carte</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="provider-card" style="margin-top: var(--spacing-6);">
                                <div class="provider-content">
                                    <h3 style="margin-bottom: var(--spacing-4);">Temps de réponse</h3>
                                    <div class="provider-detail">
                                        <i class="fas fa-clock"></i>
                                        <div class="provider-detail-content">
                                            <p>Répond généralement sous 24h</p>
                                        </div>
                                    </div>
                                    
                                    <h3 style="margin: var(--spacing-6) 0 var(--spacing-4);">Disponibilité</h3>
                                    <div class="provider-detail">
                                        <i class="fas fa-calendar-check"></i>
                                        <div class="provider-detail-content">
                                            <p>Disponible pour la saison 2025</p>
                                            <p>Quelques dates encore libres pour 2024</p>
                                        </div>
                                    </div>
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
    
    <!-- Script spécifique à la page contact prestataire -->
    <script>
        // Définir la date minimale pour le sélecteur de date (aujourd'hui)
        document.addEventListener('DOMContentLoaded', function() {
            const weddingDateInput = document.getElementById('wedding-date');
            if (weddingDateInput) {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                const formattedToday = `${yyyy}-${mm}-${dd}`;
                weddingDateInput.setAttribute('min', formattedToday);
            }
        });
    </script>
</body>
</html>