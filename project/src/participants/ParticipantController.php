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

        $this->validateEmail($email);

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
        $email = trim($email);
        $token = trim($token);

        $this->validateEmail($email);

        // Vérifie le format du code généré.
        if (strlen($token) !== 6) {
            throw new InvalidArgumentException('Le code de vérification est invalide.');
        }

        $participant = $this->participantRequests->getParticipantByEmail($email);

        if (!$participant) {
            throw new InvalidArgumentException('Aucun participant trouvé pour cet e-mail.');
        }

        if ($participant->getTokenExpiresAt() > new DateTime() && compareToken($token, $participant->getTokenHash())) {
            // TODO : Valider l'inscription du participant.
            $participant->setParticipantAllValues(
                $participant->getId(),
                true,
                null,
                null
            );
            $this->participantRequests->updateToken($participant);
        } else {
            throw new InvalidArgumentException('Code incorrect ou expiré. Veuillez vérifier votre code.');
        }
    }

    private function validateEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Adresse e-mail invalide.');
        }
    }
}
