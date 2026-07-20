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
insert into role (name) values ('admin'), ('client');
INSERT INTO user (telephone, role_id)
VALUES ('0340000000', '1'),
    ('0331278201', '2');
INSERT INTO prefixe_operateur (code_prefixe, operateur_nom)
VALUES ('032', 'Orange'),
    ('033', 'Airtel'),
    ('034', 'Telma'),
    ('038', 'Bip');
INSERT INTO type_operation (libelle)
VALUES ('Depot'),
    ('Retrait'),
    ('Transfert')