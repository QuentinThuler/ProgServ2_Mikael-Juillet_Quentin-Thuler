<?php

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
                $row['is_confirmed'],
                $row['token_hash'],
                $row['token_expires_at'] ? new DateTime($row['token_expires_at']) : null
            );
            $participants[] = $participant;
        }

        return $participants;
    }

    public function getParticipantByEmail(string $email): ?Participant {
        $sql = "SELECT id, email, is_confirmed, token_hash, token_expires_at FROM participants WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $participant = new Participant($row['email']);
            $participant->setParticipantAllValues(
                $row['id'],
                $row['is_confirmed'],
                $row['token_hash'],
                $row['token_expires_at'] ? new DateTime($row['token_expires_at']) : null
            );
            return $participant;
        }

        return null;
    }

    public function create(Participant $participant): void {

        $sql = '
            INSERT INTO participants (email)
            VALUES (:email)
        ';

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'email' => $participant->getEmail()
        ]);
    }

    public function updateToken(Participant $participant): void {
        $sql = '
            UPDATE participants
            SET is_confirmed = :is_confirmed,
                token_hash = :token_hash,
                token_expires_at = :token_expires_at
            WHERE email = :email
        ';

        $statement = $this->connection->prepare($sql);

        $statement->execute([
            'is_confirmed' => (int) $participant->isConfirmed(),
            'token_hash' => $participant->getTokenHash(),
            'token_expires_at' => $participant->getTokenExpiresAt() ? $participant->getTokenExpiresAt()->format('Y-m-d H:i:s') : null,
            'email' => $participant->getEmail()
        ]);
    }
}
