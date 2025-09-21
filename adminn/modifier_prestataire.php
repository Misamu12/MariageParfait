<?php
require_once '../config.php';
header('Content-Type: application/json');
$id = intval($_POST['id_prestataire'] ?? 0);
$nom = trim($_POST['nom_entreprise'] ?? '');
$categorie = trim($_POST['categorie'] ?? '');
$description = trim($_POST['description'] ?? '');
$region = trim($_POST['region'] ?? '');
$email = trim($_POST['contact_email'] ?? '');
$contact_telephone = trim($_POST['contact_telephone'] ?? '');
$imagePath = trim($_POST['current_image'] ?? '');

if (!$id || !$nom || !$categorie || !$region || !$email || !$description) {
    echo json_encode(['success'=>false, 'message'=>'Tous les champs sont obligatoires']);
    exit;
}

// Gestion de l'upload de l'image
if (isset($_FILES['image_profil']) && $_FILES['image_profil']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/prestataires/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    $fileTmp = $_FILES['image_profil']['tmp_name'];
    $fileName = uniqid('prest_') . '_' . basename($_FILES['image_profil']['name']);
    $fileDest = $uploadDir . $fileName;
    if (move_uploaded_file($fileTmp, $fileDest)) {
        $imagePath = 'uploads/prestataires/' . $fileName;
    }
}

$stmt = $pdo->prepare("UPDATE prestataire SET nom_entreprise=?, categorie=?, description=?, region=?, contact_telephone=?, contact_email=?, image_profil=? WHERE id_prestataire=?");
$stmt->execute([$nom, $categorie, $description, $region, $contact_telephone, $email, $imagePath, $id]);
echo json_encode(['success'=>true, 'prestataire'=>[
    'id_prestataire'=>$id,
    'nom_entreprise'=>$nom,
    'categorie'=>$categorie,
    'description'=>$description,
    'region'=>$region,
    'contact_email'=>$email,
    'contact_telephone'=>$contact_telephone,
    'image_profil'=>$imagePath
]]);