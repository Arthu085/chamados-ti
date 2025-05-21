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

}
