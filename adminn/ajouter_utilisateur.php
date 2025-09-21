<?php
require_once 'header.php';

$erreur = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $type_utilisateur = $_POST['type_utilisateur'];
    
    try {
        $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $type_utilisateur]);
        $message = "Utilisateur ajouté avec succès";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de l'ajout: " . $e->getMessage();
    }
}
?>

<h2>Ajouter un Utilisateur</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($erreur): ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="post" action="">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        
        <div class="form-group">
            <label for="type_utilisateur">Type d'utilisateur</label>
            <select id="type_utilisateur" name="type_utilisateur">
                <option value="user">Utilisateur</option>
                <option value="prestataire">Prestataire</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="utilisateurs.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>