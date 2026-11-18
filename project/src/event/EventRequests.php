<?php

require_once __DIR__ . '/Event.php';

class EventRequests
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(Event $event): ?int
    {
        $sql = "INSERT INTO events (title, description, tagline, address, city, room, date_time, image, created_by) 
                VALUES (:title, :description, :tagline, :address, :city, :room, :date_time, :image, :creator_id)";

        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            'title'       => $event->getTitle(),
            'description' => $event->getDescription(),
            'tagline'     => $event->getTagline(),
            'address'     => $event->getAddress(),
            'city'        => $event->getCity(),
            'room'        => $event->getRoom(),
            'date_time'   => $event->getDateTime()->format('Y-m-d H:i:s'),
            'image'       => $event->getImagePath(),
            'creator_id'  => $event->getCreatorId()
        ]);

        if ($success) {
            return (int) $this->pdo->lastInsertId();
        }

        return null;
    }

    /**
     * @return Event[]
     */
    public function getAllEvents(): array
    {
        $sql = "SELECT * FROM events ORDER BY date_time ASC";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->hydrateEvent($row);
        }

        return $events;
    }

    public function getEventById(int $id): ?Event
    {
        $sql = "SELECT * FROM events WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->hydrateEvent($row);
    }

    // Transforme un tableau associatif brut issu de la base de données en un objet PHP fortement typé.
    private function hydrateEvent(array $row): Event
    {
        $event = new Event(
            $row['title'],
            $row['description'],
            $row['tagline'],
            $row['address'],
            $row['city'],
            $row['room'],
            new DateTime($row['date_time']),
            $row['image'],
            (int) $row['created_by']
        );

        $event->setEventAllValues(
            (int) $row['id'],
            new DateTime($row['creation_date'])
        );

        return $event;
    }

    public function getUpcomingEvents(): array
    {
        $sql = "SELECT * FROM events WHERE date_time >= NOW() ORDER BY date_time ASC";
        $stmt = $this->pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $events = [];
        foreach ($rows as $row) {
            $events[] = $this->hydrateEvent($row);
        }

        return $events;
    }
}
