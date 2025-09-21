<?php
require_once 'header.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Récupérer l'offre
$stmt = $conn->prepare("SELECT * FROM offre_speciale WHERE id_offre = ?");
$stmt->execute([$id]);
$offre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offre) {
    echo "<div class='alert alert-danger'>Offre introuvable.</div>";
    require_once 'footer.php';
    exit;
}

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
    $imagePath = $offre['image'];

    // Gestion de l'upload de l'image (optionnel)
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
        $stmt = $conn->prepare("UPDATE offre_speciale SET titre=?, description=?, id_prestataire=?, date_debut=?, date_fin=?, image=? WHERE id_offre=?");
        $stmt->execute([$titre, $description, $id_prestataire, $date_debut, $date_fin, $imagePath, $id]);
        $success = true;
        // Refresh data
        $stmt = $conn->prepare("SELECT * FROM offre_speciale WHERE id_offre = ?");
        $stmt->execute([$id]);
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (!$error) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>
<div class="content">
    <h2>Modifier l'offre spéciale</h2>
    <?php if ($success): ?>
        <div class="alert alert-success">Offre modifiée avec succès. <a href="offres_admin.php">Retour à la liste</a></div>
    <?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <form method="post" class="admin-form" enctype="multipart/form-data" style="max-width:500px;">
        <div class="form-group">
            <label>Titre*<br>
                <input type="text" name="titre" value="<?php echo htmlspecialchars($offre['titre']); ?>" required>
            </label>
        </div>
        <div class="form-group">
            <label>Description<br>
                <textarea name="description" rows="4"><?php echo htmlspecialchars($offre['description']); ?></textarea>
            </label>
        </div>
        <div class="form-group">
            <label>Prestataire*<br>
                <select name="id_prestataire" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($prestataires as $p): ?>
                        <option value="<?php echo $p['id_prestataire']; ?>" <?php if ($offre['id_prestataire'] == $p['id_prestataire']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($p['nom_entreprise']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>
        <div class="form-group">
            <label>Date début*<br>
                <input type="date" name="date_debut" value="<?php echo htmlspecialchars($offre['date_debut']); ?>" required>
            </label>
        </div>
        <div class="form-group">
            <label>Date fin<br>
                <input type="date" name="date_fin" value="<?php echo htmlspecialchars($offre['date_fin']); ?>">
            </label>
        </div>
        <div class="form-group">
            <label>Image actuelle<br>
                <?php if (!empty($offre['image'])): ?>
                    <img src="../<?php echo htmlspecialchars($offre['image']); ?>" alt="Image offre" style="max-width:150px;display:block;margin-bottom:8px;">
                <?php else: ?>
                    <span>Aucune image</span>
                <?php endif; ?>
            </label>
        </div>
        <div class="form-group">
            <label>Changer l'image (optionnel)<br>
                <input type="file" name="image" accept="image/*">
            </label>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="offres_admin.php" class="btn btn-outline">Annuler</a>
        </div>
    </form>
</div>
<?php require_once 'footer.php'; ?>