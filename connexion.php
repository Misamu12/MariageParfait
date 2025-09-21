<?php
require_once 'config.php';

$login_error = '';
$register_error = '';
$register_success = false;

// Connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id_utilisateur'];
            $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
            header('Location: index.php');
            exit;
        } else {
            $login_error = "Email ou mot de passe incorrect.";
        }
    } else {
        $login_error = "Merci de remplir tous les champs.";
    }
}

// Inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $prenom = trim($_POST['firstname'] ?? '');
    $nom = trim($_POST['lastname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $terms = isset($_POST['terms']);

    if ($prenom && $nom && $email && $password && $terms && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Vérifier si l'email existe déjà
        $stmt = $pdo->prepare("SELECT id_utilisateur FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $register_error = "Cet email est déjà utilisé.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateur (prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$prenom, $nom, $email, $hash])) {
                $register_success = true;
            } else {
                $register_error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
    } else {
        $register_error = "Merci de remplir tous les champs et d'accepter les conditions.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Mariage Parfait</title>
    <meta name="description" content="Connectez-vous à votre compte Mariage Parfait ou créez un nouveau compte">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .auth-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-8);
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-8) 0;
        }
        
        @media (min-width: 992px) {
            .auth-container {
                grid-template-columns: 3fr 2fr;
            }
        }
        
        .auth-form-container {
            background-color: var(--color-background);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: var(--spacing-8);
            border: 1px solid var(--color-primary-light);
        }
        
        .auth-tabs {
            display: flex;
            border-bottom: 1px solid var(--color-border);
            margin-bottom: var(--spacing-6);
        }
        
        .auth-tab {
            flex: 1;
            text-align: center;
            padding: var(--spacing-3) 0;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        
        .auth-tab.active {
            color: var(--color-primary);
        }
        
        .auth-tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: var(--color-primary);
        }
        
        .auth-tab-content {
            display: none;
        }
        
        .auth-tab-content.active {
            display: block;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .form-group {
            margin-bottom: var(--spacing-4);
        }
        
        .form-group label {
            display: block;
            margin-bottom: var(--spacing-2);
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: var(--spacing-3) var(--spacing-4);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            background-color: var(--color-background);
            color: var(--color-foreground);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-4);
        }
        
        .password-container {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: var(--spacing-3);
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-muted-foreground);
            cursor: pointer;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--spacing-4);
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: var(--spacing-2);
        }
        
        .remember-me input {
            width: auto;
        }
        
        .forgot-password {
            color: var(--color-primary);
            text-decoration: underline;
        }
        
        .auth-divider {
            position: relative;
            text-align: center;
            margin: var(--spacing-6) 0;
        }
        
        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 1px;
            background-color: var(--color-border);
        }
        
        .auth-divider span {
            position: relative;
            background-color: var(--color-background);
            padding: 0 var(--spacing-4);
            color: var(--color-muted-foreground);
            font-size: 0.875rem;
            text-transform: uppercase;
        }
        
        .social-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-4);
            margin-bottom: var(--spacing-4);
        }
        
        .social-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-2);
            padding: var(--spacing-3) 0;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-full);
            font-weight: 500;
            transition: var(--transition-base);
        }
        
        .social-button:hover {
            background-color: var(--color-muted);
        }
        
        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-2);
            margin-bottom: var(--spacing-4);
        }
        
        .terms-checkbox input {
            margin-top: var(--spacing-1);
            width: auto;
        }
        
        .terms-checkbox label {
            font-size: 0.875rem;
            margin-bottom: 0;
            font-weight: normal;
        }
        
        .terms-checkbox a {
            color: var(--color-primary);
        }
        
        .auth-benefits {
            background: linear-gradient(to bottom right, var(--color-primary-light), var(--color-secondary-light));
            border-radius: var(--radius);
            padding: var(--spacing-8);
            border: 1px solid var(--color-primary-light);
        }
        
        .auth-benefits h3 {
            margin-bottom: var(--spacing-6);
            font-size: 1.25rem;
        }
        
        .benefit-item {
            display: flex;
            align-items: flex-start;
            gap: var(--spacing-3);
            margin-bottom: var(--spacing-4);
        }
        
        .benefit-icon {
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
        
        .benefit-content h4 {
            font-size: 1rem;
            margin-bottom: var(--spacing-1);
        }
        
        .benefit-content p {
            font-size: 0.875rem;
            color: var(--color-muted-foreground);
        }
        
        .provider-cta {
            margin-top: var(--spacing-8);
            background-color: var(--color-background);
            border-radius: var(--radius);
            padding: var(--spacing-6);
            text-align: center;
        }
        
        .provider-cta h3 {
            margin-bottom: var(--spacing-2);
            font-size: 1.125rem;
        }
        
        .provider-cta p {
            margin-bottom: var(--spacing-4);
            font-size: 0.875rem;
            color: var(--color-muted-foreground);
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
                            <li>
                                <a href="connexion.php" class="btn btn-primary btn-rounded">Connexion</a>
                            </li>
                            <li>
                                <a href="logout.php" class="btn btn-primary btn-rounded">Déconnexion</a>
                            </li>
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
                        <h1 class="section-title decorative-line">Bienvenue</h1>
                        <p class="section-description">Connectez-vous ou créez un compte pour accéder à toutes nos fonctionnalités</p>
                    </div>
                    
                    <div class="auth-container">
                        <!-- Formulaire d'authentification -->
                        <div class="auth-form-container">
                            <div class="auth-tabs">
                                <div class="auth-tab active" data-tab="login">Connexion</div>
                                <div class="auth-tab" data-tab="register">Inscription</div>
                            </div>
                            
                            <!-- Onglet Connexion -->
                            <div class="auth-tab-content active" id="login-tab">
                                <?php if ($login_error): ?>
                                    <div class="success-message" style="background:#ef4444;">
                                        <i class="fas fa-times-circle"></i> <?php echo htmlspecialchars($login_error); ?>
                                    </div>
                                <?php endif; ?>
                                <form id="login-form" method="post" action="connexion.php">
                                    <div class="form-group">
                                        <label for="login-email">Email</label>
                                        <input type="email" id="login-email" name="email" placeholder="exemple@email.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="login-password">Mot de passe</label>
                                        <div class="password-container">
                                            <input type="password" id="login-password" name="password" placeholder="••••••••" required>
                                            <button type="button" class="password-toggle" data-target="login-password">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-footer">
                                        <div class="remember-me">
                                            <input type="checkbox" id="remember" name="remember">
                                            <label for="remember">Se souvenir de moi</label>
                                        </div>
                                        <a href="mot-de-passe-oublie.php" class="forgot-password">Mot de passe oublié ?</a>
                                    </div>
                                    <button type="submit" name="login" class="btn btn-primary btn-rounded btn-block">Se connecter</button>
                                </form>
                                <div class="auth-divider">
                                    <span>Ou continuer avec</span>
                                </div>
                                <div class="social-buttons">
                                    <a href="#" class="social-button">
                                        <i class="fab fa-facebook-f"></i>
                                        Facebook
                                    </a>
                                    <a href="#" class="social-button">
                                        <i class="fab fa-google"></i>
                                        Google
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Onglet Inscription -->
                            <div class="auth-tab-content" id="register-tab">
                                <?php if ($register_error): ?>
                                    <div class="success-message" style="background:#ef4444;">
                                        <i class="fas fa-times-circle"></i> <?php echo htmlspecialchars($register_error); ?>
                                    </div>
                                <?php elseif ($register_success): ?>
                                    <div class="success-message show">
                                        <i class="fas fa-check-circle"></i> Inscription réussie ! Vous pouvez maintenant vous connecter.
                                    </div>
                                <?php endif; ?>
                                <form id="register-form" method="post" action="connexion.php">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="register-firstname">Prénom</label>
                                            <input type="text" id="register-firstname" name="firstname" placeholder="Prénom" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="register-lastname">Nom</label>
                                            <input type="text" id="register-lastname" name="lastname" placeholder="Nom" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="register-email">Email</label>
                                        <input type="email" id="register-email" name="email" placeholder="exemple@email.com" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="register-password">Mot de passe</label>
                                        <div class="password-container">
                                            <input type="password" id="register-password" name="password" placeholder="••••••••" required>
                                            <button type="button" class="password-toggle" data-target="register-password">
                                                <i class="far fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="terms-checkbox">
                                        <input type="checkbox" id="terms" name="terms" required>
                                        <label for="terms">
                                            J'accepte les <a href="conditions.php">conditions d'utilisation</a> et la <a href="confidentialite.php">politique de confidentialité</a>
                                        </label>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-primary btn-rounded btn-block">S'inscrire</button>
                                </form>
                                <div class="auth-divider">
                                    <span>Ou s'inscrire avec</span>
                                </div>
                                <div class="social-buttons">
                                    <a href="#" class="social-button">
                                        <i class="fab fa-facebook-f"></i>
                                        Facebook
                                    </a>
                                    <a href="#" class="social-button">
                                        <i class="fab fa-google"></i>
                                        Google
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avantages de l'inscription -->
                        <div class="auth-benefits">
                            <h3>Les avantages de l'inscription</h3>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4>Contactez les prestataires</h4>
                                    <p>Échangez directement avec les prestataires pour organiser votre mariage.</p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4>Sauvegardez vos favoris</h4>
                                    <p>Créez une liste de favoris pour retrouver facilement les prestataires qui vous intéressent.</p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4>Partagez votre avis</h4>
                                    <p>Laissez des commentaires et des évaluations sur les prestataires que vous avez utilisés.</p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="benefit-content">
                                    <h4>Offres exclusives</h4>
                                    <p>Accédez à des offres et réductions exclusives réservées aux membres.</p>
                                </div>
                            </div>
                            
                            <div class="provider-cta">
                                <h3>Vous êtes un prestataire ?</h3>
                                <p>Rejoignez notre plateforme et développez votre activité.</p>
                                <a href="devenir-prestataire.php" class="btn btn-primary btn-rounded">Devenir prestataire</a>
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
    
    <!-- Script spécifique à la page connexion -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion des onglets
            const tabs = document.querySelectorAll('.auth-tab');
            const tabContents = document.querySelectorAll('.auth-tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Désactiver tous les onglets et contenus
                    tabs.forEach(t => t.classList.remove('active'));
                    tabContents.forEach(c => c.classList.remove('active'));
                    
                    // Activer l'onglet et le contenu sélectionnés
                    this.classList.add('active');
                    document.getElementById(`${tabId}-tab`).classList.add('active');
                });
            });
            
            // Gestion de l'affichage du mot de passe
            const passwordToggles = document.querySelectorAll('.password-toggle');
            
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
</body>
</html>