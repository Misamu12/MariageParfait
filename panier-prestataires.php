<?php
require_once 'config.php';

// Identifiant de session unique
if (!isset($_SESSION['session_id'])) {
    $_SESSION['session_id'] = session_id();
}
$session_id = $_SESSION['session_id'];

// Enregistrement de la demande de devis (formulaire)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['demande_devis'])) {
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $date_mariage = $_POST['date_mariage'] ?? null;
    $nb_invites = $_POST['nb_invites'] ?? null;
    $budget = $_POST['budget'] ?? '';
    $demandes_speciales = $_POST['demandes_speciales'] ?? '';

    // Récupère les prestataires du panier (ex: depuis une table temporaire ou via POST)
    $prestataires = $_POST['prestataires'] ?? []; // tableau d'id_prestataire

    foreach ($prestataires as $id_prestataire) {
        // Vérifie que l'id existe dans la table prestataire
        $check = $pdo->prepare("SELECT COUNT(*) FROM prestataire WHERE id_prestataire = ?");
        $check->execute([$id_prestataire]);
        if ($check->fetchColumn() == 0) {
            continue; // Ignore si l'id n'existe pas
        }
        $stmt = $pdo->prepare("INSERT INTO panier 
            (session_id, id_prestataire, quantite, nom, email, telephone, date_mariage, nb_invites, budget, demandes_speciales)
            VALUES (?, ?, 1, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $session_id, $id_prestataire, $nom, $email, $telephone, $date_mariage, $nb_invites, $budget, $demandes_speciales
        ]);
    }
    $success = true;
}

