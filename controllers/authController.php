<?php
session_start();
require_once('../models/User.php');
header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode([
        'success' => false,
        'toast' => [
            'message' => 'Preencha todos os campos.',
            'type' => 'warning'
        ]
    ]);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'toast' => [
            'message' => 'E-mail inválido.',
            'type' => 'danger'
        ]
    ]);
    exit();
}

$userModel = new User($pdo);
$user = $userModel->findByEmail($email);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'lastname' => $user['last_name'],
    ];

    echo json_encode([
        'success' => true,
        'toast' => [
            'message' => 'Login realizado com sucesso!',
            'type' => 'success'
        ],
        'redirect' => '../views/dashboard.php'
    ]);
    exit();
} else {
    echo json_encode([
        'success' => false,
        'toast' => [
            'message' => 'E-mail ou senha incorretos.',
            'type' => 'danger'
        ]
    ]);
    exit();
}
