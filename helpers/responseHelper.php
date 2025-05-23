<?php
session_start();

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

function respond($message, $type = 'danger', $redirect = null)
{
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    if ($isAjax) {
        jsonResponse($message, $type, $redirect);
    } else {
        $_SESSION['toast'] = [
            'message' => $message,
            'type' => $type
        ];
        header("Location: " . ($redirect ?? 'CHAMADOS-TI/views/login.php'));
        exit;
    }
}
