// Application State
let currentUser = null
let isLoggedIn = false
let activeTab = "dashboard"
let sidebarOpen = false

// Data
let newsItems = [
  {
    id: "1",
    title: "Tendances Mariage 2024",
    content: "Découvrez les dernières tendances pour votre mariage de rêve...",
    image: "https://via.placeholder.com/300x200",
    category: "Tendances",
    status: "published",
    date: "2024-01-15",
    author: "Marie Dupont",
  },
  {
    id: "2",
    title: "Conseils pour choisir votre photographe",
    content: "Guide complet pour sélectionner le photographe parfait...",
    image: "https://via.placeholder.com/300x200",
    category: "Conseils",
    status: "published",
    date: "2024-01-10",
    author: "Pierre Martin",
  },
  {
    id: "3",
    title: "Nouveaux prestataires partenaires",
    content: "Nous accueillons de nouveaux prestataires de qualité...",
    image: "https://via.placeholder.com/300x200",
    category: "Actualités",
    status: "draft",
    date: "2024-01-08",
    author: "Sophie Bernard",
  },
]

let providers = [
  {
    id: "1",
    name: "PhotoMariage Pro",
    email: "contact@photomariage.fr",
    phone: "+33 1 23 45 67 89",
    website: "www.photomariage.fr",
    logo: "https://via.placeholder.com/100x100",
    category: "Photographie",
    location: "Paris",
    address: "123 Rue de la Paix, 75001 Paris",
    description: "Photographe professionnel spécialisé dans les mariages avec plus de 10 ans d'expérience.",
    services: ["Photographie de mariage", "Séance engagement", "Album photo", "Retouches"],
    pricing: "À partir de 1500€",
    rating: 4.9,
    reviewsCount: 127,
    status: "active",
    joinDate: "2023-03-15",
    lastActive: "Il y a 2h",
    verified: true,
  },
  {
    id: "2",
    name: "Fleurs & Co",
    email: "info@fleursetco.fr",
    phone: "+33 4 56 78 90 12",
    website: "www.fleursetco.fr",
    logo: "https://via.placeholder.com/100x100",
    category: "Fleuriste",
    location: "Lyon",
    address: "456 Avenue des Fleurs, 69000 Lyon",
    description: "Créations florales uniques pour votre jour J. Spécialiste des bouquets de mariée.",
    services: ["Bouquet de mariée", "Décoration florale", "Centres de table", "Arche florale"],
    pricing: "À partir de 800€",
    rating: 4.7,
    reviewsCount: 89,
    status: "active",
    joinDate: "2023-05-20",
    lastActive: "Il y a 1j",
    verified: true,
  },
  {
    id: "3",
    name: "DJ Events",
    email: "contact@djevents.fr",
    phone: "+33 6 12 34 56 78",
    website: "www.djevents.fr",
    logo: "https://via.placeholder.com/100x100",
    category: "DJ/Animation",
    location: "Marseille",
    address: "789 Boulevard de la Musique, 13000 Marseille",
    description: "Animation musicale professionnelle pour des mariages inoubliables.",
    services: ["DJ mariage", "Éclairage", "Sonorisation", "Animation"],
    pricing: "À partir de 600€",
    rating: 4.5,
    reviewsCount: 156,
    status: "pending",
    joinDate: "2024-01-10",
    lastActive: "Il y a 3j",
    verified: false,
  },
]

// Filter states
let newsFilters = {
  category: "all",
  status: "all",
  search: "",
}

let providerFilters = {
  category: "all",
  status: "all",
  location: "all",
  search: "",
}

// Modal states
let editingNewsId = null
let editingProviderId = null

// DOM Elements
const loginPage = document.getElementById("loginPage")
const dashboard = document.getElementById("dashboard")
const loginForm = document.getElementById("loginForm")
const loginError = document.getElementById("loginError")
const sidebar = document.getElementById("sidebar")
const sidebarToggle = document.getElementById("sidebarToggle")
const userMenuBtn = document.getElementById("userMenuBtn")
const userDropdown = document.getElementById("userDropdown")
const logoutBtn = document.getElementById("logoutBtn")
const pageTitle = document.getElementById("pageTitle")
const searchInput = document.getElementById("searchInput")

