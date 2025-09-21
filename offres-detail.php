<?php
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer l'offre
$stmt = $pdo->prepare("SELECT o.*, p.nom_entreprise FROM offre_speciale o LEFT JOIN prestataire p ON o.id_prestataire = p.id_prestataire WHERE o.id_offre = ?");
$stmt->execute([$id]);
$offer = $stmt->fetch();

if (!$offer) {
    echo "<div class='container'><div class='error'>Offre introuvable.</div></div>";
    exit;
}

// Traitement du formulaire
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("INSERT INTO demande_offre (id_offre, nom, email, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$id, $nom, $email, $message]);
        $success = true;
    } else {
        $error = "Veuillez remplir tous les champs obligatoires avec des informations valides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail de l'offre - <?php echo htmlspecialchars($offer['titre']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="container" style="max-width:700px;margin-top:3rem;">
                <div class="offer-card card-fancy" style="margin-bottom:2rem;">
                    <div class="offer-image" style="height:260px;">
                        <img src="<?php echo htmlspecialchars($offer['image']); ?>" alt="Image offre">
                    </div>
                    <div class="offer-content" style="padding:2rem;">
                        <h1 style="margin-bottom:1rem;"><?php echo htmlspecialchars($offer['titre']); ?></h1>
                        <p class="offer-provider" style="margin-bottom:1rem;">
                            <strong>Prestataire :</strong> <?php echo htmlspecialchars($offer['nom_entreprise']); ?>
                        </p>
                        <p class="offer-description" style="margin-bottom:1rem;">
                            <?php echo nl2br(htmlspecialchars($offer['description'])); ?>
                        </p>
                        <?php if ($offer['date_fin']): ?>
                            <p class="offer-validity">
                                <strong>Valable jusqu'au :</strong> <?php echo date('d/m/Y', strtotime($offer['date_fin'])); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card-fancy" style="padding:2rem;">
                    <h2 style="margin-bottom:1.5rem;">Demander cette offre</h2>
                    <?php if ($success): ?>
                        <div class="success" style="background:var(--color-green-light);color:var(--color-green);padding:1rem;border-radius:var(--radius);margin-bottom:1rem;">
                            Votre demande a bien été envoyée ! Nous vous contacterons rapidement.
                        </div>
                    <?php else: ?>
                        <?php if ($error): ?>
                            <div class="error" style="background:var(--color-red-light);color:var(--color-red);padding:1rem;border-radius:var(--radius);margin-bottom:1rem;">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" style="display:flex;flex-direction:column;gap:1rem;">
                            <label>Nom*<br>
                                <input type="text" name="nom" required class="input" style="width:100%;padding:0.75rem;border-radius:var(--radius);border:1px solid var(--color-border);">
                            </label>
                            <label>Email*<br>
                                <input type="email" name="email" required class="input" style="width:100%;padding:0.75rem;border-radius:var(--radius);border:1px solid var(--color-border);">
                            </label>
                            <label>Message<br>
                                <textarea name="message" rows="4" class="input" style="width:100%;padding:0.75rem;border-radius:var(--radius);border:1px solid var(--color-border);"></textarea>
                            </label>
                            <button type="submit" class="btn btn-primary btn-rounded" style="margin-top:0.5rem;">Envoyer la demande</button>
                        </form>
                    <?php endif; ?>
                    <p style="margin-top:1.5rem;"><a href="offres.php" class="btn btn-outline btn-rounded">← Retour aux offres</a></p>
                </div>
            </div>
        </main>
        <!-- Footer (optionnel, à reprendre depuis index.php si besoin) -->
    </div>
    <script src="js/script.js"></script>
</body>
</html>