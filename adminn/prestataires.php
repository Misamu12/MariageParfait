<?php
require_once 'header.php';

// Traitement de la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    try {
        $stmt = $conn->prepare("DELETE FROM prestataire WHERE id_prestataire = ?");
        $stmt->execute([$id]);
        $message = "Prestataire supprimé avec succès";
    } catch (PDOException $e) {
        $erreur = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

// Récupération des prestataires
$stmt = $conn->query("SELECT * FROM prestataire ");
$prestataires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Gestion des Prestataires</h2>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($erreur)): ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php endif; ?>

<div class="actions">
    <button id="openAddModal" class="btn btn-primary">
        <i class="fas fa-plus"></i> Ajouter un prestataire
    </button>
</div>

<div class="data-table">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>nom</th>
                <th>Catégorie</th>
                <th>description</th>
                <th>Région</th>
                <th>Contact</th>
                <th>email</th>
                <th>Date d'enregistrement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestataires as $prestataire): ?>
            <tr>
                <td><?php echo $prestataire['id_prestataire']; ?></td>
                <td>
                    <?php if ($prestataire['image_profil']): ?>
                        <img src="../<?php echo htmlspecialchars($prestataire['image_profil']); ?>" alt="" style="max-width:50px;max-height:50px;border-radius:50%;">
                    <?php endif; ?>
                </td>
                <td><?php echo $prestataire['nom_entreprise']; ?></td>
                <td><?php echo $prestataire['categorie']; ?></td>
                <td><?php echo $prestataire['description']; ?></td>
                <td><?php echo $prestataire['region']; ?></td>
                <td><?php echo $prestataire['contact_telephone']; ?></td>
                <td><?php echo $prestataire['contact_email']; ?></td>
                <td><?php echo $prestataire['date_enregistrement']; ?></td>
                <td class="actions">
                    <a href="modifier_prestataire.php?id=<?php echo $prestataire['id_prestataire']; ?>" class="btn btn-sm btn-edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="prestataires.php?supprimer=<?php echo $prestataire['id_prestataire']; ?>" class="btn btn-sm btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce prestataire?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Ajout -->
<div id="addPrestModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:500px;width:90%;position:relative;">
        <button onclick="closeAddModal()" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;">&times;</button>
        <h3>Ajouter un prestataire</h3>
        <form id="addPrestForm" enctype="multipart/form-data">
            <div><label>nom</label><input type="text" name="nom_entreprise" required class="form-control"></div>
            <div><label>Catégorie</label><input type="text" name="categorie" required class="form-control"></div>
            <div><label>description</label><input type="text" name="description" required class="form-control"></div>
            <div><label>Région</label><input type="text" name="region" required class="form-control"></div>
            <div><label>Contact</label><input type="text" name="contact_telephone" class="form-control"></div>
            <div><label>Email</label><input type="email" name="contact_email" required class="form-control"></div>
            <div><label>Photo de profil</label><input type="file" name="image_profil" accept="image/*" class="form-control"></div>
            <div style="margin-top:1rem;"><button type="submit" class="btn btn-primary">Ajouter</button></div>
        </form>
        <div id="addPrestMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<!-- Modal Modification -->
<div id="editPrestModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:500px;width:90%;position:relative;">
        <button onclick="closeEditModal()" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;">&times;</button>
        <h3>Modifier un prestataire</h3>
        <form id="editPrestForm" enctype="multipart/form-data">
            <input type="hidden" name="id_prestataire" id="edit_id_prestataire">
            <input type="hidden" name="current_image" id="edit_current_image">
            <div><label>nom</label><input type="text" name="nom_entreprise" id="edit_nom_entreprise" required class="form-control"></div>
            <div><label>Catégorie</label><input type="text" name="categorie" id="edit_categorie" required class="form-control"></div>
            <div><label>description</label><input type="text" name="description" id="edit_description" required class="form-control"></div>
            <div><label>Région</label><input type="text" name="region" id="edit_region" required class="form-control"></div>
            <div><label>Contact</label><input type="text" name="contact_telephone" id="edit_contact_telephone" class="form-control"></div>
            <div><label>Email</label><input type="email" name="contact_email" id="edit_contact_email" required class="form-control"></div>
            <div>
                <label>Photo actuelle</label>
                <div id="edit_image_preview"></div>
            </div>
            <div><label>Changer la photo</label><input type="file" name="image_profil" accept="image/*" class="form-control"></div>
            <div style="margin-top:1rem;"><button type="submit" class="btn btn-primary">Enregistrer</button></div>
        </form>
        <div id="editPrestMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<!-- Modal Suppression -->
<div id="deletePrestModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:350px;width:90%;position:relative;text-align:center;">
        <button onclick="closeDeleteModal()" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;">&times;</button>
        <h3>Confirmation</h3>
        <p>Êtes-vous sûr de vouloir supprimer ce prestataire&nbsp;?</p>
        <div style="margin-top:1.5rem;">
            <button id="confirmDeletePrestBtn" class="btn btn-danger">Supprimer</button>
            <button onclick="closeDeleteModal()" class="btn btn-outline" style="margin-left:1rem;">Annuler</button>
        </div>
        <div id="deletePrestMsg" style="margin-top:1rem;"></div>
    </div>
</div>

<script>
let editTr = null, deleteTr = null, deletePrestId = null;