// Initialize app
document.addEventListener("DOMContentLoaded", () => {
  checkLoginStatus()
  initializeEventListeners()
  updateCounts()
})

// Check if user is logged in
function checkLoginStatus() {
  const savedUser = localStorage.getItem("adminUser")
  if (savedUser) {
    currentUser = JSON.parse(savedUser)
    isLoggedIn = true
    showDashboard()
  } else {
    showLogin()
  }
}

// Show login page
function showLogin() {
  loginPage.style.display = "flex"
  dashboard.style.display = "none"
}

// Show dashboard
function showDashboard() {
  loginPage.style.display = "none"
  dashboard.style.display = "flex"
  document.getElementById("userName").textContent = currentUser?.name || "Administrateur"
  renderActiveTab()
}

// Initialize event listeners
function initializeEventListeners() {
  // Login form
  loginForm.addEventListener("submit", handleLogin)

  // Sidebar toggle
  sidebarToggle.addEventListener("click", toggleSidebar)

  // Navigation
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.addEventListener("click", () => {
      const tab = item.dataset.tab
      switchTab(tab)
    })
  })

  // User menu
  userMenuBtn.addEventListener("click", toggleUserMenu)
  logoutBtn.addEventListener("click", handleLogout)

  // Search
  searchInput.addEventListener("input", handleSearch)

  // News events
  document.getElementById("addNewsBtn").addEventListener("click", () => openNewsModal())
  document.getElementById("closeNewsModal").addEventListener("click", closeNewsModal)
  document.getElementById("cancelNewsModal").addEventListener("click", closeNewsModal)
  document.getElementById("newsForm").addEventListener("submit", handleNewsSubmit)

  // Provider events
  document.getElementById("addProviderBtn").addEventListener("click", () => openProviderModal())
  document.getElementById("closeProviderModal").addEventListener("click", closeProviderModal)
  document.getElementById("cancelProviderModal").addEventListener("click", closeProviderModal)
  document.getElementById("providerForm").addEventListener("submit", handleProviderSubmit)

  // Filter events
  document.getElementById("newsFilterCategory").addEventListener("change", handleNewsFilter)
  document.getElementById("newsFilterStatus").addEventListener("change", handleNewsFilter)
  document.getElementById("resetNewsFilters").addEventListener("click", resetNewsFilters)

  document.getElementById("providerFilterCategory").addEventListener("change", handleProviderFilter)
  document.getElementById("providerFilterStatus").addEventListener("change", handleProviderFilter)
  document.getElementById("providerFilterLocation").addEventListener("change", handleProviderFilter)
  document.getElementById("resetProviderFilters").addEventListener("click", resetProviderFilters)

  // Close modals when clicking outside
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal")) {
      closeNewsModal()
      closeProviderModal()
    }

    if (!userMenuBtn.contains(e.target)) {
      userDropdown.classList.remove("show")
    }
  })
}

// Handle login
function handleLogin(e) {
  e.preventDefault()

  const email = document.getElementById("email").value
  const password = document.getElementById("password").value

  if (!email || !password) {
    showLoginError("Veuillez remplir tous les champs")
    return
  }

  if (email === "admin@wedding.com" && password === "admin123") {
    currentUser = { email, name: "Administrateur" }
    isLoggedIn = true
    localStorage.setItem("adminUser", JSON.stringify(currentUser))
    showDashboard()
    hideLoginError()
  } else {
    showLoginError("Identifiants incorrects")
  }
}

// Show/hide login error
function showLoginError(message) {
  loginError.textContent = message
  loginError.style.display = "block"
}

function hideLoginError() {
  loginError.style.display = "none"
}

// Handle logout
function handleLogout() {
  isLoggedIn = false
  currentUser = null
  localStorage.removeItem("adminUser")
  activeTab = "dashboard"
  showLogin()
  userDropdown.classList.remove("show")
}

// Toggle sidebar
function toggleSidebar() {
  sidebarOpen = !sidebarOpen
  sidebar.classList.toggle("open", sidebarOpen)
}

