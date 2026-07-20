-- Création de la table user
CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    mdp TEXT NOT NULL
);
-- Insertion des données
INSERT INTO user (telephone, mdp)
VALUES ('0342334509', 'admin123'),
    ('0331278201', 'emp123');