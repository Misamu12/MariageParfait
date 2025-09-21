<?php
require_once '../config.php';
header('Content-Type: application/json');
$id = intval($_POST['id'] ?? 0);
if (!$id) {
    echo json_encode(['success'=>false]);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM message_contact WHERE id_message = ?");
$stmt->execute([$id]);
$email = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$email) {
    echo json_encode(['success'=>false]);
    exit;
}
echo json_encode(['success'=>true, 'email'=>[
    'nom' => $email['nom'],
    'email' => $email['email'],
    'sujet' => $email['sujet'],
    'date_envoi' => $email['date_envoi'],
    'message' => $email['message']
]]);