// Ouvrir modal ajout
document.getElementById('openAddModal').onclick = () => {
    document.getElementById('addPrestModal').style.display = 'flex';
};
function closeAddModal() {
    document.getElementById('addPrestModal').style.display = 'none';
    document.getElementById('addPrestForm').reset();
    document.getElementById('addPrestMsg').innerHTML = '';
}

// Ajout AJAX
document.getElementById('addPrestForm').onsubmit = function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch('ajouter_prestataire.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('addPrestMsg').innerHTML = '<span style="color:green;">Prestataire ajouté !</span>';
            // Ajout dans le tableau
            let tbody = document.querySelector('.data-table tbody');
            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${data.prestataire.id_prestataire}</td>
                <td>
                    ${data.prestataire.image_profil ? '<img src="../'+data.prestataire.image_profil+'" alt="" style="max-width:50px;max-height:50px;border-radius:50%;">' : ''}
                </td>
                <td>${data.prestataire.nom_entreprise}</td>
                <td>${data.prestataire.categorie}</td>
                <td>${data.prestataire.description}</td>
                <td>${data.prestataire.region}</td>
                <td>${data.prestataire.contact_email}</td>
                <td>${data.prestataire.date_enregistrement}</td>
                <td class="actions">
                    <a href="#" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></a>
                </td>
            `;
            tbody.prepend(tr);
            setTimeout(closeAddModal, 1200);
        } else {
            document.getElementById('addPrestMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('addPrestMsg').innerHTML = '<span style="color:red;">Erreur lors de l\'ajout.</span>';
    });
};

// Ouvrir modal modification
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.onclick = function(e) {
        e.preventDefault();
        editTr = this.closest('tr');
        document.getElementById('edit_id_prestataire').value = editTr.children[0].textContent;
        document.getElementById('edit_nom_entreprise').value = editTr.children[2].textContent;
        document.getElementById('edit_categorie').value = editTr.children[3].textContent;
        document.getElementById('edit_description').value = editTr.children[4].textContent;
        document.getElementById('edit_region').value = editTr.children[5].textContent;
        document.getElementById('edit_contact_telephone').value = editTr.children[6].textContent;
        document.getElementById('edit_contact_email').value = editTr.children[7].textContent;
        // Récupère le chemin de l'image
        let img = editTr.children[1].querySelector('img');
        let imgSrc = img ? img.getAttribute('src').replace('../','') : '';
        document.getElementById('edit_current_image').value = imgSrc;
        document.getElementById('edit_image_preview').innerHTML = img ? '<img src="'+img.getAttribute('src')+'" style="max-width:50px;max-height:50px;border-radius:50%;">' : '';
        document.getElementById('editPrestModal').style.display = 'flex';
    };
});
function closeEditModal() {
    document.getElementById('editPrestModal').style.display = 'none';
    document.getElementById('editPrestForm').reset();
    document.getElementById('editPrestMsg').innerHTML = '';
}

// Modification AJAX
document.getElementById('editPrestForm').onsubmit = function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch('modifier_prestataire.php', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('editPrestMsg').innerHTML = '<span style="color:green;">Prestataire modifié !</span>';
            // MAJ dans le tableau
            if(editTr) {
                editTr.children[2].textContent = data.prestataire.nom_entreprise;
                editTr.children[3].textContent = data.prestataire.categorie;
                editTr.children[4].textContent = data.prestataire.description;
                editTr.children[5].textContent = data.prestataire.region;
                editTr.children[6].textContent = data.prestataire.contact_email;
                // Met à jour la photo si besoin
                if(data.prestataire.image_profil) {
                    editTr.children[1].innerHTML = '<img src="../'+data.prestataire.image_profil+'" alt="" style="max-width:50px;max-height:50px;border-radius:50%;">';
                }
            }
            setTimeout(closeEditModal, 1200);
        } else {
            document.getElementById('editPrestMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('editPrestMsg').innerHTML = '<span style="color:red;">Erreur lors de la modification.</span>';
    });
};

// Ouvrir modal suppression
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.onclick = function(e) {
        e.preventDefault();
        deleteTr = this.closest('tr');
        deletePrestId = deleteTr.children[0].textContent;
        document.getElementById('deletePrestMsg').innerHTML = '';
        document.getElementById('deletePrestModal').style.display = 'flex';
    };
});
function closeDeleteModal() {
    document.getElementById('deletePrestModal').style.display = 'none';
    deleteTr = null;
    deletePrestId = null;
}

// Suppression AJAX
document.getElementById('confirmDeletePrestBtn').onclick = function() {
    if(!deletePrestId) return;
    fetch('supprimer_prestataire.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'id='+encodeURIComponent(deletePrestId)
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) {
            document.getElementById('deletePrestMsg').innerHTML = '<span style="color:green;">Prestataire supprimé !</span>';
            if(deleteTr) {
                let tbody = document.querySelector('.data-table tbody');
                tbody.removeChild(deleteTr);
            }
            setTimeout(closeDeleteModal, 1200);
        } else {
            document.getElementById('deletePrestMsg').innerHTML = '<span style="color:red;">'+data.message+'</span>';
        }
    })
    .catch(() => {
        document.getElementById('deletePrestMsg').innerHTML = '<span style="color:red;">Erreur lors de la suppression.</span>';
    });
};
</script>

<?php require_once 'footer.php'; ?>