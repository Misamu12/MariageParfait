<?php
require_once 'header.php';

$erreur = "";
$message = "";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: utilisateurs.php");
    exit();
}

$id = $_GET['id'];

// Récupération des données de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
$stmt->execute([$id]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$utilisateur) {
    header("Location: utilisateurs.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $type_utilisateur = $_POST['type_utilisateur'];
    
    try {
        // Si un nouveau mot de passe est fourni, on le met à jour
        if (!empty($_POST['mot_de_passe'])) {
            $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, mot_de_passe = ?, type_utilisateur = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nom, $prenom, $email, $mot_de_passe, $type_utilisateur, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, type_utilisateur = ? WHERE id_utilisateur = ?");
            $stmt->execute([$nom, $prenom, $email, $type_utilisateur, $id]);
        }
        
        $message = "Utilisateur modifié avec succès";
        
        // Mise à jour des données affichées
        $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE id_utilisateur = ?");
        $stmt->execute([$id]);
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la modification: " . $e->getMessage();
    }
}
?>

<h2>Modifier un Utilisateur</h2>

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
            <input type="text" id="nom" name="nom" value="<?php echo $utilisateur['nom']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?php echo $utilisateur['prenom']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $utilisateur['email']; ?>" required>
        </div>
        
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe (laisser vide pour ne pas modifier)</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe">
        </div>
        
        <div class="form-group">
            <label for="type_utilisateur">Type d'utilisateur</label>
            <select id="type_utilisateur" name="type_utilisateur">
                <option value="user" <?php echo $utilisateur['type_utilisateur'] == 'user' ? 'selected' : ''; ?>>Utilisateur</option>
                <option value="prestataire" <?php echo $utilisateur['type_utilisateur'] == 'prestataire' ? 'selected' : ''; ?>>Prestataire</option>
                <option value="admin" <?php echo $utilisateur['type_utilisateur'] == 'admin' ? 'selected' : ''; ?>>Administrateur</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="utilisateurs.php" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php require_once 'footer.php'; ?>