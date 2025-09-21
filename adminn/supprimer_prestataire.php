<?php
require_once '../config.php';
header('Content-Type: application/json');
$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success'=>false, 'message'=>'ID manquant']);
    exit;
}
$stmt = $pdo->prepare("DELETE FROM prestataire WHERE id_prestataire = ?");
$stmt->execute([$id]);
echo json_encode(['success'=>true]);