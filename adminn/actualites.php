<?php
require_once 'header.php';

// Traitement de la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    try {
        $stmt = $conn->prepare("DELETE FROM actualite WHERE id_actualite = ?");
        $stmt->execute([$id]);
        $message = "Actualité supprimée avec succès";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Récupération des actualités
$stmt = $conn->query("SELECT * FROM actualite ORDER BY date_publication DESC");
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des Actualités</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($erreur)): ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php endif; ?>

<div class="actions">
    <button type="button" class="btn btn-primary" id="openAddActualite">
        <i class="fas fa-plus"></i> Ajouter une actualité
    </button>
</div>

<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Image</th>
                <th>Date de publication</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actualites as $actualite): ?>
            <tr>
                <td><?php echo $actualite['id_actualite']; ?></td>
                <td><?php echo $actualite['titre']; ?></td>
                <td>
                    <?php if ($actualite['image']): ?>
                        <img src="../images/<?php echo $actualite['image']; ?>" alt="<?php echo $actualite['titre']; ?>" width="50">
                    <?php else: ?>
                        <span>Pas d'image</span>
                    <?php endif; ?>
                </td>
                <td><?php echo $actualite['date_publication']; ?></td>
                <td class="actions">
                    <a href="modifier_actualite.php?id=<?php echo $actualite['id_actualite']; ?>" class="btn btn-sm btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="actualites.php?supprimer=<?php echo $actualite['id_actualite']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
                <td class="contenu-cache" style="display:none;"><?php echo htmlspecialchars($actualite['contenu']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Formulaire modal d'ajout d'actualité -->
<div id="addActualiteModal"  style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:400px;width:90%;position:relative;">
        <button type="button" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;" onclick="closeModal()">&times;</button>
        <h3>Ajouter une actualité</h3>
        <form id="addActualiteForm" enctype="multipart/form-data">
            <label>Titre</label>
            <input type="text" name="titre" required>
            <label>Contenu</label>
            <textarea name="contenu" required></textarea>
            <label>Image</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </form>
        <div id="addActualiteMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<!-- Modal de modification d'actualité -->
<div id="editActualiteModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:400px;width:90%;position:relative;">
        <button type="button" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;" onclick="closeEditModal()">&times;</button>
        <h3>Modifier une actualité</h3>
        <form id="editActualiteForm" enctype="multipart/form-data">
            <input type="hidden" name="id_actualite" id="edit_id_actualite">
            <label>Titre</label>
            <input type="text" name="titre" id="edit_titre" required>
            <label>Contenu</label>
            <textarea name="contenu" id="edit_contenu" required></textarea>
            <label>Image (laisser vide pour ne pas changer)</label>
            <input type="file" name="image" accept="image/*">
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
        <div id="editActualiteMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteConfirmModal"  style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:350px;width:90%;position:relative;text-align:center;">
        <button type="button" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;" onclick="closeDeleteModal()">&times;</button>
        <h3>Confirmation</h3>
        <p>Êtes-vous sûr de vouloir supprimer cette actualité&nbsp;?</p>
        <div style="margin-top:1.5rem;">
            <button id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
            <button type="button" onclick="closeDeleteModal()" class="btn btn-outline" style="margin-left:1rem;">Annuler</button>
        </div>
        <div id="deleteMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<script>
// Ouvre le modal
document.getElementById('openAddActualite').onclick = function(e) {
    e.preventDefault();
    document.getElementById('addActualiteModal').style.display = 'flex';
};
function closeModal() {
    document.getElementById('addActualiteModal').style.display = 'none';
    document.getElementById('addActualiteForm').reset();
    document.getElementById('addActualiteMsg').innerHTML = '';
}

// Soumission AJAX du formulaire d'ajout
document.getElementById('addActualiteForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('ajouter_actualite.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('addActualiteMsg').innerHTML = '<span style="color:green;">Actualité ajoutée !</span>';
            // Ajoute la nouvelle actualité dans le tableau sans recharger
            let tbody = document.querySelector('.data-table tbody');
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${data.actualite.id_actualite}</td>
                <td>${data.actualite.titre}</td>
                <td>${data.actualite.image ? `<img src="uploads/actualites/${data.actualite.image}" width="50">` : 'Pas d\'image'}</td>
                <td>${data.actualite.date_publication}</td>
                <td class="actions">
                    <a href="modifier_actualite.php?id=${data.actualite.id_actualite}" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                    <a href="actualites.php?supprimer=${data.actualite.id_actualite}" class="btn btn-sm btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette actualité?')"><i class="fas fa-trash"></i></a>
                </td>
            `;
            tbody.prepend(tr);
            setTimeout(closeModal, 1200);
        } else {
            document.getElementById('addActualiteMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('addActualiteMsg').innerHTML = '<span style="color:red;">Erreur lors de l\'ajout.</span>';
    });
};

// Ouvre le modal de modification et pré-remplit les champs
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.onclick = function(e) {
        e.preventDefault();
        let tr = this.closest('tr');
        document.getElementById('edit_id_actualite').value = tr.children[0].textContent;
        document.getElementById('edit_titre').value = tr.children[1].textContent;
        document.getElementById('edit_contenu').value = tr.querySelector('.contenu-cache') ? tr.querySelector('.contenu-cache').textContent : '';
        document.getElementById('editActualiteModal').style.display = 'flex';
    };
});
function closeEditModal() {
    document.getElementById('editActualiteModal').style.display = 'none';
    document.getElementById('editActualiteForm').reset();
    document.getElementById('editActualiteMsg').innerHTML = '';
}

// Soumission AJAX du formulaire de modification
document.getElementById('editActualiteForm').onsubmit = function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    fetch('modifier_actualite.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('editActualiteMsg').innerHTML = '<span style="color:green;">Actualité modifiée !</span>';
            // Met à jour la ligne dans le tableau
            let tbody = document.querySelector('.data-table tbody');
            let tr = Array.from(tbody.children).find(row => row.children[0].textContent == data.actualite.id_actualite);
            if(tr) {
                tr.children[1].textContent = data.actualite.titre;
                tr.querySelector('.contenu-cache').textContent = data.actualite.contenu;
                if(data.actualite.image) {
                    tr.children[2].innerHTML = `<img src="uploads/actualites/${data.actualite.image}" width="50">`;
                }
                tr.children[3].textContent = data.actualite.date_publication;
            }
            setTimeout(closeEditModal, 1200);
        } else {
            document.getElementById('editActualiteMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('editActualiteMsg').innerHTML = '<span style="color:red;">Erreur lors de la modification.</span>';
    });
};

let deleteActualiteId = null;
let deleteTr = null;

// Ouvre le modal de suppression
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = function(e) {
        e.preventDefault();
        let tr = this.closest('tr');
        deleteActualiteId = tr.children[0].textContent;
        deleteTr = tr;
        document.getElementById('deleteMsg').innerHTML = '';
        document.getElementById('deleteConfirmModal').style.display = 'flex';
    };
});

function closeDeleteModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
    deleteActualiteId = null;
    deleteTr = null;
}

// Suppression AJAX
document.getElementById('confirmDeleteBtn').onclick = function() {
    if(!deleteActualiteId) return;
    fetch('supprimer_actualite.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id='+encodeURIComponent(deleteActualiteId)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('deleteMsg').innerHTML = '<span style="color:green;">Actualité supprimée !</span>';
            // Retire la ligne du tableau
            let tbody = document.querySelector('.data-table tbody');
            if(deleteTr) {
                tbody.removeChild(deleteTr);
            }
            setTimeout(closeDeleteModal, 1200);
        } else {
            document.getElementById('deleteMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('deleteMsg').innerHTML = '<span style="color:red;">Erreur lors de la suppression.</span>';
    });
};
</script>

<?php require_once 'footer.php'; ?>