// Récupérer tous les prestataires pour le select
$prestataires = $pdo->query("SELECT id_prestataire, nom_entreprise FROM prestataire")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier - Mariage Parfait</title>
    <meta name="description" content="Gérez vos prestataires sélectionnés et demandez un devis personnalisé">
    
    <!-- Polices Google Fonts -->
     <link rel="stylesheet" href="css/panier.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">
                    <span class="fancy-title">Mariage Parfait</span>
                </a>
                
                <!-- Burger Menu Button -->
                <button class="burger-btn" id="burger-btn" aria-label="Menu">
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                    <span class="burger-line"></span>
                </button>

                <!-- Navigation Links -->
                <nav class="nav-links" id="nav-links">
                    <div class="nav-overlay" id="nav-overlay"></div>
                    <div class="nav-menu">
                        <a href="index.php" class="nav-link">
                            <i class="fas fa-home"></i>
                            <span>Accueil</span>
                        </a>
                        <a href="prestataires.php" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Prestataires</span>
                        </a>
                        <a href="panier.php" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Mon Panier (<span id="cart-count-nav">0</span>)</span>
                        </a>
                        <a href="contact.php" class="nav-link contact-btn">
                            <i class="fas fa-envelope"></i>
                            <span>Contact</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="fancy-title">Mon Panier</h1>
            <p>Gérez vos prestataires sélectionnés et demandez un devis personnalisé</p>
        </div>
    </div>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <span>Votre demande de devis a été envoyée avec succès !</span>
                </div>
            <?php endif; ?>

            <div class="add-prestataire-form">
    <form id="add-prestataire-form" style="display:flex;gap:10px;align-items:center;">
        <select id="prestataire-select" class="form-control" required>
            <option value="">Ajouter un prestataire...</option>
            <?php foreach ($prestataires as $p): ?>
                <option value="<?= $p['id_prestataire'] ?>"><?= htmlspecialchars($p['nom_entreprise']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>

            <div class="cart-items" id="cart-items-block">
    <div class="cart-header">
        <h2>Mes Prestataires Sélectionnés</h2>
        <span class="cart-count" id="cart-count">0</span>
    </div>
    <div id="cart-items-container">
    </div>
</div>


<div id="empty-cart" class="empty-cart">
    <div class="empty-cart-icon">
        <i class="fas fa-shopping-cart"></i>
    </div>
    <h3>Votre panier est vide</h3>
    <p>Découvrez nos prestataires et ajoutez-les à votre panier pour créer le mariage de vos rêves.</p>
    </a>
</div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-header">
                        <h3>Résumé de votre sélection</h3>
                    </div>

                    <div id="summary-details">
                        <div class="summary-line">
                            <span>Nombre de prestataires:</span>
                            <span id="total-items">0</span>
                        </div>
                    </div>

                    <!-- Quote Form -->
                    <form id="quote-form" class="quote-form" method="POST" action="">
                        <h4 style="margin-bottom: var(--spacing-4); color: var(--color-primary);">
                            <i class="fas fa-file-invoice"></i>
                            Demander un devis personnalisé
                        </h4>

                        <div class="form-group">
                            <label class="form-label" for="client-name">Nom complet *</label>
                            <input type="text" id="client-name" name="nom" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="client-email">Email *</label>
                            <input type="email" id="client-email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="client-phone">Téléphone</label>
                            <input type="tel" id="client-phone" name="telephone" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="wedding-date">Date du mariage</label>
                            <input type="date" id="wedding-date" name="date_mariage" class="form-control">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="guest-count">Nombre d'invités</label>
                            <input type="number" id="guest-count" name="nb_invites" class="form-control" min="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="budget">Budget approximatif</label>
                            <select id="budget" name="budget" class="form-control">
                                <option value="">Sélectionnez votre budget</option>
                                <option value="5000-10000">5 000 € - 10 000 €</option>
                                <option value="10000-20000">10 000 € - 20 000 €</option>
                                <option value="20000-30000">20 000 € - 30 000 €</option>
                                <option value="30000-50000">30 000 € - 50 000 €</option>
                                <option value="50000+">Plus de 50 000 €</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="special-requests">Demandes spéciales</label>
                            <textarea id="special-requests" name="demandes_speciales" class="form-control" placeholder="Décrivez vos souhaits particuliers, contraintes ou questions..."></textarea>
                        </div>

                        <!-- Génère dynamiquement ces champs selon le panier JS -->

                        <button type="submit" name="demande_devis" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i>
                            Demander mon devis gratuit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Sélection d'un prestataire à ajouter -->


    <!-- Message de succès caché pour JS -->
<div id="success-message" class="success-message" style="display:none;">
    <i class="fas fa-check-circle"></i>
    <span>Votre demande de devis a été envoyée avec succès !</span>
</div>

    <script>
        // ===== GESTION DU MENU BURGER =====
class BurgerMenu {
  constructor() {
    this.burgerBtn = document.getElementById("burger-btn")
    this.navLinks = document.getElementById("nav-links")
    this.navOverlay = document.getElementById("nav-overlay")
    this.body = document.body

    this.init()
  }

  init() {
    // Event listeners
    this.burgerBtn.addEventListener("click", () => this.toggleMenu())
    this.navOverlay.addEventListener("click", () => this.closeMenu())

    // Fermer le menu avec Escape
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && this.navLinks.classList.contains("active")) {
        this.closeMenu()
      }
    })

    // Fermer le menu lors du redimensionnement vers desktop
    window.addEventListener("resize", () => {
      if (window.innerWidth >= 1024) {
        this.closeMenu()
      }
    })

    // Fermer le menu lors du clic sur un lien (mobile)
    const navLinksElements = document.querySelectorAll(".nav-link")
    navLinksElements.forEach((link) => {
      link.addEventListener("click", () => {
        if (window.innerWidth < 1024) {
          this.closeMenu()
        }
      })
    })
  }

  toggleMenu() {
    if (this.navLinks.classList.contains("active")) {
      this.closeMenu()
    } else {
      this.openMenu()
    }
  }

  openMenu() {
    this.navLinks.classList.add("active")
    this.burgerBtn.classList.add("active")
    this.body.style.overflow = "hidden"
  }

  closeMenu() {
    this.navLinks.classList.remove("active")
    this.burgerBtn.classList.remove("active")
    this.body.style.overflow = ""
  }
}

// ===== GESTION DU PANIER =====
class CartManager {
  constructor() {
    this.cart = JSON.parse(localStorage.getItem("cart")) || []
    this.cartCountNav = document.getElementById("cart-count-nav")
    this.cartCount = document.getElementById("cart-count")
    this.cartItemsContainer = document.getElementById("cart-items-container")
    this.emptyCart = document.getElementById("empty-cart")
    this.totalItems = document.getElementById("total-items")
    this.quoteForm = document.getElementById("quote-form")
    this.successMessage = document.getElementById("success-message")

    this.init()
  }

  init() {
    this.updateCartDisplay()
    this.setupEventListeners()
  }

