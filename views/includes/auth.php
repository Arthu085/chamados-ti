<?php
session_start();

function checkAuth()
{
    $timeout_duration = 900; // 15 minutos
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

    // Sessão expirada
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        session_unset();
        session_destroy();

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'toast' => [
                    'message' => 'Sua sessão expirou por inatividade. Faça login novamente.',
                    'type' => 'warning'
                ],
                'redirect' => '/chamados-ti/views/login.php'
            ]);
            exit();
        } else {
            $_SESSION['toast'] = [
                'message' => 'Sua sessão expirou por inatividade. Faça login novamente.',
                'type' => 'warning'
            ];
            header("Location: /chamados-ti/views/login.php");
            exit();
        }
    }

    $_SESSION['LAST_ACTIVITY'] = time();

    // Sem login
    if (!isset($_SESSION['user'])) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'toast' => [
                    'message' => 'Faça login para entrar na tela.',
                    'type' => 'danger'
                ],
                'redirect' => '/chamados-ti/views/login.php'
            ]);
            exit();
        } else {
            $_SESSION['toast'] = [
                'message' => 'Faça login para entrar na tela.',
                'type' => 'danger'
            ];
            header("Location: /chamados-ti/views/login.php");
            exit();
        }
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
