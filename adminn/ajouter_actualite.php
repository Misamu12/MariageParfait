<?php
require_once '../config.php';

header('Content-Type: application/json');

$titre = trim($_POST['titre'] ?? '');
$contenu = trim($_POST['contenu'] ?? '');

if (!$titre || !$contenu) {
    echo json_encode(['success'=>false, 'message'=>'Titre et contenu obligatoires']);
    exit;
}

// Gestion de l'image
$imageName = null;
if (!empty($_FILES['image']['name'])) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = uniqid().'.'.$ext;
    $uploadDir = __DIR__.'/../images/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$imageName);
}

$stmt = $pdo->prepare("INSERT INTO actualite (titre, contenu, image, date_publication) VALUES (?, ?, ?, NOW())");
$stmt->execute([$titre, $contenu, $imageName]);
$id = $pdo->lastInsertId();

echo json_encode([
    'success'=>true,
    'actualite'=>[
        'id_actualite'=>$id,
        'titre'=>$titre,
        'image'=>$imageName,
        'date_publication'=>date('Y-m-d')
    ]
]);