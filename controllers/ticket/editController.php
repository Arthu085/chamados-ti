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
$basePath = '/CHAMADOS-TI/controllers/ticket/editController.php';
$route = str_replace($basePath . '/', '', $path);

$ticketModel = new Ticket($pdo);

try {
    switch ($route) {
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

        case 'tickets/edit':
            try {
                if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    $input = file_get_contents('php://input');
                    $data = json_decode($input, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'toast' => [
                                'message' => 'Dados inválidos enviados.',
                                'type' => 'danger'
                            ]
                        ]);
                        exit;
                    }

                    $idTicket = $data['id'] ?? '';
                    $userId = $_SESSION['user']['id'] ?? null;

                    if (empty($idTicket) || empty($userId)) {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'toast' => [
                                'message' => 'ID do chamado ou usuário inválido.',
                                'type' => 'warning'
                            ]
                        ]);
                        exit;
                    }

                    unset($data['id']);

                    // Buscar dados atuais do chamado
                    $current = $ticketModel->fetchTicketDetails($idTicket);

                    if (!$current) {
                        http_response_code(404);
                        echo json_encode([
                            'success' => false,
                            'toast' => [
                                'message' => 'Chamado não encontrado.',
                                'type' => 'danger'
                            ]
                        ]);
                        exit;
                    }

                    // Normalizar campos
                    $newDescription = trim(strip_tags($data['description'] ?? ''));
                    $oldDescription = trim(strip_tags($current['description'] ?? ''));

                    $newIncidentType = trim($data['incident_type'] ?? '');
                    $oldIncidentType = trim($current['incident_type'] ?? '');

                    $hasChangedDescription = $newDescription !== $oldDescription;
                    $hasChangedIncidentType = $newIncidentType !== $oldIncidentType;

                    $hasMessage = isset($data['message']) && trim($data['message']) !== '';
                    $hasContact = isset($data['contact']) && is_array($data['contact']) && count($data['contact']) > 0;
                    $hasAttachment = isset($data['attachment']) && is_array($data['attachment']) && count($data['attachment']) > 0;

                    // Se nada foi alterado, retorna aviso
                    if (
                        !$hasChangedDescription &&
                        !$hasChangedIncidentType &&
                        !$hasMessage &&
                        !$hasContact &&
                        !$hasAttachment
                    ) {
                        echo json_encode([
                            'success' => false,
                            'toast' => [
                                'message' => 'Nenhuma alteração detectada no chamado.',
                                'type' => 'info'
                            ]
                        ]);
                        exit;
                    }

                    // Salvar alterações no banco
                    $result = $ticketModel->editTicket($idTicket, $userId, $data);

                    if ($result['success']) {
                        echo json_encode([
                            'success' => true,
                            'toast' => [
                                'message' => 'Chamado atualizado com sucesso!',
                                'type' => 'success'
                            ]
                        ]);
                    } else {
                        http_response_code(400);
                        echo json_encode([
                            'success' => false,
                            'toast' => [
                                'message' => $result['error'] ?? 'Erro ao atualizar chamado.',
                                'type' => 'danger'
                            ]
                        ]);
                    }
                } else {
                    http_response_code(405);
                    echo json_encode([
                        'success' => false,
                        'toast' => [
                            'message' => 'Método não permitido.',
                            'type' => 'danger'
                        ]
                    ]);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'toast' => [
                        'message' => 'Erro interno no servidor: ' . $e->getMessage(),
                        'type' => 'danger'
                    ],
                    'error' => $e->getMessage() // Apenas para desenvolvimento
                ]);
            }
            break;

        default:
            echo json_encode(['erro' => 'Rota inválida']);
    }
} catch (PDOException $e) {
    echo json_encode(['erro' => 'Erro no banco: ' . $e->getMessage()]);
}
