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
$basePath = '/CHAMADOS-TI/controllers/ticket/createController.php';
$route = str_replace($basePath . '/', '', $path);

$ticketModel = new Ticket($pdo);

try {
    switch ($route) {
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

        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
