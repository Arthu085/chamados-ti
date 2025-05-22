<?php
class Ticket
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function fetchTicketByUser($userId)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tickets WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchNumberOfOpenTicketsByUser($userId)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as total FROM tickets WHERE status = "aberto" AND user_id = :user_id');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchNumberOfCloseTicketsByUser($userId)
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) as total FROM tickets WHERE status = "finalizado" AND user_id = :user_id');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTicket($userId, $description, $type, array $contacts, array $attachments)
    {
        $this->pdo->beginTransaction();

        try {
            // 1. Inserir o chamado principal
            $stmt = $this->pdo->prepare('
            INSERT INTO tickets (user_id, description, incident_type) 
            VALUES (:user_id, :description, :incident_type)
        ');
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':incident_type', $type, PDO::PARAM_STR);
            $stmt->execute();

            $idTicket = $this->pdo->lastInsertId();

            // 2. Inserir histórico
            $stmt = $this->pdo->prepare('
            INSERT INTO ticket_history (ticket_id, user_id, action, message) 
            VALUES (:ticket_id, :user_id, "abertura", "abriu o chamado")
        ');
            $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // 3. Inserir contatos (varios)
            $stmtContact = $this->pdo->prepare('
            INSERT INTO ticket_contacts (ticket_id, name, phone, note) 
            VALUES (:ticket_id, :name, :phone, :note)
        ');
            foreach ($contacts as $contact) {
                $stmtContact->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmtContact->bindParam(':name', $contact['name'], PDO::PARAM_STR);
                $stmtContact->bindParam(':phone', $contact['phone'], PDO::PARAM_STR);
                $stmtContact->bindParam(':note', $contact['note'], PDO::PARAM_STR);
                $stmtContact->execute();
            }

            // 4. Inserir anexos (varios, em base64)
            $stmtAttachment = $this->pdo->prepare('
            INSERT INTO ticket_attachments (ticket_id, file_name, file_base64) 
            VALUES (:ticket_id, :file_name, :file_base64)
        ');
            foreach ($attachments as $attachment) {
                $stmtAttachment->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmtAttachment->bindParam(':file_name', $attachment['file_name'], PDO::PARAM_STR);
                $stmtAttachment->bindParam(':file_base64', $attachment['base64'], PDO::PARAM_STR);
                $stmtAttachment->execute();
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Chamado criado com sucesso'
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao criar o chamado: ' . $e->getMessage()
            ];
        }
    }
    public function deleteTicket($idTicket)
    {
        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('DELETE FROM tickets WHERE id = :id');
            $stmt->bindParam(':id', $idTicket, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Chamado deletado com sucesso'
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao deletar o chamado: ' . $e->getMessage()
            ];
        }
    }

    public function fetchTicketHistory($idTicket)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ticket_history WHERE ticket_id = :ticket_id');
        $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchTicketContacts($idTicket)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ticket_contacts WHERE ticket_id = :ticket_id');
        $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchTicketAttachments($idTicket)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM ticket_attachments WHERE ticket_id = :ticket_id');
        $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchTicketDetails($idTicket)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM tickets WHERE id = :ticket_id');
        $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function finishTicket($idTicket, $userId)
    {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('UPDATE tickets SET status = "finalizado" WHERE id = :id');
            $stmt->bindParam(':id', $idTicket, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare('INSERT INTO ticket_history (ticket_id, user_id, action, message) VALUES (:ticket_id, :user_id, "finalização", "finalizou o chamado")');
            $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Chamado finalizado com sucesso'
            ];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao finalizar o chamado: ' . $e->getMessage()
            ];
        }
    }

    public function reopenTicket($idTicket, $userId)
    {

        $this->pdo->beginTransaction();

        try {
            $stmt = $this->pdo->prepare('UPDATE tickets SET status = "aberto" WHERE id = :id');
            $stmt->bindParam(':id', $idTicket, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $this->pdo->prepare('INSERT INTO ticket_history (ticket_id, user_id, action, message) VALUES (:ticket_id, :user_id, "reabertura", "reabriu o chamado")');
            $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Chamado reaberto com sucesso'
            ];

        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao reabrir o chamado: ' . $e->getMessage()
            ];
        }
    }

    public function editTicket($idTicket, $userId, array $data)
    {
        $this->pdo->beginTransaction();

        try {
            // Buscar dados atuais do chamado
            $stmt = $this->pdo->prepare('SELECT description, incident_type FROM tickets WHERE id = :id');
            $stmt->bindParam(':id', $idTicket, PDO::PARAM_INT);
            $stmt->execute();
            $current = $stmt->fetch(PDO::FETCH_ASSOC);

            $fields = [];
            $params = [':id' => $idTicket];

            // Atualizar descrição
            if (isset($data['description']) && $data['description'] !== $current['description']) {
                $fields[] = 'description = :description';
                $params[':description'] = $data['description'];

                $stmt = $this->pdo->prepare('
                INSERT INTO ticket_history (ticket_id, user_id, action, message)
                VALUES (:ticket_id, :user_id, "atualização", "atualizou a descrição do chamado")
            ');
                $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Atualizar tipo de incidente
            if (isset($data['incident_type']) && $data['incident_type'] !== $current['incident_type']) {
                $fields[] = 'incident_type = :incident_type';
                $params[':incident_type'] = $data['incident_type'];

                $stmt = $this->pdo->prepare('
                INSERT INTO ticket_history (ticket_id, user_id, action, message)
                VALUES (:ticket_id, :user_id, "atualização", "atualizou o tipo de incidente do chamado")
            ');
                $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }


            // Nova observação
            if (isset($data['message']) && trim($data['message']) !== '') {
                $stmt = $this->pdo->prepare('
                INSERT INTO ticket_history (ticket_id, user_id, action, message)
                VALUES (:ticket_id, :user_id, "nova observação", :message)
            ');
                $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':message', $data['message'], PDO::PARAM_STR);
                $stmt->execute();
            }

            // Adicionar contatos
            if (isset($data['contact']) && is_array($data['contact'])) {
                $stmtContact = $this->pdo->prepare('
                INSERT INTO ticket_contacts (ticket_id, name, phone, note)
                VALUES (:ticket_id, :name, :phone, :note)
            ');
                foreach ($data['contact'] as $contact) {
                    $stmtContact->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                    $stmtContact->bindParam(':name', $contact['name'], PDO::PARAM_STR);
                    $stmtContact->bindParam(':phone', $contact['phone'], PDO::PARAM_STR);
                    $stmtContact->bindParam(':note', $contact['note'], PDO::PARAM_STR);
                    $stmtContact->execute();
                }
                $stmt = $this->pdo->prepare('
                INSERT INTO ticket_history (ticket_id, user_id, action, message)
                VALUES (:ticket_id, :user_id, "atualização", "atualizou os contatos do chamado")
            ');
                $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Adicionar anexos
            if (isset($data['attachment']) && is_array($data['attachment'])) {
                $stmtAttachment = $this->pdo->prepare('
                INSERT INTO ticket_attachments (ticket_id, file_name, file_base64)
                VALUES (:ticket_id, :file_name, :file_base64)
            ');
                foreach ($data['attachment'] as $attachment) {
                    $stmtAttachment->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                    $stmtAttachment->bindParam(':file_name', $attachment['file_name'], PDO::PARAM_STR);
                    $stmtAttachment->bindParam(':file_base64', $attachment['base64'], PDO::PARAM_STR);
                    $stmtAttachment->execute();
                }

                $stmt = $this->pdo->prepare('
                INSERT INTO ticket_history (ticket_id, user_id, action, message)
                VALUES (:ticket_id, :user_id, "atualização", "atualizou os anexos do chamado")
            ');
                $stmt->bindParam(':ticket_id', $idTicket, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $stmt->execute();
            }

            // Atualizar campos na tabela de chamados
            if (!empty($fields)) {
                $sql = 'UPDATE tickets SET ' . implode(', ', $fields) . ' WHERE id = :id';
                $stmt = $this->pdo->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value);
                }
                $stmt->execute();
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'message' => 'Chamado atualizado com sucesso'
            ];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao atualizar o chamado: ' . $e->getMessage()
            ];
        }
    }


}
