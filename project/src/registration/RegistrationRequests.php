<?php

namespace App\registration;

use PDO;

class RegistrationRequests {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}
