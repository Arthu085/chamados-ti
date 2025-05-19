<?php
session_start();

function checkAuth()
{
    $timeout_duration = 900; // 15 minutos

    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['toast'] = [
            'message' => 'Sua sessão expirou por inatividade. Faça login novamente.',
            'type' => 'warning'
        ];
        header("Location: /chamados-ti/views/login.php");
        exit();
    }

    $_SESSION['LAST_ACTIVITY'] = time();

    if (!isset($_SESSION['user'])) {
        $_SESSION['toast'] = [
            'message' => 'Faça login para entrar na tela.',
            'type' => 'danger'
        ];
        header("Location: /chamados-ti/views/login.php");
        exit();
    }

    return $_SESSION['user'];
}

function logout()
{
    session_unset();
    session_destroy();
    header("Location: /chamados-ti/views/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    logout();
}