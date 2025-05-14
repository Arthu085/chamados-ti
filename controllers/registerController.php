<?php
session_start();

require_once('../config/db.php');


$name = $_POST['name'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$birth_date = $_POST['birth_date'];
$phone_number = $_POST['phone_number'];
$whatsapp_number = $_POST['whatsapp_number'];
$state = $_POST['state'];
$city = $_POST['city'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validação de email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['toast'] = [
        'message' => 'E-mail inválido. Por favor, insira um e-mail válido.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}

// Validação de número de telefone e WhatsApp
$phone_length = strlen($phone_number);
$whatsapp_length = strlen($whatsapp_number);

// Validar se ambos os números têm exatamente 13 caracteres (formato "(00) 00000-0000")
if ($phone_length !== 15) {
    $_SESSION['toast'] = [
        'message' => 'O número de telefone deve ter 15 caracteres.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}

if ($whatsapp_length !== 15) {
    $_SESSION['toast'] = [
        'message' => 'O número de WhatsApp deve ter 15 caracteres.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}

// Validação de idade
$birthDate = new DateTime($birth_date);
$today = new DateTime();
$age = $today->diff($birthDate)->y;

if ($age < 18 || $age < 0) {
    $_SESSION['toast'] = [
        'message' => 'Você precisa ter pelo menos 18 anos para se cadastrar.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}

// Validação de senha
if ($password != $confirm_password) {
    $_SESSION['toast'] = [
        'message' => 'As senhas não coincidem.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Verificar se o e-mail já está registrado
$query = "SELECT COUNT(*) FROM users WHERE email = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$email]);
$emailExists = $stmt->fetchColumn();

if ($emailExists > 0) {
    $_SESSION['toast'] = [
        'message' => 'Este e-mail já está cadastrado.',
        'type' => 'warning'
    ];
    header("Location: ../views/register.php");
    exit();
}

// Inserção no banco
$query = "INSERT INTO users (name, last_name, birth_date, email, phone_number, whatsapp_number, password, city, state)
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($query);

if ($stmt->execute([$name, $lastname, $birth_date, $email, $phone_number, $whatsapp_number, $hashedPassword, $city, $state])) {
    $_SESSION['toast'] = [
        'message' => 'Cadastro realizado com sucesso!',
        'type' => 'success'
    ];
    header("Location: ../views/login.php");
    exit();
} else {
    $_SESSION['toast'] = [
        'message' => 'Erro ao cadastrar. Tente novamente mais tarde.',
        'type' => 'danger'
    ];
    header("Location: ../views/register.php");
    exit();
}
