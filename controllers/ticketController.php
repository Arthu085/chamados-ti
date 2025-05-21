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

        case 'tickets/create':
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

            $description = trim($input['description'] ?? '');
            $type = trim($input['incident_type'] ?? '');
            $contacts = $input['contacts'] ?? [];
            $attachments = $input['attachments'] ?? [];

            if (empty($description) || empty($type) || empty($contacts) || empty($attachments)) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'Todos os campos são obrigatórios.',
                        'type' => 'warning'
                    ]
                ]);
                exit;
            }

            foreach ($contacts as $contact) {
                if (
                    empty(trim($contact['name'] ?? '')) ||
                    empty(trim($contact['phone'] ?? '')) ||
                    empty(trim($contact['note'] ?? ''))
                ) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'Todos os campos dos contatos são obrigatórios.',
                            'type' => 'warning'
                        ]
                    ]);
                    exit;
                }

                $phone = preg_replace('/\D/', '', $contact['phone'] ?? '');
                if (strlen($phone) !== 11) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'O telefone do contato deve conter 11 dígitos (DDD + número).',
                            'type' => 'warning'
                        ]
                    ]);
                    exit;
                }
            }

            $newTicket = $ticketModel->createTicket(
                $_SESSION['user']['id'],
                $description,
                $type,
                $contacts,
                $attachments
            );

            if (!$newTicket['success']) {
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'Erro ao criar chamado.',
                        'type' => 'danger'
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'toast' => [
                        'message' => 'Chamado criado com sucesso!',
                        'type' => 'success'
                    ]
                ]);
            }
            exit;

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

        case 'tickets/edit/finish':
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                $idTicket = $data['id'] ?? '';
                $userId = $_SESSION['user']['id'] ?? null;

                if (empty($idTicket) || empty($userId)) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'ID do chamado ou usuário inválido.',
                            'type' => 'warning'
                        ]
                    ]);
                    exit;
                }

                $result = $ticketModel->finishTicket($idTicket, $userId);

                if (!$result['success']) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'Erro ao finalizar chamado.',
                            'type' => 'danger'
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'toast' => [
                            'message' => 'Chamado finalizado com sucesso!',
                            'type' => 'success'
                        ]
                    ]);
                }
            }
            break;

        case 'tickets/edit/reopen':
            if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                $input = file_get_contents('php://input');
                $data = json_decode($input, true);

                $idTicket = $data['id'] ?? '';
                $userId = $_SESSION['user']['id'] ?? null;

                if (empty($idTicket) || empty($userId)) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'ID do chamado ou usuário inválido.',
                            'type' => 'warning'
                        ]
                    ]);
                    exit;
                }

                $result = $ticketModel->reopenTicket($idTicket, $userId);

                if (!$result['success']) {
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'Erro ao reabrir chamado.',
                            'type' => 'danger'
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'toast' => [
                            'message' => 'Chamado reaberto com sucesso!',
                            'type' => 'success'
                        ]
                    ]);
                }
            }
            break;


        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
