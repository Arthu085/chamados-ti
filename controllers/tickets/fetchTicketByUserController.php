<?php
session_start();
require_once('../../config/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}


try {
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $_SESSION['user']['id']);
    $stmt->execute();

    $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($tickets);
} catch (PDOException $error) {
    echo json_encode(['erro' => 'Erro no banco de dados: ' . $error->getMessage()]);
}
