<?php

namespace App\user;

use App\participant\Participant;
use App\role\Role;
use DateTime;
use PDO;

class UserRequests {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Create a new user in the database
     * @param User $user The user to be created
     * @return void
     */
    public function create(User $user): void {
        $sql = '
            INSERT INTO users (
                participant_id,
                name,
                password,
                description,
                image,
                linkedin,
                phone,
                role_id
            ) VALUES (
                :participant_id,
                :name,
                :password,
                :description,
                :image,
                :linkedin,
                :phone,
                :role_id
            )
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'participant_id' => $user->getParticipant()->getId(),
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'description' => $user->getDescription(),
            'image' => $user->getImage(),
            'linkedin' => $user->getLinkedin(),
            'phone' => $user->getPhone(),
            'role_id' => $user->getRole()->getId()
        ]);

        $user->setId((int) $this->connection->lastInsertId());
    }

    /**
     * Get all users from the database
     * @return array
     */
    public function getAll(): array {
        $sql = '
            SELECT
                users.id AS user_id,
                users.name,
                users.password,
                users.description,
                users.image,
                users.linkedin,
                users.phone,
                users.role_id,

                participants.id AS participant_id,
                participants.email,

                roles.name AS role_name

            FROM users

            INNER JOIN participants
                ON users.participant_id = participants.id

            INNER JOIN roles
                ON users.role_id = roles.id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->build($row);
        }

        return $users;
    }

    /**
     * Get a user by their ID
     * @param int $id The user ID
     * @return ?User The user if found, null otherwise
     */
    public function getById(int $id): ?User {
        $sql = '
            SELECT
                users.id AS user_id,
                users.name,
                users.password,
                users.description,
                users.image,
                users.linkedin,
                users.phone,
                users.role_id,

                participants.id AS participant_id,
                participants.email,

                roles.name AS role_name

            FROM users

            INNER JOIN participants
                ON users.participant_id = participants.id

            INNER JOIN roles
                ON users.role_id = roles.id

            WHERE users.id = :id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->build($row);
    }

    /**
     * Update a user
     * @param User $user The user to update
     * @return void
     */
    public function update(User $user): void {
        $sql = '
            UPDATE users
            SET
                name = :name,
                password = :password,
                description = :description,
                image = :image,
                linkedin = :linkedin,
                phone = :phone,
                role_id = :role_id
            WHERE id = :id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'name' => $user->getName(),
            'password' => $user->getPassword(),
            'description' => $user->getDescription(),
            'image' => $user->getImage(),
            'linkedin' => $user->getLinkedin(),
            'phone' => $user->getPhone(),
            'role_id' => $user->getRole()->getId(),
            'id' => $user->getId()
        ]);
    }

    /**
     * Get a user by their participant ID
     * @param int $participantId The participant ID
     * @return ?User The user if found, null otherwise
     */
    public function getUserByParticipantId(int $participantId): ?User {
        $sql = '
            SELECT
                users.id AS user_id,
                users.name,
                users.password,
                users.description,
                users.image,
                users.linkedin,
                users.phone,
                users.role_id,

                participants.id AS participant_id,
                participants.email,

                roles.name AS role_name

            FROM users

            INNER JOIN participants
                ON users.participant_id = participants.id

            INNER JOIN roles
                ON users.role_id = roles.id

            WHERE participants.id = :participant_id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['participant_id' => $participantId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->build($row);
    }

    /**
     * Delete a user
     * @param User $user The user to delete
     * @return void
     */
    public function delete(User $user): void {
        $sql = 'DELETE FROM users WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $user->getId()]);
    }

    // PRIVATE METHODS

    /**
     * Build a User object from a database row
     * @param array $row The database row
     * @return User The constructed User object
     */
    private function build(array $row): User {
        $participant = new Participant($row['email']);
        $participant->setId((int) $row['participant_id']);

        $role = new Role((int) $row['role_id'], $row['role_name'],);

        $user = new User(
            $participant,
            $row['name'],
            $row['password'],
            $role,
            $row['description'],
            $row['image'],
            $row['linkedin'],
            $row['phone']
        );

        $user->setId((int) $row['user_id']);

        return $user;
    }
}
