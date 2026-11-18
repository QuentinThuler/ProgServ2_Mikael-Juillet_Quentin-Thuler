<?php

namespace App\Role;

use PDO;

class RoleRequests {
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * Get a role by its name
     * @param string $name The role name
     * @return ?Role The role if found, null otherwise
     */
    public function getRoleByName(string $name): ?Role {
        $sql = 'SELECT id, name FROM roles WHERE name = :name';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['name' => $name]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $role = new Role((int) $row['id'], $row['name']);

        return $role;
    }

    /**
     * Get a role by its ID
     * @param int $id The role ID
     * @return ?Role The role if found, null otherwise
     */
    public function getRoleById(int $id): ?Role {
        $sql = 'SELECT id, name FROM roles WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $role = new Role((int) $row['id'], $row['name']);

        return $role;
    }
}
