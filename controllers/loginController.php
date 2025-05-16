<?php
session_start();

require_once('../config/db.php');


$email = $_POST['email'];
$password = $_POST['password'];

// Validação de campos
if (empty($email) || empty($password)) {
    $_SESSION['toast'] = [
        'message' => 'Preencha todos os campos.',
        'type' => 'warning'
    ];
    header("Location: ../views/login.php");
    exit();
}

// Validação de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['toast'] = [
        'message' => 'E-mail inválido. Por favor, insira um e-mail válido.',
        'type' => 'danger'
    ];
    header("Location: ../views/login.php");
    exit();
}

// Verificar se o e-mail existe
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Login bem-sucedido
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'lastname' => $user['last_name'],
    ];
    $_SESSION['toast'] = [
        'message' => 'Login realizado com sucesso!',
        'type' => 'success'
    ];
    header("Location: ../views/dashboard.php"); // redirecione para a área logada
    exit();
} else {
    // Credenciais inválidas
    $_SESSION['toast'] = [
        'message' => 'E-mail ou senha incorretos.',
        'type' => 'danger'
    ];
    header("Location: ../views/login.php");
    exit();
}