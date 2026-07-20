-- Création de la table user
CREATE TABLE IF NOT EXISTS user (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    telephone TEXT NOT NULL UNIQUE,
    role_id INTEGER NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(id)
);
create table if not exists role (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL UNIQUE
);
-- Création de la table prefixe_operateur
CREATE TABLE IF NOT EXISTS prefixe_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code_prefixe TEXT NOT NULL UNIQUE,
    operateur_nom TEXT NOT NULL
);
-- Création de la table client
CREATE TABLE IF NOT EXISTS client (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user INTEGER NOT NULL,
    solde REAL NOT NULL DEFAULT 0,
    date_creation TEXT NOT NULL,
    id_prefixe INTEGER NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(id),
    FOREIGN KEY (id_prefixe) REFERENCES prefixe_operateur(id)
);
-- Création de la table type_operation
CREATE TABLE IF NOT EXISTS type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL UNIQUE
);
-- Création de la table tranche_frais
CREATE TABLE IF NOT EXISTS tranche_frais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    montant_min REAL NOT NULL,
    montant_max REAL NOT NULL,
    frais REAL NOT NULL,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);
-- Création de la table operation
CREATE TABLE IF NOT EXISTS operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date_operation TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    montant REAL NOT NULL,
    frais_applique REAL NOT NULL DEFAULT 0,
    id_client_emetteur INTEGER NOT NULL,
    id_client_destinataire INTEGER,
    id_type_operation INTEGER NOT NULL,
    FOREIGN KEY (id_client_emetteur) REFERENCES client(id),
    FOREIGN KEY (id_client_destinataire) REFERENCES client(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);
-- Insertion des données
insert into role (name)
values ('admin'),
    ('client');
INSERT INTO user (telephone, role_id)
VALUES ('0340000000', '1');
INSERT INTO prefixe_operateur (code_prefixe, operateur_nom)
VALUES ('032', 'Orange'),
    ('033', 'Airtel'),
    ('034', 'Telma'),
    ('038', 'Bip');
INSERT INTO type_operation (libelle)
VALUES ('Depot'),
    ('Retrait'),
    ('Transfert');

INSERT INTO tranche_frais (montant_min, montant_max, frais, id_type_operation)
VALUES
    -- Retrait (id = 2)
    (100, 1000, 50, 2),
    (1001, 5000, 50, 2),
    (5001, 10000, 100, 2),
    (10001, 25000, 200, 2),
    (25001, 50000, 400, 2),
    (50001, 100000, 800, 2),
    (100001, 250000, 1500, 2),
    (250001, 500000, 1500, 2),
    (500001, 1000000, 2500, 2),
    (1000001, 2000000, 3000, 2),
    -- Transfert (id = 3)
    (100, 1000, 50, 3),
    (1001, 5000, 50, 3),
    (5001, 10000, 100, 3),
    (10001, 25000, 200, 3),
    (25001, 50000, 400, 3),
    (50001, 100000, 800, 3),
    (100001, 250000, 1500, 3),
    (250001, 500000, 1500, 3),
    (500001, 1000000, 2500, 3),
    (1000001, 2000000, 3000, 3);

CREATE VIEW IF NOT EXISTS vue_historique AS
SELECT 
    o.id,
    o.date_operation,
    o.montant,
    o.frais_applique,
    t.libelle AS type_operation,
    u_em.telephone AS emetteur_telephone,
    u_dest.telephone AS destinataire_telephone
FROM operation o
JOIN type_operation t ON o.id_type_operation = t.id
JOIN client c_em ON o.id_client_emetteur = c_em.id
JOIN user u_em ON c_em.id_user = u_em.id
LEFT JOIN client c_dest ON o.id_client_destinataire = c_dest.id
LEFT JOIN user u_dest ON c_dest.id_user = u_dest.id;