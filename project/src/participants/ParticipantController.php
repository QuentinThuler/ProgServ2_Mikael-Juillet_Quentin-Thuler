<?php

class ParticipantController {
    private ParticipantRequests $participantRequests;
    
    public function __construct(ParticipantRequests $participantRequests) {
        $this->participantRequests = $participantRequests;
    }

    public function index(): array {
        return $this->participantRequests->getAllParticipants();
    }

    public function create(string $email){

        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('L’adresse e-mail est invalide.');
        }

        $participant = new Participant($email);

        /* validate email
            generate 
        */

        return $this->participantRequests->create($participant);
    }
}
