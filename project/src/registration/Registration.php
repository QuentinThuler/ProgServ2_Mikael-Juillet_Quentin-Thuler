<?php

namespace App\registration;

use App\participant\Participant;
use DateTime;

class Registration {
    private int $id;
    private int $event_id;
    private Participant $participant;
    private DateTime $registration_date;
    private bool $reminder_sent;
    private ?string $utm_source;
    private ?string $utm_medium;
    private ?string $utm_campaign;

    



    
}
