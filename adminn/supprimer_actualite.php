<?php
// filepath: c:\xampp\htdocs\mariage_v2\wedding-Presto-last\adminn\supprimer_actualite.php
require_once '../config.php';
header('Content-Type: application/json');

$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success'=>false, 'message'=>'ID manquant']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM actualite WHERE id_actualite = ?");
    $stmt->execute([$id]);
    echo json_encode(['success'=>true]);
} catch (PDOException $e) {
    echo json_encode(['success'=>false, 'message'=>'Erreur: '.$e->getMessage()]);
}