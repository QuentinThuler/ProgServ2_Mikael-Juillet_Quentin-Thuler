USE my_database;

-- 1. Table des rôles
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Table des participants (socle basé sur l'email pour toute inscription)
CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE
);

-- 3. Table des utilisateurs (compte optionnel lié à un participant)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    participant_id INT NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    linkedin VARCHAR(255),
    phone VARCHAR(20),
    id_roles INT NOT NULL,

    CONSTRAINT fk_users_participants
        FOREIGN KEY (participant_id)
        REFERENCES participants(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_users_roles
        FOREIGN KEY (id_roles)
        REFERENCES roles(id)
);

-- 4. Table des événements
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    tagline VARCHAR(100) NOT NULL,
    date_time DATETIME NOT NULL,
    adresse VARCHAR(255) NOT NULL,
    city VARCHAR(150) NOT NULL,
    room VARCHAR(64),
    image VARCHAR(255),
    creation_date DATETIME NOT NULL,
    id_users INT NOT NULL,

    CONSTRAINT fk_events_users
        FOREIGN KEY (id_users)
        REFERENCES users(id)
);

-- 5. Table d'association des inscriptions (porte les métadonnées de validation par événement)
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_events INT NOT NULL,
    id_participants INT NOT NULL,
    subscription_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_approved BOOLEAN NOT NULL DEFAULT FALSE,
    validation_number VARCHAR(6),
    reminder_sent BOOLEAN NOT NULL DEFAULT FALSE,
    origin_channel VARCHAR(100),

    CONSTRAINT fk_subscriptions_events
        FOREIGN KEY (id_events)
        REFERENCES events(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_subscriptions_participants
        FOREIGN KEY (id_participants)
        REFERENCES participants(id)
        ON DELETE CASCADE,

    CONSTRAINT unique_subscriptions
        UNIQUE (id_events, id_participants)
);