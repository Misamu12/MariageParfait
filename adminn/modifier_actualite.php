<?php
require_once '../config.php';
header('Content-Type: application/json');

$id = intval($_POST['id_actualite'] ?? 0);
$titre = trim($_POST['titre'] ?? '');
$contenu = trim($_POST['contenu'] ?? '');

if (!$id || !$titre || !$contenu) {
    echo json_encode(['success'=>false, 'message'=>'Tous les champs sont obligatoires']);
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
    $stmt = $pdo->prepare("UPDATE actualite SET titre=?, contenu=?, image=?, date_publication=NOW() WHERE id_actualite=?");
    $stmt->execute([$titre, $contenu, $imageName, $id]);
} else {
    $stmt = $pdo->prepare("UPDATE actualite SET titre=?, contenu=?, date_publication=NOW() WHERE id_actualite=?");
    $stmt->execute([$titre, $contenu, $id]);
}

echo json_encode([
    'success'=>true,
    'actualite'=>[
        'id_actualite'=>$id,
        'titre'=>$titre,
        'contenu'=>$contenu,
        'image'=>$imageName,
        'date_publication'=>date('Y-m-d')
    ]
]);