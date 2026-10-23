<?php
require_once __DIR__ . '/ParticipantRequests.php';
require_once __DIR__ . '/Participant.php';

class ParticipantRequests {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function getAllParticipants(): array {
        $sql = "SELECT id, email, is_confirmed, token_hash, token_expires_at FROM participants";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $participants = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participant = new Participant($row['email']);
            $participant->setParticipantAllValues(
                $row['id'],
                $row['email'],
                $row['is_confirmed'],
                $row['token_hash'],
                $row['token_expires_at'] ? new DateTime($row['token_expires_at']) : null
            );
            $participants[] = $participant; 
        }

        return $participants;
    }

    public function create(Participant $participant): void {

    }


}
