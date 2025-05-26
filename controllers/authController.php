<?php
require_once('../config/db.php');
require_once('../models/User.php');
require_once('../helpers/responseHelper.php');

$userModel = new User($pdo);

$action = $_POST['action'] ?? null;
if (!$action) {
    respond('Ação não especificada.');
}

switch ($action) {
    case 'login':
        login($userModel);
        break;
    case 'register':
        register($userModel);
        break;
    default:
        respond('Ação inválida.');
}

function login($userModel)
{
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        respond('Preencha todos os campos.', 'warning');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond('E-mail inválido.');
    }

    $user = $userModel->findByEmail($email);

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

function register($userModel)
{
    global $pdo;

    $fieldLabels = [
        'name' => 'nome',
        'lastname' => 'sobrenome',
        'email' => 'e-mail',
        'birth_date' => 'data de nascimento',
        'phone_number' => 'número de telefone',
        'whatsapp_number' => 'número de whatsApp',
        'state' => 'estado',
        'city' => 'cidade',
        'password' => 'senha',
        'confirm_password' => 'confirmação de senha'
    ];

    foreach ($fieldLabels as $field => $label) {
        if (empty($_POST[$field])) {
            respond("O campo {$label} é obrigatório.", 'warning');
        }
    }

    extract($_POST);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        respond('E-mail inválido.');
    }

    if (strlen($phone_number) !== 11 || strlen($whatsapp_number) !== 11) {
        respond('Número de telefone e WhatsApp devem ter 15 caracteres.');
    }

    $birthDate = new DateTime($birth_date);
    $age = (new DateTime())->diff($birthDate)->y;
    if ($age < 18) {
        respond('Você precisa ter pelo menos 18 anos.');
    }

    if ($password !== $confirm_password) {
        respond('As senhas não coincidem.');
    }

    if ($userModel->findByEmail($email)) {
        respond('Este e-mail já está cadastrado.', 'warning');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, last_name, birth_date, email, phone_number, whatsapp_number, password, city, state)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $success = $stmt->execute([
        $name,
        $lastname,
        $birth_date,
        $email,
        $phone_number,
        $whatsapp_number,
        $hashedPassword,
        $city,
        $state
    ]);

    if ($success) {
        respond('Cadastro realizado com sucesso!', 'success', '../views/login.php');
    } else {
        respond('Erro ao cadastrar. Tente novamente.');
    }
}
