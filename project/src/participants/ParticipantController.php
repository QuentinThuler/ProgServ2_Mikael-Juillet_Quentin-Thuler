<?php
require_once __DIR__ . '/../services/tokenServices.php';

class ParticipantController {
    private ParticipantRequests $participantRequests;

    public function __construct(ParticipantRequests $participantRequests) {
        $this->participantRequests = $participantRequests;
    }

    public function index(): array {
        return $this->participantRequests->getAllParticipants();
    }

    public function create(string $email): void {

        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('L’adresse e-mail est invalide.');
        }

        $participant = new Participant($email);
        $existingParticipant = $this->participantRequests->getParticipantByEmail($email);

        if (!$existingParticipant) {
            $this->participantRequests->create($participant);
        } else {
            $participant = $existingParticipant;
        }

        if ($participant->getTokenHash() && $participant->getTokenExpiresAt() > new DateTime()) {
            throw new Exception('Un token a déjà été généré pour cet e-mail et est encore valide.');
        } else {
            $token = generateToken(6);
            $participant->setToken(encriptToken($token));
            error_log($token);
            $this->participantRequests->updateToken($participant);
        }
    }

    public function validateToken(string $email, string $token): void {
        /* 
        in an other form file add a validation (add hiden email /and event id.)
        hash token 
        get participant, check if there token and if it is the same token 
        update status, validate 
        how to link event and participan
        */
    }
}
