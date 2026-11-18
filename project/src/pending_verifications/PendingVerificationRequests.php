<?php

class PendingVerificationRequests {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    public function create(PendingVerification $pendingVerification): void {
        $query = "INSERT INTO pending_verifications (email, token_hash, token_expires_at, purpose, payload, attempt_count) VALUES (:email, :token_hash, :token_expires_at, :purpose, :payload, :attempt_count)";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            ':email' => $pendingVerification->getEmail(),
            ':token_hash' => $pendingVerification->getTokenHash(),
            ':token_expires_at' => $pendingVerification->getTokenExpiresAt()->format('Y-m-d H:i:s'),
            ':purpose' => $pendingVerification->getPurpose(),
            ':payload' => json_encode($pendingVerification->getPayload()),
            ':attempt_count' => $pendingVerification->getAttemptCount()
        ]);
    }

    public function getByEmail(string $email): ?PendingVerification {
        $query = "SELECT * FROM pending_verifications WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->execute([':email' => $email]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return new PendingVerification(
                $result['email'],
                $result['token_hash'],
                $result['purpose'],
                new DateTime($result['token_expires_at']),
                json_decode($result['payload'], true),
                (int)$result['attempt_count']
            );
        }

        return null;
    }

    public function update(PendingVerification $pendingVerification): void {
        $query = "UPDATE pending_verifications SET token_hash = :token_hash, token_expires_at = :token_expires_at, purpose = :purpose, payload = :payload, attempt_count = :attempt_count WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->execute([
            ':email' => $pendingVerification->getEmail(),
            ':token_hash' => $pendingVerification->getTokenHash(),
            ':token_expires_at' => $pendingVerification->getTokenExpiresAt()->format('Y-m-d H:i:s'),
            ':purpose' => $pendingVerification->getPurpose(),
            ':payload' => json_encode($pendingVerification->getPayload()),
            ':attempt_count' => $pendingVerification->getAttemptCount()
        ]);
    }

    public function delete(string $email): void {
        $query = "DELETE FROM pending_verifications WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->execute([':email' => $email]);
    }
}
