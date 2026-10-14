USE my_database;
INSERT INTO roles (
    name
) VALUES ('Administrator'), ('User');

-- Insert default admin as a participant
INSERT INTO participants (
    email,
    is_approved,
    validation_number
)
VALUES (
    'admin@admin.com',
    TRUE,
    SUBSTRING(MD5(RAND()), 1, 6) -- Generates a 6 character long substring from the MD5 hash of a random value.
);

INSERT INTO users (
    participant_id,
    name,
    password,
    id_roles
)
VALUES (
    (SELECT id
     FROM participants
     WHERE email = 'admin@admin.com'),
    'admin',
    '$2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm', -- ex. Password = $2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm
    (SELECT id FROM roles WHERE name = 'Administrator')
);
