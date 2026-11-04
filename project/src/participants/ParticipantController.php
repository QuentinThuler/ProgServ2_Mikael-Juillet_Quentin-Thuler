<?php
require_once '../project/src/services/tokenServices.php'; 

class ParticipantController {
    private ParticipantRequests $participantRequests;

    public function __construct(ParticipantRequests $participantRequests) {
        $this->participantRequests = $participantRequests;
    }

    public function index(): array {
        return $this->participantRequests->getAllParticipants();
    }

    public function create(string $email) {

        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('L’adresse e-mail est invalide.');
        }

        $participant = new Participant($email);

        $token = generateToken(6);
        $participant->setToken(encriptToken($token));
        error_log($token);

        /*
            generate random char and hash -> service 
            create participant in db with token and duration of 3h
            send mail (log at first)
        */

        /* does it exist if yes update values */

        return $this->participantRequests->create($participant);
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
