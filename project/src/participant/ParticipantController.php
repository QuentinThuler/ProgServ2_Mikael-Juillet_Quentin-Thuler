<?php

namespace App\participant;

use InvalidArgumentException;

class ParticipantController {
    private ParticipantRequests $participantRequests;

    public function __construct(ParticipantRequests $participantRequests) {
        $this->participantRequests = $participantRequests;
    }

    public function index(): array {
        return $this->participantRequests->getAll();
    }

    public function create(string $email): void {

        $email = trim($email);
        $this->validateEmail($email);

        $participant = new Participant($email);
        $existingParticipant = $this->participantRequests->getByEmail($email);

        if (!$existingParticipant) {
            $this->participantRequests->create($participant);
        } else {
            throw new InvalidArgumentException('Un participant avec cet e-mail existe déjà.');
        }
    }

    // PRIVATE METHODS

    private function validateEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Adresse e-mail invalide.');
        }
    }
}
