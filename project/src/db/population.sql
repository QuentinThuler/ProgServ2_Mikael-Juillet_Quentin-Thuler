USE gatherly;

INSERT INTO
    roles (name)
VALUES
    ('Administrator'),
    ('User');

-- Insert default admin as a participant
INSERT INTO
    participants (email)
VALUES
    ('admin@clubphoto.ch');

INSERT INTO users
    (participant_id, name, password, description, role_id)
VALUES
    ((SELECT id FROM participants WHERE email = 'admin@clubphoto.ch'),
     'admin_photo',
     '$2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm', -- ex. Password = $2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm
     'Compte administrateur du Club Photo.',
     (SELECT id FROM roles WHERE name = 'Administrator'));
