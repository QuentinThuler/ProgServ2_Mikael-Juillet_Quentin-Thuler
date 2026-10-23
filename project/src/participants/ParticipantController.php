<?php
require_once __DIR__ . '/ParticipantRequests.php';

class ParticipantController {
    private ParticipantRequests $participantRequests;
    
    public function __construct(ParticipantRequests $participantRequests) {
        $this->participantRequests = $participantRequests;
    }

    public function index(): array {
        return $this->participantRequests->getAllParticipants();
    }
}
