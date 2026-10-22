USE gatherly;

INSERT INTO
    roles (name)
VALUES
    ('Administrator'),
    ('User');

-- Insert default admin as a participant
INSERT INTO
    participants (email, is_confirmed, token_hash, token_expires_at)
VALUES
    (
        'admin@clubphoto.ch',
        TRUE,
        SHA2(SUBSTRING(MD5(RAND()), 1, 6), 256), -- Generates a 6 character long substring from the MD5 hash of a random value then use encription to store it.
        NOW() + INTERVAL 30 DAY
    );

INSERT INTO users
    (participant_id, name, password, description, role_id)
VALUES
    ((SELECT id FROM participants WHERE email = 'admin@clubphoto.ch'),
     'admin_photo',
     '$2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm', -- ex. Password = $2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm
     'Compte administrateur du Club Photo.',
     (SELECT id FROM roles WHERE name = 'Administrator'));
