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
$basePath = '/CHAMADOS-TI/controllers/ticket/deleteController.php';
$route = str_replace($basePath . '/', '', $path);

$ticketModel = new Ticket($pdo);

try {
    switch ($route) {
        case 'tickets/delete':
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'Dados inválidos.',
                        'type' => 'danger'
                    ]
                ]);
                exit;
            }

            $idTicket = trim($input['id'] ?? '');

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

            $deleteTicket = $ticketModel->deleteTicket($idTicket);

            if (!$deleteTicket['success']) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'Erro ao deletar chamado.',
                        'type' => 'danger'
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'toast' => [
                        'message' => 'Chamado deletado com sucesso!',
                        'type' => 'success'
                    ]
                ]);
            }
            exit;

        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
