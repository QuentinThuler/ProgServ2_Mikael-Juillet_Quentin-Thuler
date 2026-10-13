USE my_database;
INSERT INTO roles (
    name
) VALUES ('Administrator'), ('User');

-- Insert Default Admin
INSERT INTO participants (
    email,
    approved,
    validation_number
)
VALUES (
    'admin@admin.com',
    TRUE,
    SUBSTRING(MD5(RAND()), 1, 6) -- Generates a 6 character long substring from the MD5 hash of a random value.
);

INSERT INTO users (
    name,
    password,
    id_roles
)
VALUES (
    'admin',
    'hash_du_mot_de_passe', -- ex. Password = $2y$12$eY0NRHBikC0h88CBCtWA7uHgcR/Kjwu2erd14tZ4PNb/G8ThYuNFm
    (SELECT id FROM roles WHERE name = 'Administrator')
);

UPDATE participants
SET id_users = LAST_INSERT_ID()
WHERE email = 'admin@admin.com';