// Toggle user menu
function toggleUserMenu() {
  userDropdown.classList.toggle("show")
}

// Switch tab
function switchTab(tab) {
  activeTab = tab

  // Update navigation
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active")
  })
  document.querySelector(`[data-tab="${tab}"]`).classList.add("active")

  // Update page title
  const titles = {
    dashboard: "Dashboard",
    news: "Actualités",
    providers: "Prestataires",
    users: "Utilisateurs",
    emails: "E-mails",
  }
  pageTitle.textContent = titles[tab]

  // Show active tab content
  document.querySelectorAll(".tab-content").forEach((content) => {
    content.classList.remove("active")
  })
  document.getElementById(`${tab}Tab`).classList.add("active")

  renderActiveTab()
}

// Render active tab
function renderActiveTab() {
  switch (activeTab) {
    case "dashboard":
      renderDashboard()
      break
    case "news":
      renderNews()
      break
    case "providers":
      renderProviders()
      break
  }
}

// Render dashboard
function renderDashboard() {
  updateCounts()
}

// Update counts
function updateCounts() {
  const activeProviders = providers.filter((p) => p.status === "active").length
  const publishedNews = newsItems.filter((n) => n.status === "published").length

  document.getElementById("providersCount").textContent = providers.length
  document.getElementById("activeProvidersCount").textContent = `${activeProviders} actifs`
  document.getElementById("newsCount").textContent = newsItems.length
  document.getElementById("publishedNewsCount").textContent = `${publishedNews} publiées`
  document.getElementById("newsBadge").textContent = newsItems.length
  document.getElementById("providersBadge").textContent = providers.length

  // Update provider stats
  const pendingProviders = providers.filter((p) => p.status === "pending").length
  const suspendedProviders = providers.filter((p) => p.status === "suspended").length

  if (document.getElementById("totalProviders")) {
    document.getElementById("totalProviders").textContent = providers.length
    document.getElementById("activeProviders").textContent = activeProviders
    document.getElementById("pendingProviders").textContent = pendingProviders
    document.getElementById("suspendedProviders").textContent = suspendedProviders
  }
}

// Handle search
function handleSearch(e) {
  const searchTerm = e.target.value

  if (activeTab === "news") {
    newsFilters.search = searchTerm
    renderNews()
  } else if (activeTab === "providers") {
    providerFilters.search = searchTerm
    renderProviders()
  }
}

