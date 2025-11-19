<?php
require_once 'models.inc.php';

class DbAccess {
    // $conn ist eine Instanzvariable (ist damit in allen ethoden der Klasse verfügbar )
    private PDO $conn;

    public function __construct()
    {
        $this->conn = new PDO('mysql:host=localhost; dbname=musik', username: 'root', password: '');
        // Aktiviere alle PDO-Fehlermeldungen
        $this->conn->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    }

    // registriert einen User, gibt die ID des Users zurück
    public function registerUser(string $vorname, string $nachname, string $email, string $passwort, DateTime $geburtsdatum): int
    {
        // Passwort hashen!!
        $passwort = password_hash($passwort, PASSWORD_DEFAULT);
        // prepared Statement (Schutz vor SQL-Injection)
        $sql = '
        INSERT INTO mitglied
        (vorname, nachname, email, passwort, geburtsdatum, is_admin)
        VALUES
        (:email, :nachname, :email, :passwort, :geburtsdatum, :is_admin)
        ';

        // Jeden named Parameter mit dem Wert Ersetzten
        $ps = $this->conn->prepare($sql);
        $ps->bindValue('vorname', $vorname);
        $ps->bindValue('nachname', $nachname);
        $ps->bindValue('email', $email);
        $ps->bindValue('passwort', $passwort);
        // Datum in das Format YYYY-MM-DD umwandeln
        $ps->bindValue('geburtsdatum', $geburtsdatum->format('Y-m-d'));
        $ps->bindValue('is_admin', false, PDO::PARAM_BOOL); // bei boolean!
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getMitgliedByEmail($email) : Mitglied|false
    {
        $ps = $this->conn->prepare('
            SELECT *
            FROM mitglied
            WHERE email = :email
            ');
        
        $ps->bindValue('email', $email);
        $ps->execute();
        // fechtobject() ist für 0 oder 1 Datensätze
        // erstellt automatisch ein Objekt der Klasse Mitglied
        return $ps->fetchObject(Mitglied::class);

    }

    public function login(string $email, string $passwort) : bool
        {
            // Gibt es den User mit der Email?
            $user = $this->getMitgliedByEmail($email);
            if ($user === FALSE) {
                return false; // User existiert nicht
            }
            // Passwort überprüfen
            if (password_verify($passwort, $user->passwort) === FALSE) {
                return false; // Passwort falsch
            }
            // Email und Passwort stimmt überein
            // speichere wer sich angemeldet hat in der Sessioin
            $_SESSION['user_id'] = $user->id;
            $_SESSION['is_admin'] = $user->is_admin;

            return true;
        }

    public function createKentniss(string $bezeichnung): int
        {
            $sql = '
            INSERT INTO kenntniss
            (bezeichnung)
            VALUES
            (:bezeichnung)
            ';

            // Jeden named Parameter mit dem Wert Ersetzten
            $ps = $this->conn->prepare($sql);
            $ps->bindValue('bezeichnung', $bezeichnung);
            $ps->execute();
            return $this->conn->lastInsertId();
    }

    public function isLoggedIn(): bool
        {
            return isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0;
        }

    public function isAdmin(): bool
        {
            return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true;
        }

    public function getKenntnissByBezeichnung(string $bezeichnung): Kenntniss|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM kenntniss
                WHERE bezeichnung = :bezeichnung
                ');
            $ps->bindValue('bezeichnung', $bezeichnung);
            $ps->execute();
            // fetchobject() liefert ein Objekt der Klasse zurück, oder false wenn ncihts gefunden wurde 

            return $ps->fetchObject(Kenntniss::class);

        }

    public function getKenntnissById(string $id): Kenntniss|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM kenntniss
                WHERE id = :id
                ');
            $ps->bindValue('id', $id);
            $ps->execute();
            // fetchobject() liefert ein Objekt der Klasse zurück, oder false wenn ncihts gefunden wurde 

            return $ps->fetchObject(Kenntniss::class);

        }

    // Gibt ein array von Objekten der Klasse Kenntniss zurück
    public function getKenntnisse(): array
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM kenntniss
                ');
            $ps->execute();
            // fetchall() liefert ein Array mit allen Ergebnissen
            // PDO::FETCH_CLASS - jedes Ergebnis wird in ein Objekt der Klasse Kenntniss umgewandelt
            return $ps->fetchAll(PDO::FETCH_CLASS, Kenntniss::class);

    }

    // Das OBjekt der Klasse Kenntniss in Variable $k enthält bereits die 
    // neuen Daten, diese sollen in der DB gespeichert werden
    public function updateKenntniss(Kenntniss $k) :void {
        $ps = $this->conn->prepare('
            UPDATE kenntniss
            SET bezeichnung = :bezeichnung
            WHERE id = :id');
        $ps->bindValue('id', $k->id);
        $ps->bindValue('bezeichnung', $k->bezeichnung);
        $ps->execute();
    }

    public function createVeranstaltungsart(string $bezeichnungva) : INT{

        $ps = $this->conn->prepare('
        INSERT INTO veranstaltungsart
        (bezeichnung)
        VALUES
        (:bezeichnung)
        ');

        $ps->bindValue('bezeichnung', $bezeichnungva);
        $ps->execute();
        return $this->conn->lastInsertId();
    }

    public function getVeranstaltungsartByBezeichnung(string $bezeichnungva) : Veranstaltungsart|false {
        $ps = $this->conn->prepare('
        SELECT *
        FROM Veranstaltungsart
        WHERE bezeichnung = :bezeichnung
        ');

        $ps->bindValue('bezeichnung', $bezeichnungva);
        $ps->execute();

        return $ps->fetchObject(Veranstaltungsart::class);
    }

    public function getVeranstaltungsart(): array
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM veranstaltungsart
                ');
            $ps->execute();
            return $ps->fetchAll(PDO::FETCH_CLASS, Veranstaltungsart::class);

    }

    public function getVeranstaltungsartById(string $id): Veranstaltungsart|false
        {
            $ps = $this->conn->prepare('
                SELECT *
                FROM veranstaltungsart
                WHERE id = :id
                ');
            $ps->bindValue('id', $id);
            $ps->execute();
            return $ps->fetchObject(Veranstaltungsart::class);

        }
    
    public function updateVeranstaltungsart(Veranstaltungsart $v) :void {
        $ps = $this->conn->prepare('
            UPDATE veranstaltungsart
            SET bezeichnung = :bezeichnung
            WHERE id = :id');
        $ps->bindValue('id', $v->id);
        $ps->bindValue('bezeichnung', $v->bezeichnung);
        $ps->execute();
    }

    public function deleteVeranstaltungsart(int $id) : void {
    $ps = $this->conn->prepare('
        DELETE FROM veranstaltungsart
        WHERE id = :id
    ');
    $ps->bindValue('id', $id);
    $ps->execute();
    }

    public function createVeranstaltung(int $veranstaltungsartId, string $thema, DateTime $zeitpunkt, string $bild) : int {
        $ps = $this->conn->prepare('
            INSERT INTO veranstaltung
            (veranstaltungsart_id, thema, zeitpunkt, bild)
            VALUES
            (:veranstaltungsart_id, :thema, :zeitpunkt, :bild)
        ');
        $ps->bindValue('veranstaltungsart_id', $veranstaltungsartId);
        $ps->bindValue('thema', $thema);
        $ps->bindValue('zeitpunkt', $zeitpunkt->format('Y-m-d H:i:s'));
        $ps->bindValue('bild', $bild);
        $ps->execute();
        return $this->conn->lastInsertId();
    }
}
?>