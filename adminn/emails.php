<?php
require_once 'header.php';

// Récupération des emails
$stmt = $conn->query("SELECT * FROM message_contact ORDER BY date_envoi DESC");
$emails = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de l'envoi d'une campagne
$message = "";
$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['envoyer_campagne'])) {
    $sujet = htmlspecialchars(trim($_POST['sujet']));
    if (empty($sujet)) {
        $erreur = "Le sujet de la campagne ne peut pas être vide.";
    }
    $contenu = htmlspecialchars(trim($_POST['contenu']));
    if (empty($contenu)) {
        $erreur = "Le contenu de la campagne ne peut pas être vide.";
    }
    
    // Récupération des emails des utilisateurs
    $stmt = $conn->query("SELECT email FROM utilisateur");
    $destinataires = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Simulation d'envoi d'email (dans un vrai projet, utilisez PHPMailer ou une autre bibliothèque)
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: contact@mariage-parfait.com' . "\r\n";
    
    $envoi_reussi = true;
    foreach ($destinataires as $email) {
        // Dans un vrai projet, remplacez cette ligne par un vrai envoi d'email
        // $envoi_reussi = mail($email, $sujet, $contenu, $headers);
        if (!$envoi_reussi) break;
    }
    
    if ($envoi_reussi) {
        // Enregistrement de la campagne dans la base de données
        $stmt = $conn->prepare("INSERT INTO message_contact (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Campagne', 'admin@mariage-parfait.com', $sujet, $contenu]);
        $message = "Campagne envoyée avec succès à " . count($destinataires) . " destinataires";
    } else {
        $erreur = "Erreur lors de l'envoi de la campagne";
    }
}
?>

<h2>Gestion des E-mails</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if ($erreur): ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php endif; ?>

<div class="email-tabs">
    <ul class="tabs">
        <li class="active"><a href="#historique">Historique des messages</a></li>
        <li><a href="#campagne">Envoyer une campagne</a></li>
    </ul>
    
    <div class="tab-content">
        <div id="historique" class="tab-pane active">
            <div class="data-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Sujet</th>
                            <th>Date d'envoi</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($emails as $email): ?>
                        <tr>
                            <td><?php echo  $email['id_message']; ?></td>
                            <td><?php echo htmlspecialchars($email['nom']); ?></td>
                            <td><?php echo htmlspecialchars($email['email']);?></td>
                            <td><?php echo htmlspecialchars($email['sujet']); ?></td>
                            <td><?php echo $email['date_envoi']; ?></td>
                            <td class="actions">
                                <a href="#" class="btn btn-sm btn-view" data-id="<?php echo $email['id_message']; ?>" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-delete" data-id="<?php echo $email['id_message']; ?>" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="https://mail.google.com/mail/?view=cm&to=<?php echo $email['email']; ?>&su=Re: <?php echo rawurlencode($email['sujet']); ?>" target="_blank" class="btn btn-sm btn-reply" title="Répondre via Gmail">
                                    <i class="fas fa-reply"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div id="campagne" class="tab-pane">
            <div class="form-container">
                <form method="post" action="">
                    <div class="form-group">
                        <label for="sujet">Sujet de la campagne</label>
                        <input type="text" id="sujet" name="sujet" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contenu">Contenu de l'email</label>
                        <textarea id="contenu" name="contenu" rows="10" required></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" name="envoyer_campagne" class="btn btn-primary">Envoyer la campagne</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de lecture d'email -->
<div id="viewEmailModal" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.4);z-index:3000;align-items:center;justify-content:center;">
    <div style="background:#fff;padding:2rem;border-radius:10px;max-width:500px;width:90%;position:relative;">
        <button onclick="closeViewModal()" style="position:absolute;top:10px;right:10px;background:none;border:none;font-size:1.5rem;">&times;</button>
        <h3 id="viewEmailSujet"></h3>
        <ul style="list-style:none;padding:0;">
            <li><strong>De :</strong> <span id="viewEmailNom"></span> &lt;<span id="viewEmailAdresse"></span>&gt;</li>
            <li><strong>Date :</strong> <span id="viewEmailDate"></span></li>
        </ul>
        <div id="viewEmailMessage" style="margin-top:1.5rem;white-space:pre-line;"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tabs li');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Retirer la classe active de tous les onglets
            tabs.forEach(t => t.classList.remove('active'));
            
            // Ajouter la classe active à l'onglet cliqué
            this.classList.add('active');
            
            // Afficher le contenu correspondant
            const target = this.querySelector('a').getAttribute('href').substring(1);
            tabPanes.forEach(pane => {
                pane.classList.remove('active');
                if (pane.id === target) {
                    pane.classList.add('active');
                }
            });
        });
    });
    
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            if(confirm('Supprimer ce message ?')) {
                const id = this.getAttribute('data-id');
                fetch('supprimer_email.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/x-www-form-urlencoded'},
                    body: 'id='+encodeURIComponent(id)
                })
                .then(r => r.json())
                .then(data => {
                    if(data.success) {
                        this.closest('tr').remove();
                    } else {
                        alert('Erreur : ' + data.message);
                    }
                });
            }
        };
    });
    
    // Ouvre le modal de lecture d'email
    document.querySelectorAll('.btn-view').forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            fetch('voir_email.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'id='+encodeURIComponent(id)
            })
            .then(r => r.json())
            .then(data => {
                console.log(data); // Ajoute ceci pour debug
                if(data.success) {
                    document.getElementById('viewEmailSujet').textContent = data.email.sujet;
                    document.getElementById('viewEmailNom').textContent = data.email.nom;
                    document.getElementById('viewEmailAdresse').textContent = data.email.email;
                    document.getElementById('viewEmailDate').textContent = data.email.date_envoi;
                    document.getElementById('viewEmailMessage').innerHTML = data.email.message.replace(/\n/g, "<br>");
                    document.getElementById('viewEmailModal').style.display = 'flex';
                } else {
                    alert('Message introuvable');
                }
            });
        };
    });
});

function closeViewModal() {
    document.getElementById('viewEmailModal').style.display = 'none';
}
</script>

<?php require_once 'footer.php'; ?>