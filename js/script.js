// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    // Éléments du DOM
    const header = document.getElementById('header');
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const themeToggle = document.getElementById('theme-toggle');
    const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
    const faqItems = document.querySelectorAll('.faq-item');
    
    // Vérifier si le mode sombre est activé dans le localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }
    
    // Fonction pour gérer le scroll de la page
    function handleScroll() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    
    // Fonction pour basculer le menu mobile
    function toggleMobileMenu() {
        menuToggle.classList.toggle('active');
        mobileMenu.classList.toggle('active');
        
        // Empêcher le défilement du body quand le menu est ouvert
        if (mobileMenu.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
    /*
    // Fonction pour basculer le mode sombre
    function toggleDarkMode() {
        document.body.classList.toggle('dark-mode');
        
        // Sauvegarder la préférence dans le localStorage
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    }
    */
    
    // Fonction pour gérer les FAQ accordéons
    function toggleFaqItem() {
        const isActive = this.classList.contains('active');
        
        // Fermer tous les items actifs
        faqItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Si l'item n'était pas actif, l'ouvrir
        if (!isActive) {
            this.classList.add('active');
        }
    }
    
    // Ajouter les écouteurs d'événements
    window.addEventListener('scroll', handleScroll);
    menuToggle.addEventListener('click', toggleMobileMenu);
    themeToggle.addEventListener('click', toggleDarkMode);
    mobileThemeToggle.addEventListener('click', toggleDarkMode);
    
    // Fermer le menu mobile quand on clique sur un lien
    document.querySelectorAll('.mobile-nav-link').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('active');
            menuToggle.classList.remove('active');
            document.body.style.overflow = '';
        });
    });
    
    // Ajouter les écouteurs pour les FAQ
    faqItems.forEach(item => {
        item.querySelector('.faq-question').addEventListener('click', function() {
            toggleFaqItem.call(item);
        });
    });
    
    // Animation au défilement
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observer les sections pour les animations au défilement
    document.querySelectorAll('.section').forEach(section => {
        observer.observe(section);
    });
    
    // Ajouter la classe animate-on-scroll aux éléments à animer
    document.querySelectorAll('.category-card, .news-card, .offer-card, .testimonial-card').forEach(element => {
        element.classList.add('animate-on-scroll');
        observer.observe(element);
    });
    
    // Appliquer le style scrolled au header si la page est déjà scrollée au chargement
    handleScroll();
    
    // Animations CSS pour les éléments avec la classe animate-on-scroll
    document.head.insertAdjacentHTML('beforeend', `
        <style>
            .animate-on-scroll {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            
            .animate-on-scroll.animate {
                opacity: 1;
                transform: translateY(0);
            }
            
            .section {
                opacity: 0;
                transform: translateY(20px);
                transition: opacity 0.6s ease, transform 0.6s ease;
            }
            
            .section.animate {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    `);
});

// Fonction pour gérer le responsive design
function handleResponsive() {
    const mainNav = document.querySelector('.main-nav');
    
    if (window.innerWidth >= 768) {
        mainNav.style.display = 'block';
    } else {
        mainNav.style.display = 'none';
    }
}

// Exécuter la fonction au chargement et au redimensionnement
window.addEventListener('load', handleResponsive);
window.addEventListener('resize', handleResponsive);

// Fonction pour simuler le chargement des images (à remplacer par de vraies images)
function loadPlaceholderImages() {
    const placeholders = document.querySelectorAll('img[src*="placeholder"]');
    
    placeholders.forEach((img, index) => {
        // Générer des couleurs aléatoires pour les placeholders
        const hue = (index * 40) % 360;
        const color = `hsl(${hue}, 70%, 80%)`;
        const darkColor = `hsl(${hue}, 70%, 60%)`;
        
        // Créer un canvas pour le placeholder
        const canvas = document.createElement('canvas');
        const width = img.getAttribute('width') || 800;
        const height = img.getAttribute('height') || 600;
        
        canvas.width = width;
        canvas.height = height;
        
        const ctx = canvas.getContext('2d');
        
        // Dessiner un dégradé
        const gradient = ctx.createLinearGradient(0, 0, width, height);
        gradient.addColorStop(0, color);
        gradient.addColorStop(1, darkColor);
        
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, width, height);
        
        // Ajouter un texte
        ctx.fillStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.font = '20px Arial';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Image', width / 2, height / 2);
        
        // Remplacer l'image par le canvas
        img.src = canvas.toDataURL();
    });
}

// Charger les images placeholder
window.addEventListener('load', loadPlaceholderImages);