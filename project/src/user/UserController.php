<?php

namespace App\user;

use App\participant\ParticipantRequests;
use App\pendingVerifications\IVerificationHandler;
use App\role\RoleRequests;
use InvalidArgumentException;
use JsonSerializable;

class UserController implements IVerificationHandler {
    private UserRequests $userRequests;
    private ParticipantRequests $participantRequests;
    private RoleRequests $roleRequests;

    public function __construct(
        UserRequests $userRequests,
        ParticipantRequests $participantRequests,
        RoleRequests $roleRequests
    ) {
        $this->userRequests = $userRequests;
        $this->participantRequests = $participantRequests;
        $this->roleRequests = $roleRequests;
    }

    public function index(): array {
        return $this->userRequests->getAllUsers();
    }

    /**
     * Show a user by their ID
     * @param int $id The user ID
     * @return ?User The user if found, null otherwise
     */
    public function show(int $id): ?User {
        return $this->userRequests->getUserById($id);
    }

    /**
     * Create a new user
     * @param string $email The user's email
     * @param string $name The user's name
     * @param string $password The user's password
     * @param ?string $description The user's description (optional)
     * @param ?string $image The user's image URL (optional)
     * @param ?string $linkedin The user's LinkedIn profile URL (optional)
     * @param ?string $phone The user's phone number (optional)
     * @return void
     */
    public function create(
        string $email,
        string $name,
        string $password,
        ?string $description = null,
        ?string $image = null,
        ?string $linkedin = null,
        ?string $phone = null
    ): void {
        $email = trim($email);
        $name = trim($name);

        $this->userValidation([
            'email' => $email,
            'name' => $name,
            'password' => $password
        ]);

        // Get the participant by email
        $participant = $this->participantRequests->getParticipantByEmail($email);

        // If the participant does not exist, create a new participant
        if (!$participant) {
            $newParticipant = new Participant($email);
            $this->participantRequests->create($newParticipant);
            $participant = $this->participantRequests->getParticipantByEmail($email);
        }

        // Check if a user already exists for this participant
        $existingUser = $this->userRequests->getUserByParticipantId(
            $participant->getId()
        );
        if ($existingUser) throw new RuntimeException('Un compte existe déjà avec cette adresse e-mail.');

        // TODO : gérer la validation. 
        if (!$participant->isConfirmed()) {
            throw new RuntimeException(
                'Cette adresse e-mail doit être vérifiée avant de créer un compte.'
            );
        }

        // Get the role for the new user (default role is "User")
        $role = $this->roleRequests->getroleByName('User');
        if ($role === null) {
            throw new RuntimeException('Le rôle est introuvable.');
        }

        // Create the new user
        $user = new User(
            $participant,
            $name,
            password_hash($password, PASSWORD_DEFAULT),
            $role,
            $description,
            $image,
            $linkedin,
            $phone
        );

        $this->userRequests->create($user);
    }

    // TODO : implement update

    // DELETE
    public function delete(int $id): void {
        $user = $this->userRequests->getUserById($id);

        if ($user === null) {
            throw new Exception('Utilisateur introuvable.');
        }

        $this->userRequests->delete($user);
    }

    public function handle(string $email, JsonSerializable $payload): void {
    }

    // PRIVATE METHODS

    private function userValidation(array $data): void {
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Adresse e-mail invalide.');
        }

        if (!isset($data['name']) || trim($data['name']) === '') {
            throw new InvalidArgumentException('Le nom est obligatoire.');
        }

        if (!isset($data['password']) || strlen($data['password']) < 8) {
            throw new InvalidArgumentException(
                'Le mot de passe doit contenir au moins 8 caractères.'
            );
        }
    }

    private function createUserFromPayload(string $email, JsonSerializable $payload): void {
        $data = json_decode(json_encode($payload), true);

        if (!isset($data['name']) || !isset($data['password'])) {
            throw new InvalidArgumentException('Le nom et le mot de passe sont obligatoires.');
        }

        $this->create(
            $email,
            $data['name'],
            $data['password'],
            $data['description'] ?? null,
            $data['image'] ?? null,
            $data['linkedin'] ?? null,
            $data['phone'] ?? null
        );
    }
}
