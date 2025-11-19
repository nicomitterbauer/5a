<?php
require_once 'models.inc.php';

class DbAccess{
    private PDO $conn;

    public function __construct()
    {
        $this->conn new PDO('mysql:host=localhost; dbname=webshop', username: 'root', password: '');
        $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    public function registerUser(string $firstname, string $lastname, string $email, string $password, bool $is_admin): int
    {
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = '
        INSERT INTO users
        (firstname, lastname, email, password, is_admin)
        VALUES
        (:firstname, :lastname, :email, :password, :is_admin)
        ';

        $ps = $this->conn->prepare($sql);
        $ps->bindValue('firstname', $firstname);
        $ps->bindValue('lastname', $lastname);
        $ps->bindValue('email', $email);
        $ps->bindValue('password', $password);
        $ps->bindValue('is_admin', $is_admin, PDO::PARAM_BOOL);
        $ps->execute();
        return $this->conn->lastInsertId();

    }

}



?>