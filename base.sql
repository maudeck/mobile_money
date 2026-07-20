-- Création de la table user
CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    role TEXT NOT NULL
);

create table if not exists role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);
insert into role (name) values ('admin'), ('employe');

-- Insertion des données
INSERT INTO user (telephone, role)
VALUES ('0340000000', '1'),
    ('0331278201', '2');