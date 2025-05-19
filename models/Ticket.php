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
}