// News functions
function renderNews() {
  const filteredNews = filterNews()
  const newsGrid = document.getElementById("newsGrid")

  if (filteredNews.length === 0) {
    newsGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-newspaper"></i>
                <h3>Aucune actualité trouvée</h3>
                <p>${getNewsEmptyMessage()}</p>
                ${shouldShowAddNewsButton() ? '<button class="btn primary" onclick="openNewsModal()"><i class="fas fa-plus"></i> Créer une actualité</button>' : ""}
            </div>
        `
    return
  }

  newsGrid.innerHTML = filteredNews
    .map(
      (news) => `
        <div class="card">
            <div class="card-image">
                <img src="${news.image}" alt="${news.title}">
                <span class="card-badge ${news.status}">${news.status === "published" ? "Publié" : "Brouillon"}</span>
            </div>
            <div class="card-content">
                <div class="card-meta">
                    <span class="card-category">${news.category}</span>
                    <span class="card-date">${formatDate(news.date)}</span>
                </div>
                <h3 class="card-title">${news.title}</h3>
                <p class="card-description">${news.content}</p>
                <div class="card-footer">
                    <span class="card-author">Par ${news.author}</span>
                    <div class="card-actions">
                        <button class="card-action" onclick="viewNews('${news.id}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="card-action" onclick="editNews('${news.id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="card-action delete" onclick="deleteNews('${news.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

function filterNews() {
  return newsItems.filter((news) => {
    const matchesCategory = newsFilters.category === "all" || news.category === newsFilters.category
    const matchesStatus = newsFilters.status === "all" || news.status === newsFilters.status
    const matchesSearch =
      !newsFilters.search ||
      news.title.toLowerCase().includes(newsFilters.search.toLowerCase()) ||
      news.content.toLowerCase().includes(newsFilters.search.toLowerCase())

    return matchesCategory && matchesStatus && matchesSearch
  })
}

function getNewsEmptyMessage() {
  if (newsFilters.search || newsFilters.category !== "all" || newsFilters.status !== "all") {
    return "Aucune actualité ne correspond à vos critères de recherche."
  }
  return "Commencez par créer votre première actualité."
}

function shouldShowAddNewsButton() {
  return !newsFilters.search && newsFilters.category === "all" && newsFilters.status === "all"
}

function handleNewsFilter() {
  newsFilters.category = document.getElementById("newsFilterCategory").value
  newsFilters.status = document.getElementById("newsFilterStatus").value
  renderNews()
}

function resetNewsFilters() {
  newsFilters = { category: "all", status: "all", search: "" }
  document.getElementById("newsFilterCategory").value = "all"
  document.getElementById("newsFilterStatus").value = "all"
  searchInput.value = ""
  renderNews()
}

function openNewsModal(newsId = null) {
  editingNewsId = newsId
  const modal = document.getElementById("newsModal")
  const title = document.getElementById("newsModalTitle")
  const form = document.getElementById("newsForm")

  if (newsId) {
    const news = newsItems.find((n) => n.id === newsId)
    title.textContent = "Modifier l'actualité"
    document.getElementById("newsTitle").value = news.title
    document.getElementById("newsCategory").value = news.category
    document.getElementById("newsImage").value = news.image
    document.getElementById("newsContent").value = news.content
    document.getElementById("newsStatus").value = news.status
  } else {
    title.textContent = "Ajouter une actualité"
    form.reset()
  }

  modal.classList.add("show")
}

function closeNewsModal() {
  document.getElementById("newsModal").classList.remove("show")
  editingNewsId = null
}

function handleNewsSubmit(e) {
  e.preventDefault()

  const formData = {
    title: document.getElementById("newsTitle").value,
    category: document.getElementById("newsCategory").value,
    image: document.getElementById("newsImage").value || "https://via.placeholder.com/300x200",
    content: document.getElementById("newsContent").value,
    status: document.getElementById("newsStatus").value,
  }

  if (editingNewsId) {
    // Update existing news
    const index = newsItems.findIndex((n) => n.id === editingNewsId)
    newsItems[index] = { ...newsItems[index], ...formData }
  } else {
    // Add new news
    const newNews = {
      id: Date.now().toString(),
      ...formData,
      date: new Date().toISOString().split("T")[0],
      author: "Admin",
    }
    newsItems.unshift(newNews)
  }

  closeNewsModal()
  renderNews()
  updateCounts()
}

function viewNews(id) {
  const news = newsItems.find((n) => n.id === id)
  alert(`Titre: ${news.title}\n\nContenu: ${news.content}`)
}

function editNews(id) {
  openNewsModal(id)
}

function deleteNews(id) {
  if (confirm("Êtes-vous sûr de vouloir supprimer cette actualité ?")) {
    newsItems = newsItems.filter((n) => n.id !== id)
    renderNews()
    updateCounts()
  }
}

// Provider functions
function renderProviders() {
  const filteredProviders = filterProviders()
  const providersGrid = document.getElementById("providersGrid")

  if (filteredProviders.length === 0) {
    providersGrid.innerHTML = `
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-store"></i>
                <h3>Aucun prestataire trouvé</h3>
                <p>${getProvidersEmptyMessage()}</p>
                ${shouldShowAddProviderButton() ? '<button class="btn primary" onclick="openProviderModal()"><i class="fas fa-plus"></i> Ajouter un prestataire</button>' : ""}
            </div>
        `
    return
  }

  providersGrid.innerHTML = filteredProviders
    .map(
      (provider) => `
        <div class="card">
            <div class="provider-card-header">
                <img src="${provider.logo}" alt="${provider.name}" class="provider-logo">
                <span class="card-badge ${provider.status}">${getStatusLabel(provider.status)}</span>
                ${provider.verified ? '<span class="card-badge verified" style="top: 0.5rem; left: 0.5rem;">Vérifié</span>' : ""}
            </div>
            <div class="card-content">
                <div class="card-meta">
                    <span class="card-category">
                        <i class="${getCategoryIcon(provider.category)}"></i>
                        ${provider.category}
                    </span>
                    ${
                      provider.rating > 0
                        ? `
                        <div class="card-rating">
                            <i class="fas fa-star"></i>
                            <span>${provider.rating}</span>
                            <span class="text-gray-500">(${provider.reviewsCount})</span>
                        </div>
                    `
                        : ""
                    }
                </div>
                <h3 class="card-title">${provider.name}</h3>
                <div class="card-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>${provider.location}</span>
                </div>
                <p class="card-description">${provider.description}</p>
                <div class="card-info">
                    <div class="card-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>${provider.email}</span>
                    </div>
                    ${
                      provider.phone
                        ? `
                        <div class="card-info-item">
                            <i class="fas fa-phone"></i>
                            <span>${provider.phone}</span>
                        </div>
                    `
                        : ""
                    }
                    ${
                      provider.website
                        ? `
                        <div class="card-info-item">
                            <i class="fas fa-globe"></i>
                            <span>${provider.website}</span>
                        </div>
                    `
                        : ""
                    }
                </div>
                ${
                  provider.services.length > 0
                    ? `
                    <div class="card-services">
                        <div class="card-services-label">Services:</div>
                        <div class="card-services-list">
                            ${provider.services
                              .slice(0, 3)
                              .map(
                                (service) => `
                                <span class="service-badge">${service}</span>
                            `,
                              )
                              .join("")}
                            ${provider.services.length > 3 ? `<span class="service-badge">+${provider.services.length - 3}</span>` : ""}
                        </div>
                    </div>
                `
                    : ""
                }
                ${provider.pricing ? `<div class="card-pricing">${provider.pricing}</div>` : ""}
                <div class="card-footer">
                    <span class="card-author">Inscrit le ${formatDate(provider.joinDate)}</span>
                    <div class="card-actions">
                        <button class="card-action" onclick="viewProvider('${provider.id}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="card-action" onclick="editProvider('${provider.id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="card-action" onclick="changeProviderStatus('${provider.id}')">
                            <i class="fas fa-cog"></i>
                        </button>
                        <button class="card-action delete" onclick="deleteProvider('${provider.id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

function filterProviders() {
  return providers.filter((provider) => {
    const matchesCategory = providerFilters.category === "all" || provider.category === providerFilters.category
    const matchesStatus = providerFilters.status === "all" || provider.status === providerFilters.status
    const matchesLocation = providerFilters.location === "all" || provider.location === providerFilters.location
    const matchesSearch =
      !providerFilters.search ||
      provider.name.toLowerCase().includes(providerFilters.search.toLowerCase()) ||
      provider.description.toLowerCase().includes(providerFilters.search.toLowerCase()) ||
      provider.services.some((service) => service.toLowerCase().includes(providerFilters.search.toLowerCase()))

    return matchesCategory && matchesStatus && matchesLocation && matchesSearch
  })
}

function getProvidersEmptyMessage() {
  if (
    providerFilters.search ||
    providerFilters.category !== "all" ||
    providerFilters.status !== "all" ||
    providerFilters.location !== "all"
  ) {
    return "Aucun prestataire ne correspond à vos critères de recherche."
  }
  return "Commencez par ajouter votre premier prestataire."
}

function shouldShowAddProviderButton() {
  return (
    !providerFilters.search &&
    providerFilters.category === "all" &&
    providerFilters.status === "all" &&
    providerFilters.location === "all"
  )
}

function handleProviderFilter() {
  providerFilters.category = document.getElementById("providerFilterCategory").value
  providerFilters.status = document.getElementById("providerFilterStatus").value
  providerFilters.location = document.getElementById("providerFilterLocation").value
  renderProviders()
}

function resetProviderFilters() {
  providerFilters = { category: "all", status: "all", location: "all", search: "" }
  document.getElementById("providerFilterCategory").value = "all"
  document.getElementById("providerFilterStatus").value = "all"
  document.getElementById("providerFilterLocation").value = "all"
  searchInput.value = ""
  renderProviders()
}

function openProviderModal(providerId = null) {
  editingProviderId = providerId
  const modal = document.getElementById("providerModal")
  const title = document.getElementById("providerModalTitle")
  const form = document.getElementById("providerForm")

  if (providerId) {
    const provider = providers.find((p) => p.id === providerId)
    title.textContent = "Modifier le prestataire"
    document.getElementById("providerName").value = provider.name
    document.getElementById("providerEmail").value = provider.email
    document.getElementById("providerPhone").value = provider.phone
    document.getElementById("providerWebsite").value = provider.website
    document.getElementById("providerCategory").value = provider.category
    document.getElementById("providerLocation").value = provider.location
    document.getElementById("providerAddress").value = provider.address
    document.getElementById("providerLogo").value = provider.logo
    document.getElementById("providerDescription").value = provider.description
    document.getElementById("providerServices").value = provider.services.join(", ")
    document.getElementById("providerPricing").value = provider.pricing
    document.getElementById("providerStatus").value = provider.status
  } else {
    title.textContent = "Ajouter un prestataire"
    form.reset()
  }

  modal.classList.add("show")
}

function closeProviderModal() {
  document.getElementById("providerModal").classList.remove("show")
  editingProviderId = null
}

function handleProviderSubmit(e) {
  e.preventDefault()

  const formData = {
    name: document.getElementById("providerName").value,
    email: document.getElementById("providerEmail").value,
    phone: document.getElementById("providerPhone").value,
    website: document.getElementById("providerWebsite").value,
    category: document.getElementById("providerCategory").value,
    location: document.getElementById("providerLocation").value,
    address: document.getElementById("providerAddress").value,
    logo: document.getElementById("providerLogo").value || "https://via.placeholder.com/100x100",
    description: document.getElementById("providerDescription").value,
    services: document
      .getElementById("providerServices")
      .value.split(",")
      .map((s) => s.trim())
      .filter((s) => s),
    pricing: document.getElementById("providerPricing").value,
    status: document.getElementById("providerStatus").value,
  }

  if (editingProviderId) {
    // Update existing provider
    const index = providers.findIndex((p) => p.id === editingProviderId)
    providers[index] = { ...providers[index], ...formData }
  } else {
    // Add new provider
    const newProvider = {
      id: Date.now().toString(),
      ...formData,
      rating: 0,
      reviewsCount: 0,
      joinDate: new Date().toISOString().split("T")[0],
      lastActive: "Jamais connecté",
      verified: false,
    }
    providers.unshift(newProvider)
  }

  closeProviderModal()
  renderProviders()
  updateCounts()
}

function viewProvider(id) {
  const provider = providers.find((p) => p.id === id)
  alert(`Nom: ${provider.name}\n\nDescription: ${provider.description}\n\nServices: ${provider.services.join(", ")}`)
}

function editProvider(id) {
  openProviderModal(id)
}

function changeProviderStatus(id) {
  const provider = providers.find((p) => p.id === id)
  const newStatus = prompt(
    `Changer le statut de ${provider.name}:\n\n1. active\n2. pending\n3. suspended\n\nEntrez le nouveau statut:`,
  )

  if (newStatus && ["active", "pending", "suspended"].includes(newStatus)) {
    provider.status = newStatus
    renderProviders()
    updateCounts()
  }
}

function deleteProvider(id) {
  if (confirm("Êtes-vous sûr de vouloir supprimer ce prestataire ?")) {
    providers = providers.filter((p) => p.id !== id)
    renderProviders()
    updateCounts()
  }
}

// Utility functions
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString("fr-FR")
}

function getStatusLabel(status) {
  const labels = {
    active: "Actif",
    pending: "En attente",
    suspended: "Suspendu",
    published: "Publié",
    draft: "Brouillon",
  }
  return labels[status] || status
}

function getCategoryIcon(category) {
  const icons = {
    Photographie: "fas fa-camera",
    Fleuriste: "fas fa-seedling",
    "DJ/Animation": "fas fa-music",
    Traiteur: "fas fa-utensils",
    Décoration: "fas fa-palette",
    "Wedding Planner": "fas fa-heart",
  }
  return icons[category] || "fas fa-store"
}
