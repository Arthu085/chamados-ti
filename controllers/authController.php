<?php
session_start();
require_once('../config/db.php');

// Função para resposta JSON
function jsonResponse($message, $type = 'info', $redirect = null)
{
    header('Content-Type: application/json');
    echo json_encode([
        'toast' => [
            'message' => $message,
            'type' => $type
        ],
        'redirect' => $redirect
    ]);
    exit;
}

// Detecta requisição AJAX
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

function respond($message, $type = 'danger', $redirect = null)
{
    global $isAjax;
    if ($isAjax) {
        jsonResponse($message, $type, $redirect);
    } else {
        $_SESSION['toast'] = [
            'message' => $message,
            'type' => $type
        ];
        header("Location: " . ($redirect ?? '../views/login.php'));
        exit;
    }
}

// Recupera ação
$action = $_POST['action'] ?? null;

if (!$action) {
    respond('Ação não especificada.');
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'register':
        register();
        break;
    default:
        respond('Ação inválida.');
}

// Função de login
function login()
{
    global $pdo;

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        respond('Preencha todos os campos.', 'warning');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond('E-mail inválido.', 'danger');
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'lastname' => $user['last_name'],
            'email' => $user['email'],
        ];
        respond('Login realizado com sucesso!', 'success', '../views/dashboard.php');
    } else {
        respond('E-mail ou senha incorretos.', 'danger');
    }
}

// Função de registro
function register()
{
    global $pdo;

    $name = $_POST['name'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $birth_date = $_POST['birth_date'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $whatsapp_number = $_POST['whatsapp_number'] ?? '';
    $state = $_POST['state'] ?? '';
    $city = $_POST['city'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($lastname) || empty($email) || empty($birth_date) || empty($phone_number) || empty($whatsapp_number) || empty($state) || empty($city) || empty($password) || empty($confirm_password)) {
        respond('Todos os campos são obrigatórios.', 'warning');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond('E-mail inválido.');
    }
    if (strlen($phone_number) !== 15) {
        respond('O número de telefone deve ter 15 caracteres.');
    }
    if (strlen($whatsapp_number) !== 15) {
        respond('O número de WhatsApp deve ter 15 caracteres.');
    }
    $birthDate = new DateTime($birth_date);
    $age = (new DateTime())->diff($birthDate)->y;
    if ($age < 18 || $age < 0) {
        respond('Você precisa ter pelo menos 18 anos.');
    }
    if ($password !== $confirm_password) {
        respond('As senhas não coincidem.');
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        respond('Este e-mail já está cadastrado.', 'warning');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, last_name, birth_date, email, phone_number, whatsapp_number, password, city, state)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([$name, $lastname, $birth_date, $email, $phone_number, $whatsapp_number, $hashedPassword, $city, $state])) {
        respond('Cadastro realizado com sucesso!', 'success', '../views/login.php');
    } else {
        respond('Erro ao cadastrar. Tente novamente.');
    }
}
