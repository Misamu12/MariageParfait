<?php
require_once 'header.php';
header('Content-Type: application/json');
$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success'=>false, 'message'=>'ID manquant']);
    exit;
}
$stmt = $conn->prepare("DELETE FROM message_contact WHERE id_message = ?");
$stmt->execute([$id]);
echo json_encode(['success'=>true]);