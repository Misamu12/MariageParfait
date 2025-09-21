<?php
require_once 'header.php';

// Traitement de l'ajout d'une publicité
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $nom_client = $_POST['nom_client'] ?? '';
    $email_client = $_POST['email_client'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    // Gestion de l'upload vidéo
    if (!empty($_FILES['video_file']['name'])) {
        $targetDir = "../uploads/publicites/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $fileName = uniqid('pub_') . '_' . basename($_FILES['video_file']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowed = ['mp4','webm','ogg','mov','avi','mkv'];

        if (in_array($fileType, $allowed) && $_FILES['video_file']['size'] <= 100*1024*1024) { // 100 Mo max
            if (move_uploaded_file($_FILES['video_file']['tmp_name'], $targetFile)) {
                $video_url = "uploads/publicites/" . $fileName;
            } else {
                $error = "Erreur lors de l'upload de la vidéo.";
            }
        } else {
            $error = "Format ou taille de vidéo non autorisé.";
        }
    } else {
        $error = "Veuillez sélectionner une vidéo.";
    }

    if ($titre && !empty($video_url) && $date_debut && $date_fin && !$error) {
        $stmt = $conn->prepare("INSERT INTO publicite (titre, video_url, nom_client, email_client, date_debut, date_fin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$titre, $video_url, $nom_client, $email_client, $date_debut, $date_fin]);
        $success = true;
    } elseif (!$error) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}

// Suppression d'une publicité
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->prepare("DELETE FROM publicite WHERE id_publicite = ?")->execute([$id]);
    header("Location: publicite.php?deleted=1");
    exit;
}

// Liste des publicités
$pubs = $conn->query("SELECT * FROM publicite ORDER BY date_creation DESC")->fetchAll();
?>
<div class="content">
    <h2>Gestion des publicités vidéo</h2>

    <!-- Bouton pour afficher le formulaire -->
    <button id="show-form-btn" class="btn btn-primary" style="margin-bottom:20px;">
        <i class="fas fa-plus"></i> Ajouter une publicité
    </button>

    <!-- Formulaire caché par défaut -->
    <div id="publicite-form-block" style="display:none;max-width:500px;margin-bottom:30px;">
        <form method="post" enctype="multipart/form-data" class="admin-form" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px #0001;">
            <h3 style="margin-top:0;">Nouvelle publicité</h3>
            <label for="titre">Titre* :</label>
            <input type="text" name="titre" id="titre" required>
            <label for="video_url">URL de la vidéo* :</label>
            <input type="text" name="video_url" id="video_url" required placeholder="Lien ou chemin de la vidéo">
            <label for="video_file">Vidéo* :</label>
            <input type="file" name="video_file" id="video_file" accept="video/*" required>
            <label for="nom_client">Nom du client :</label>
            <input type="text" name="nom_client" id="nom_client">
            <label for="email_client">Email du client :</label>
            <input type="email" name="email_client" id="email_client">
            <div style="display:flex;gap:10px;">
                <div style="flex:1;">
                    <label for="date_debut">Date de début* :</label>
                    <input type="date" name="date_debut" id="date_debut" required>
                </div>
                <div style="flex:1;">
                    <label for="date_fin">Date de fin* :</label>
                    <input type="date" name="date_fin" id="date_fin" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success" style="margin-top:15px;width:100%;">Créer la publicité</button>
        </form>
        <?php if ($success): ?>
            <div class="notification notification-success">Publicité ajoutée avec succès !</div>
        <?php elseif ($error): ?>
            <div class="notification notification-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </div>

    <h2 style="margin-top:40px;">Liste des publicités</h2>
    <?php if (isset($_GET['deleted'])): ?>
        <div class="notification notification-success">Publicité supprimée.</div>
    <?php endif; ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Vidéo</th>
                <th>Client</th>
                <th>Email</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Créée le</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($pubs as $pub): ?>
            <tr>
                <td><?= $pub['id_publicite'] ?></td>
                <td><?= htmlspecialchars($pub['titre']) ?></td>
                <td>
                    <?php if ($pub['video_url']): ?>
                        <video src="<?= htmlspecialchars($pub['video_url']) ?>" width="120" controls style="border-radius:6px;box-shadow:0 1px 4px #0002;"></video>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($pub['nom_client']) ?></td>
                <td><?= htmlspecialchars($pub['email_client']) ?></td>
                <td><?= htmlspecialchars($pub['date_debut']) ?></td>
                <td><?= htmlspecialchars($pub['date_fin']) ?></td>
                <td><?= htmlspecialchars($pub['date_creation']) ?></td>
                <td>
                    <a href="publicite.php?delete=<?= $pub['id_publicite'] ?>" onclick="return confirm('Supprimer cette publicité ?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
document.getElementById('show-form-btn').onclick = function() {
    var formBlock = document.getElementById('publicite-form-block');
    formBlock.style.display = (formBlock.style.display === 'none' || formBlock.style.display === '') ? 'block' : 'none';
};
</script>
</body>
</html>