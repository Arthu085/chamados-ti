<?php
session_start();
require_once('../../config/db.php');
require_once('../../models/Ticket.php');

header('Content-Type: application/json');

// Verifica autenticação
if (!isset($_SESSION['user']['id'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$basePath = '/CHAMADOS-TI/controllers/ticket/fetchController.php';
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

        case 'tickets/fetch/user/history':
            $idTicket = $_GET['id'] ?? '';

            if (empty($idTicket)) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'ID do chamado inválido.',
                        'type' => 'warning'
                    ]
                ]);
                exit;
            }

            $tickets = $ticketModel->fetchTicketHistory($idTicket);
            echo json_encode($tickets);
            break;

        case 'tickets/fetch/user/contacts':
            $idTicket = $_GET['id'] ?? '';

            if (empty($idTicket)) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'ID do chamado inválido.',
                        'type' => 'warning'
                    ]
                ]);
                exit;
            }

            $tickets = $ticketModel->fetchTicketContacts($idTicket);
            echo json_encode($tickets);
            break;

        case 'tickets/fetch/user/attachments':
            $idTicket = $_GET['id'] ?? '';

            if (empty($idTicket)) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'ID do chamado inválido.',
                        'type' => 'warning'
                    ]
                ]);
                exit;
            }

            $tickets = $ticketModel->fetchTicketAttachments($idTicket);
            echo json_encode($tickets);
            break;

        case 'tickets/fetch/user/details':
            $idTicket = $_GET['id'] ?? '';

            if (empty($idTicket)) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'ID do chamado inválido.',
                        'type' => 'warning'
                    ]
                ]);
                exit;
            }

            $tickets = $ticketModel->fetchTicketDetails($idTicket);
            echo json_encode($tickets);
            break;

        case 'tickets/fetch':
            $tickets = $ticketModel->fetchAllTickets();
            echo json_encode($tickets);
            break;

        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