  setupEventListeners() {
    // Ajout d'un prestataire depuis le select
    document.getElementById("add-prestataire-form").addEventListener("submit", (e) => {
        e.preventDefault()
        const select = document.getElementById("prestataire-select")
        const id = parseInt(select.value)
        const name = select.options[select.selectedIndex].text

        if (!id) return

        // Vérifie si déjà dans le panier
        const found = this.cart.find(item => item.id === id)
        if (found) {
            found.quantity += 1
        } else {
            this.cart.push({
                id: id,
                name: name,
                quantity: 1
            })
        }
        this.saveCart()
        this.updateCartDisplay()
        select.value = ""
    })

    // Formulaire de devis
    this.quoteForm.addEventListener("submit", (e) => {
        // Supprime les anciens champs cachés
        this.quoteForm.querySelectorAll('input[name="prestataires[]"]').forEach(el => el.remove())
        // Ajoute les IDs du panier
        this.cart.forEach(item => {
            const input = document.createElement("input")
            input.type = "hidden"
            input.name = "prestataires[]"
            input.value = item.id
            this.quoteForm.appendChild(input)
        })
        // Ensuite, laisse le submit se faire normalement
    })
  }

  updateCartDisplay() {
    const itemCount = this.cart.reduce((total, item) => total + item.quantity, 0)
    this.cartCountNav.textContent = itemCount
    this.cartCount.textContent = itemCount
    this.totalItems.textContent = itemCount

    const cartItemsBlock = document.getElementById("cart-items-block")

    if (this.cart.length === 0) {
        this.emptyCart.style.display = "block"
        this.cartItemsContainer.innerHTML = ""
        cartItemsBlock.style.display = "none"
    } else {
        this.emptyCart.style.display = "none"
        cartItemsBlock.style.display = "block"
        this.renderCartItems()
    }
}

  renderCartItems() {
    this.cartItemsContainer.innerHTML = ""

    this.cart.forEach((item, index) => {
        const cartItemHTML = `
            <div class="cart-item" data-index="${index}">
                <div class="item-details">
                    <h3 class="item-name">${item.name}</h3>
                </div>
                <div class="item-actions">
                    <button class="quantity-btn" onclick="cartManager.updateQuantity(${index}, ${item.quantity - 1})">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1"
                        onchange="cartManager.updateQuantity(${index}, this.value)">
                    <button class="quantity-btn" onclick="cartManager.updateQuantity(${index}, ${item.quantity + 1})">+</button>
                    <button class="remove-btn" onclick="cartManager.removeItem(${index})" title="Supprimer">🗑️</button>
                </div>
            </div>
        `
        this.cartItemsContainer.innerHTML += cartItemHTML
    })
  }

  updateQuantity(index, newQuantity) {
    newQuantity = Number.parseInt(newQuantity)
    if (newQuantity < 1) {
      this.removeItem(index)
      return
    }

    this.cart[index].quantity = newQuantity
    this.saveCart()
    this.updateCartDisplay()
  }

  removeItem(index) {
    this.cart.splice(index, 1)
    this.saveCart()
    this.updateCartDisplay()
  }

  saveCart() {
    localStorage.setItem("cart", JSON.stringify(this.cart))
  }

  handleQuoteSubmission(e) {
    e.preventDefault()
    // Affiche le message de succès
    this.successMessage.style.display = "block"
    this.quoteForm.reset()
    // Masque le message après 5 secondes
    setTimeout(() => {
        this.successMessage.style.display = "none"
    }, 5000)
  }

  // Méthode pour ajouter des éléments de test
  
}

// ===== INITIALISATION =====
document.addEventListener("DOMContentLoaded", () => {
  // Initialiser le menu burger
  const burgerMenu = new BurgerMenu()

  // Initialiser le gestionnaire de panier
  window.cartManager = new CartManager()

  // Animation d'entrée pour les éléments
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  }

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in")
      }
    })
  }, observerOptions)

  // Observer les éléments à animer
  document.querySelectorAll(".cart-item, .order-summary").forEach((el) => {
    observer.observe(el)
  })
})

// ===== UTILITAIRES =====
// Fonction pour formater les prix
function formatPrice(price) {
  if (typeof price === "number") {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "EUR",
    }).format(price)
  }
  return price
}

// Fonction pour valider l'email
function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return re.test(email)
}

// Fonction pour afficher des notifications
function showNotification(message, type = "success") {
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.innerHTML = `
        <i class="fas fa-${type === "success" ? "check-circle" : "exclamation-circle"}"></i>
        <span>${message}</span>
    `

  document.body.appendChild(notification)

  setTimeout(() => {
    notification.classList.add("show")
  }, 100)

  setTimeout(() => {
    notification.classList.remove("show")
    setTimeout(() => {
      document.body.removeChild(notification)
    }, 300)
  }, 3000)
}

    </script>
</body>
</html>
