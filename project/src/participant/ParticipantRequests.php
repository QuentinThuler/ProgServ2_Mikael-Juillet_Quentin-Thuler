<?php

namespace App\participant;

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
    public function getAll(): array {
        $sql = "SELECT id, email FROM participants";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $participants = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $participants[] = $this->build($row);
        }

        return $participants;
    }

    /**
     * Get a participant by their email
     *
     * @param string $email The participant's email
     * @return ?Participant The participant if found, null otherwise
     */
    public function getByEmail(string $email): ?Participant {
        $sql = "SELECT id, email FROM participants WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $participant = $this->build($row);
        return $participant;
    }

    /**
     * Update a participant's email
     *
     * @param Participant $participant The participant to update
     * @return void
     */
    public function update(Participant $participant): void {
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
    public function getById(int $id): ?Participant {
        $sql = '
        SELECT id, email
        FROM participants
        WHERE id = :id
    ';

        $statement = $this->connection->prepare($sql);
        $statement->execute(['id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $participant = $this->build($row);
        return $participant;
    }

    // PRIVATE METHODS

    private function build(array $row): Participant {
        $participant = new Participant($row['email']);
        $participant->setID((int) $row['id']);
        return $participant;
    }
}
