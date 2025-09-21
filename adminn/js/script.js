// Fonction pour afficher/masquer le menu déroulant du profil utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const userProfile = document.querySelector('.user-profile');
    
    if (userProfile) {
        userProfile.addEventListener('click', function() {
            // Ici, vous pouvez ajouter le code pour afficher un menu déroulant
            console.log('Profil utilisateur cliqué');
        });
    }
    
    // Gestion des notifications
    const notifications = document.querySelector('.notifications');
    
    if (notifications) {
        notifications.addEventListener('click', function() {
            // Ici, vous pouvez ajouter le code pour afficher les notifications
            console.log('Notifications cliquées');
        });
    }
});

// Fonction pour confirmer la suppression
function confirmerSuppression(message) {
    return confirm(message || 'Êtes-vous sûr de vouloir supprimer cet élément?');
}