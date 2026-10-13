USE my_database;

CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    linkedin VARCHAR(255),
    phone VARCHAR(20),
    id_roles INT NOT NULL,

    CONSTRAINT fk_users_roles
        FOREIGN KEY (id_roles)
        REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS participants (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    approved BOOLEAN NOT NULL,
    validation_number VARCHAR(6) NOT NULL,
    id_users INT,

    CONSTRAINT fk_participants_users
        FOREIGN KEY (id_users)
        REFERENCES users(id)
);

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

CREATE TABLE IF NOT EXISTS subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_events INT NOT NULL,
    id_participants INT NOT NULL,

    CONSTRAINT fk_subscriptions_events
        FOREIGN KEY (id_events)
        REFERENCES events(id),

    CONSTRAINT fk_subscriptions_participants
        FOREIGN KEY (id_participants)
        REFERENCES participants(id),

    CONSTRAINT unique_subscriptions
        UNIQUE (id_events, id_participants)
);
