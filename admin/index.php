<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Wedding</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Login Page -->
    <div id="loginPage" class="login-page">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="login-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h1>Connexion Admin</h1>
                    <p>Accédez au panneau d'administration</p>
                </div>
                <div class="login-content">
                    <form id="loginForm">
                        <div id="loginError" class="error-message" style="display: none;"></div>
                        
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" placeholder="admin@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" placeholder="••••••••" required>
                        </div>

                        <button type="submit" class="login-btn">Se connecter</button>

                        <div class="login-info">
                            <p>Identifiants de test :</p>
                            <p>Email: admin@wedding.com</p>
                            <p>Mot de passe: admin123</p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard -->
    <div id="dashboard" class="dashboard" style="display: none;">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-title">Admin Panel</span>
                </div>
            </div>

            <nav class="sidebar-nav">
                <button class="nav-item active" data-tab="dashboard">
                    <i class="fas fa-chart-bar"></i>
                    <span>Dashboard</span>
                </button>
                <button class="nav-item" data-tab="news">
                    <i class="fas fa-newspaper"></i>
                    <span>Actualités</span>
                    <span class="badge" id="newsBadge">3</span>
                </button>
                <button class="nav-item" data-tab="providers">
                    <i class="fas fa-store"></i>
                    <span>Prestataires</span>
                    <span class="badge" id="providersBadge">3</span>
                </button>
                <button class="nav-item" data-tab="emails">
                    <i class="fas fa-envelope"></i>
                    <span>E-mails</span>
                    <span class="badge">127.3k</span>
                </button>
                <button class="nav-item" data-tab="users">
                    <i class="fas fa-users"></i>
                    <span>Utilisateurs</span>
                    <span class="badge">892</span>
                </button>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="header-left">
                    <button id="sidebarToggle" class="sidebar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 id="pageTitle">Dashboard</h1>
                </div>

                <div class="header-right">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Rechercher...">
                    </div>
                    <button class="header-btn">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="user-menu">
                        <button class="user-btn" id="userMenuBtn">
                            <img src="https://via.placeholder.com/32" alt="Admin">
                            <span id="userName">Administrateur</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="#" class="dropdown-item">Profil</a>
                            <a href="#" class="dropdown-item">Paramètres</a>
                            <a href="#" class="dropdown-item logout" id="logoutBtn">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="content">
                <!-- Dashboard Tab -->
                <div id="dashboardTab" class="tab-content active">
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-content">
                                <div class="kpi-info">
                                    <p class="kpi-label">E-mails envoyés</p>
                                    <p class="kpi-value">1,247</p>
                                    <p class="kpi-change positive">+12.5% vs mois dernier</p>
                                </div>
                                <i class="fas fa-envelope kpi-icon blue"></i>
                            </div>
                        </div>

                        <div class="kpi-card">
                            <div class="kpi-content">
                                <div class="kpi-info">
                                    <p class="kpi-label">Utilisateurs</p>
                                    <p class="kpi-value">892</p>
                                    <p class="kpi-change positive">+8.2% vs mois dernier</p>
                                </div>
                                <i class="fas fa-users kpi-icon green"></i>
                            </div>
                        </div>

                        <div class="kpi-card">
                            <div class="kpi-content">
                                <div class="kpi-info">
                                    <p class="kpi-label">Prestataires</p>
                                    <p class="kpi-value" id="providersCount">3</p>
                                    <p class="kpi-change blue" id="activeProvidersCount">2 actifs</p>
                                </div>
                                <i class="fas fa-store kpi-icon purple"></i>
                            </div>
                        </div>

                        <div class="kpi-card">
                            <div class="kpi-content">
                                <div class="kpi-info">
                                    <p class="kpi-label">Actualités</p>
                                    <p class="kpi-value" id="newsCount">3</p>
                                    <p class="kpi-change blue" id="publishedNewsCount">2 publiées</p>
                                </div>
                                <i class="fas fa-newspaper kpi-icon pink"></i>
                            </div>
                        </div>
                    </div>

                    <div class="activity-card">
                        <h2>Activité récente</h2>
                        <div class="activity-list">
                            <div class="activity-item blue">
                                <i class="fas fa-envelope"></i>
                                <div class="activity-content">
                                    <p class="activity-title">Campagne e-mail "Promotion Été" envoyée</p>
                                    <p class="activity-subtitle">892 destinataires • Il y a 2h</p>
                                </div>
                                <span class="activity-badge">Envoyé</span>
                            </div>

                            <div class="activity-item green">
                                <i class="fas fa-store"></i>
                                <div class="activity-content">
                                    <p class="activity-title">Nouveau prestataire inscrit</p>
                                    <p class="activity-subtitle">PhotoMariage Pro • Il y a 4h</p>
                                </div>
                                <span class="activity-badge outline">En attente</span>
                            </div>

                            <div class="activity-item purple">
                                <i class="fas fa-newspaper"></i>
                                <div class="activity-content">
                                    <p class="activity-title">Nouvelle actualité publiée</p>
                                    <p class="activity-subtitle">Tendances Mariage 2024 • Il y a 6h</p>
                                </div>
                                <span class="activity-badge">Publié</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- News Tab -->
                <div id="newsTab" class="tab-content">
                    <div class="tab-header">
                        <div class="tab-info">
                            <h2>Gestion des actualités</h2>
                            <p>Gérez vos articles et actualités</p>
                        </div>
                        <div class="tab-actions">
                            <button class="btn primary" id="addNewsBtn">
                                <i class="fas fa-plus"></i>
                                Nouvelle actualité
                            </button>
                            <button class="btn outline">
                                <i class="fas fa-download"></i>
                                Exporter
                            </button>
                        </div>
                    </div>

                    <div class="filters-card">
                        <div class="filters">
                            <div class="filter-label">
                                <i class="fas fa-filter"></i>
                                <span>Filtres:</span>
                            </div>
                            <select id="newsFilterCategory" class="filter-select">
                                <option value="all">Toutes les catégories</option>
                                <option value="Tendances">Tendances</option>
                                <option value="Conseils">Conseils</option>
                                <option value="Actualités">Actualités</option>
                                <option value="Promotions">Promotions</option>
                            </select>
                            <select id="newsFilterStatus" class="filter-select">
                                <option value="all">Tous</option>
                                <option value="published">Publié</option>
                                <option value="draft">Brouillon</option>
                            </select>
                            <button class="btn outline small" id="resetNewsFilters">Réinitialiser</button>
                        </div>
                    </div>

                    <div id="newsGrid" class="grid">
                        <!-- News items will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Providers Tab -->
                <div id="providersTab" class="tab-content">
                    <div class="tab-header">
                        <div class="tab-info">
                            <h2>Gestion des prestataires</h2>
                            <p>Gérez vos partenaires prestataires</p>
                        </div>
                        <div class="tab-actions">
                            <button class="btn primary" id="addProviderBtn">
                                <i class="fas fa-plus"></i>
                                Nouveau prestataire
                            </button>
                            <button class="btn outline">
                                <i class="fas fa-download"></i>
                                Exporter
                            </button>
                        </div>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-value" id="totalProviders">3</div>
                            <div class="stat-label">Total prestataires</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value green" id="activeProviders">2</div>
                            <div class="stat-label">Actifs</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value yellow" id="pendingProviders">1</div>
                            <div class="stat-label">En attente</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value red" id="suspendedProviders">0</div>
                            <div class="stat-label">Suspendus</div>
                        </div>
                    </div>

                    <div class="filters-card">
                        <div class="filters">
                            <div class="filter-label">
                                <i class="fas fa-filter"></i>
                                <span>Filtres:</span>
                            </div>
                            <select id="providerFilterCategory" class="filter-select">
                                <option value="all">Toutes les catégories</option>
                                <option value="Photographie">Photographie</option>
                                <option value="Fleuriste">Fleuriste</option>
                                <option value="DJ/Animation">DJ/Animation</option>
                                <option value="Traiteur">Traiteur</option>
                                <option value="Décoration">Décoration</option>
                                <option value="Wedding Planner">Wedding Planner</option>
                            </select>
                            <select id="providerFilterStatus" class="filter-select">
                                <option value="all">Tous</option>
                                <option value="active">Actif</option>
                                <option value="pending">En attente</option>
                                <option value="suspended">Suspendu</option>
                            </select>
                            <select id="providerFilterLocation" class="filter-select">
                                <option value="all">Toutes</option>
                                <option value="Paris">Paris</option>
                                <option value="Lyon">Lyon</option>
                                <option value="Marseille">Marseille</option>
                            </select>
                            <button class="btn outline small" id="resetProviderFilters">Réinitialiser</button>
                        </div>
                    </div>

                    <div id="providersGrid" class="grid">
                        <!-- Provider items will be populated by JavaScript -->
                    </div>
                </div>

                <!-- Other tabs (Users, Emails) -->
                <div id="usersTab" class="tab-content">
                    <h2>Gestion des utilisateurs</h2>
                    <p>Cette section sera développée prochainement.</p>
                </div>

                <div id="emailsTab" class="tab-content">
                    <h2>Gestion des e-mails</h2>
                    <p>Cette section sera développée prochainement.</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <div id="newsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="newsModalTitle">Ajouter une actualité</h3>
                <button class="modal-close" id="closeNewsModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="newsForm">
                    <div class="form-group">
                        <label for="newsTitle">Titre</label>
                        <input type="text" id="newsTitle" placeholder="Titre de l'actualité" required>
                    </div>
                    <div class="form-group">
                        <label for="newsCategory">Catégorie</label>
                        <select id="newsCategory" required>
                            <option value="">Sélectionner une catégorie</option>
                            <option value="Tendances">Tendances</option>
                            <option value="Conseils">Conseils</option>
                            <option value="Actualités">Actualités</option>
                            <option value="Promotions">Promotions</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="newsImage">URL de l'image</label>
                        <input type="url" id="newsImage" placeholder="https://example.com/image.jpg">
                    </div>
                    <div class="form-group">
                        <label for="newsContent">Contenu</label>
                        <textarea id="newsContent" rows="6" placeholder="Contenu de l'actualité..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="newsStatus">Statut</label>
                        <select id="newsStatus">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn primary">Ajouter</button>
                        <button type="button" class="btn outline" id="cancelNewsModal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="providerModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <h3 id="providerModalTitle">Ajouter un prestataire</h3>
                <button class="modal-close" id="closeProviderModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="providerForm">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="providerName">Nom du prestataire *</label>
                            <input type="text" id="providerName" placeholder="Nom de l'entreprise" required>
                        </div>
                        <div class="form-group">
                            <label for="providerEmail">E-mail *</label>
                            <input type="email" id="providerEmail" placeholder="contact@entreprise.fr" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="providerPhone">Téléphone</label>
                            <input type="tel" id="providerPhone" placeholder="+33 1 23 45 67 89">
                        </div>
                        <div class="form-group">
                            <label for="providerWebsite">Site web</label>
                            <input type="url" id="providerWebsite" placeholder="www.entreprise.fr">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="providerCategory">Catégorie *</label>
                            <select id="providerCategory" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Photographie">Photographie</option>
                                <option value="Fleuriste">Fleuriste</option>
                                <option value="DJ/Animation">DJ/Animation</option>
                                <option value="Traiteur">Traiteur</option>
                                <option value="Décoration">Décoration</option>
                                <option value="Wedding Planner">Wedding Planner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="providerLocation">Ville</label>
                            <select id="providerLocation">
                                <option value="">Sélectionner une ville</option>
                                <option value="Paris">Paris</option>
                                <option value="Lyon">Lyon</option>
                                <option value="Marseille">Marseille</option>
                                <option value="Toulouse">Toulouse</option>
                                <option value="Nice">Nice</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="providerAddress">Adresse complète</label>
                        <input type="text" id="providerAddress" placeholder="123 Rue de la Paix, 75001 Paris">
                    </div>
                    <div class="form-group">
                        <label for="providerLogo">URL du logo</label>
                        <input type="url" id="providerLogo" placeholder="https://example.com/logo.jpg">
                    </div>
                    <div class="form-group">
                        <label for="providerDescription">Description</label>
                        <textarea id="providerDescription" rows="3" placeholder="Description des services proposés..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="providerServices">Services (séparés par des virgules)</label>
                        <textarea id="providerServices" rows="2" placeholder="Service 1, Service 2, Service 3..."></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="providerPricing">Tarification</label>
                            <input type="text" id="providerPricing" placeholder="À partir de 1500€">
                        </div>
                        <div class="form-group">
                            <label for="providerStatus">Statut</label>
                            <select id="providerStatus">
                                <option value="pending">En attente</option>
                                <option value="active">Actif</option>
                                <option value="suspended">Suspendu</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn primary">Ajouter le prestataire</button>
                        <button type="button" class="btn outline" id="cancelProviderModal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
