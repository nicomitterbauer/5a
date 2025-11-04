DDL: Data Definition Language
DML Data manipulation Language

CREATE TABLE mitglied
(
    id INT AUTO_INCREMENT NOT NULL,
    vorname VARCHAR(100) NOT NULL,
    nachname VARCHAR(100) NOT NULL,
    email VARCHAR(320) NOT NULL,
    passwort VARCHAR(200) NOT NULL,
    geburtsdatum DATE NOT NULL,
    is_admin BOOLEAN NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (email)
);

CREATE TABLE if not exists kenntniss
(
    id INT AUTO_INCREMENT NOT NULL,
    bezeichnung VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (bezeichnung)
);

CREATE TABLE IF NOT EXISTS veranstaltungsart
(
    id INT AUTO_INCREMENT NOT NULL,
    bezeichnung VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (bezeichnung)
);