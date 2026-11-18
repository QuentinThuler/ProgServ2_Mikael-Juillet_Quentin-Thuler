<?php

namespace App\Participant;

use PDO;

class ParticipantRequests {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Create a new participant
     *
     * @param Participant $participant The participant to create
     * @return void
     */
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

    /**
     * Get all participants from the database
     *
     * @return Participant[]
     */
    public function getAllParticipants(): array {
        $sql = "SELECT id, email, is_confirmed, token_hash, token_expires_at FROM participants";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $participants = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participants[] = $this->buildParticipant($row);
        }

        return $participants;
    }

    /**
     * Get a participant by their email
     *
     * @param string $email The participant's email
     * @return ?Participant The participant if found, null otherwise
     */
    public function getParticipantByEmail(string $email): ?Participant {
        $sql = "SELECT id, email, is_confirmed, token_hash, token_expires_at FROM participants WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $participant = $this->buildParticipant($row);
        return $participant;
    }

    /**
     * Update a participant's email
     *
     * @param Participant $participant The participant to update
     * @return void
     */
    public function updateEmail(Participant $participant): void {
        $sql = '
            UPDATE participants
            SET email = :email
            WHERE id = :id
        ';

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            'id' => $participant->getId(),
            'email' => $participant->getEmail()
        ]);
    }

    /**
     * Delete a participant
     *
     * @param Participant $participant The participant to delete
     * @return void
     */
    public function delete(Participant $participant): void {
        $sql = 'DELETE FROM participants WHERE id = :id';

        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $participant->getId()]);
    }

    /**
     * Get a participant by their ID
     *
     * @param int $id The participant's ID
     * @return ?Participant The participant if found, null otherwise
     */
    public function getParticipantById(int $id): ?Participant {
        $sql = '
        SELECT id, email, is_confirmed, token_hash, token_expires_at
        FROM participants
        WHERE id = :id
    ';

        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $participant = $this->buildParticipant($row);
        return $participant;
    }

    // PRIVATE METHODS

    private function buildParticipant(array $row): Participant {
        $participant = new Participant($row['email']);
        $participant->setID((int) $row['id']);
        return $participant;
    }
}
