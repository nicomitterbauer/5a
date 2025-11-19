CREATE DATABASE webshop;
USE webshop;

CREATE TABLE users
(
    id INT AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    email VARCHAR(320) NOT NULL,
    password VARCHAR(300) NOT NULL,
    is_admin BOOL NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY(email)
)

// statt BOOL richtig wäre: TINYINT(1)