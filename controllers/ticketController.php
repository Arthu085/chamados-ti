<?php
session_start();
require_once('../config/db.php');
require_once('../models/Ticket.php');

header('Content-Type: application/json');

// Verifica autenticação
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/CHAMADOS-TI/controllers/ticketController.php';
$route = str_replace($basePath . '/', '', $path);

$ticketModel = new Ticket($pdo);

try {
    switch ($route) {
        case 'tickets/fetch/user':
            $tickets = $ticketModel->fetchTicketByUser($_SESSION['user']['id']);
            echo json_encode($tickets);
            break;

        case 'tickets/fetch/open':
            $ticketsCount = $ticketModel->fetchNumberOfOpenTicketsByUser($_SESSION['user']['id']);
            echo json_encode($ticketsCount);
            break;

        case 'tickets/fetch/close':
            $ticketsCount = $ticketModel->fetchNumberOfCloseTicketsByUser($_SESSION['user']['id']);
            echo json_encode($ticketsCount);
            break;

        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
