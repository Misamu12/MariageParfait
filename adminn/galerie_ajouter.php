<?php
require_once 'header.php';

// Liste des prestataires
$stmt = $conn->query("SELECT id_prestataire, nom_entreprise FROM prestataire ORDER BY nom_entreprise");
$prestataires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prestataire = intval($_POST['id_prestataire'] ?? 0);
    $legende = trim($_POST['legende'] ?? '');
    $imagePath = '';

    // Upload image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/galerie/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = uniqid('galerie_') . '_' . basename($_FILES['image']['name']);
        $fileDest = $uploadDir . $fileName;
        if (move_uploaded_file($fileTmp, $fileDest)) {
            $imagePath = 'uploads/galerie/' . $fileName;
        } else {
            $error = "Erreur lors de l'upload de l'image.";
        }
    } else {
        $error = "Image obligatoire.";
    }

    if ($id_prestataire && $imagePath && !$error) {
        $stmt = $conn->prepare("INSERT INTO galerie (id_prestataire, image, legende) VALUES (?, ?, ?)");
        $stmt->execute([$id_prestataire, $imagePath, $legende]);
        $success = true;
    } elseif (!$error) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<div class="content">
    <h2>Ajouter une photo à la galerie</h2>
    <?php if ($success): ?>
        <div class="alert alert-success">Photo ajoutée avec succès. <a href="galerie_admin.php">Retour à la galerie</a></div>
    <?php else: ?>
        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <form method="post" class="admin-form" enctype="multipart/form-data" style="max-width:500px;">
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
                <label>Légende<br>
                    <input type="text" name="legende">
                </label>
            </div>
            <div class="form-group">
                <label>Image (JPG, PNG, max 2 Mo)*<br>
                    <input type="file" name="image" accept="image/*" required>
                </label>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Ajouter</button>
                <a href="galerie_admin.php" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    <?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>