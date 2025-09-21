<?php
require_once 'header.php';

// Récupérer les prestataires pour la liste déroulante
$stmt = $conn->query("SELECT id_prestataire, nom_entreprise FROM prestataire ORDER BY nom_entreprise");
$prestataires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $id_prestataire = intval($_POST['id_prestataire'] ?? 0);
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;
    $imagePath = '';

    // Gestion de l'upload de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/offres/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = uniqid('offre_') . '_' . basename($_FILES['image']['name']);
        $fileDest = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmp, $fileDest)) {
            $imagePath = 'uploads/offres/' . $fileName;
        } else {
            $error = "Erreur lors de l'upload de l'image.";
        }
    }

    if ($titre && $id_prestataire && $date_debut && !$error) {
        $stmt = $conn->prepare("INSERT INTO offre_speciale (titre, description, id_prestataire, date_debut, date_fin, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $description, $id_prestataire, $date_debut, $date_fin, $imagePath]);
        $success = true;
    } elseif (!$error) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<div class="content">
    <h2>Créer une offre spéciale</h2>
    <?php if ($success): ?>
        <div class="alert alert-success">Offre créée avec succès. <a href="offres_admin.php">Retour à la liste</a></div>
    <?php else: ?>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" class="admin-form" enctype="multipart/form-data" style="max-width:500px;">
            <div class="form-group">
                <label>Titre*<br>
                    <input type="text" name="titre" required>
                </label>
            </div>
            <div class="form-group">
                <label>Description<br>
                    <textarea name="description" rows="4"></textarea>
                </label>
            </div>
            <div class="form-group">
                <label>Prestataire*<br>
                    <select name="id_prestataire" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach ($prestataires as $p): ?>
                            <option value="<?php echo $p['id_prestataire']; ?>"><?php echo htmlspecialchars($p['nom_entreprise']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="form-group">
                <label>Date début*<br>
                    <input type="date" name="date_debut" required>
                </label>
            </div>
            <div class="form-group">
                <label>Date fin<br>
                    <input type="date" name="date_fin">
                </label>
            </div>
            <div class="form-group">
                <label>Image (JPG, PNG, max 2 Mo)*<br>
                    <input type="file" name="image" accept="image/*" required>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="offres_admin.php" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>