USE gatherly;

-- 1. Roles
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- 2. Participants (one row per email address)
CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE, 
    
    -- Email confirmation (logged-out visitors only)
    is_confirmed BOOLEAN NOT NULL DEFAULT FALSE,
    token_hash CHAR(64) NULL UNIQUE,
    token_expires_at DATETIME NULL
);

-- 3. Users (optional account linked to a participant)
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    participant_id INT NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,   -- result of password_hash()
    description TEXT,
    image VARCHAR(255),
    linkedin VARCHAR(255),
    phone VARCHAR(20),
    role_id INT NOT NULL,

    CONSTRAINT fk_users_participants
        FOREIGN KEY (participant_id)
        REFERENCES participants(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_users_roles
        FOREIGN KEY (role_id)
        REFERENCES roles(id)
);

-- 4. Events
CREATE TABLE IF NOT EXISTS events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    tagline VARCHAR(100) NOT NULL,
    date_time DATETIME NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(150) NOT NULL,
    room VARCHAR(64),
    image VARCHAR(255),
    creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_by INT NOT NULL,

    INDEX idx_events_date_time (date_time),

    CONSTRAINT fk_events_created_by
        FOREIGN KEY (created_by)
        REFERENCES users(id)
);

-- 5. Registrations (one row per participant per event)
CREATE TABLE IF NOT EXISTS registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    participant_id INT NOT NULL,
    registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    reminder_sent BOOLEAN NOT NULL DEFAULT FALSE,

    -- Campaign tracking (from the UTM parameters in the URL)
    utm_source VARCHAR(100),
    utm_medium VARCHAR(100),
    utm_campaign VARCHAR(100),

    CONSTRAINT fk_registrations_events
        FOREIGN KEY (event_id)
        REFERENCES events(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_registrations_participants
        FOREIGN KEY (participant_id)
        REFERENCES participants(id)
        ON DELETE CASCADE,

    CONSTRAINT unique_registrations
        UNIQUE (event_id, participant_id)
);
