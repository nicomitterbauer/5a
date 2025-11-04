<?php
session_start();

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'db/dbaccess.inc.php';

// erzeugt ein neues Objekt der Klasse DbAccess, dabwei wird der Konstruktor der
// Klasse aufgerufen - damit wird die darin enthaltene Variable $conn mit der
// DB-Connection initialisiert.
$dba = new DbAccess();

$errors = [];

// Wurde das Formular abgesendet?
if(isset($_POST['bt_register'])){
    // Formulardaten einlesen
    echo 'b';
    $vorname = trim($_POST['vorname']);
    $nachname = trim($_POST['nachname']);
    $email = trim($_POST['email']);
    $passwort = trim($_POST['passwort']);
    $geburtsdatum = trim($_POST['geburtsdatum']);


    // Formularvalidierung
    if(empty($vorname)){
        $errors[] = 'Vorname eingeben';
    }
    if(strlen($nachname) == 0){
        $errors[] = 'Nachname eingeben';
    }
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE){
        $errors[] = "ungültiges E-Mail Format";
    } else if($dba->getMitgliedByEmail($email) !== false){
        $errors[] = "E-Mail bereits vergeben";
    }
    // TODO: prüfe ob Email nicht doppelt vorkkommt
    if(strlen($passwort) < 8){
        $errors[] = "Passwort muss mind. 8 Zeichen haben";
    }
    // Geburtsdatum: versuche das eingegebene Geburtsdatum in ein DateTime- Objekt zu parsen,
    // falls dies fehlschlegt wurde kein gültiges Geburtsdatum eingegeben.
    $geburtsdatumDateTime = DateTime::createFromFormat('d.m.Y', $geburtsdatum);
    if($geburtsdatumDateTime === false){
        $errors[] = 'Format des Geburtsdatum prüfen';
    }
     // Speichern in der Datenbank
        if(count($errors) == 0){
            $dba->registerUser($vorname, $nachname, $email, $passwort, $geburtsdatumDateTime);
            // zum Login weiterleiten
            header('Location: login.php');

    }
echo 'c';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Neuen Account erstellen</h1>
    
    <?php include 'mainmenu.inc.php'; ?>

    <main>
        <?php 
        // Ausgabe der Fehlermeldungen
        if(count($errors) > 0){
            echo '<ul>';
            for($i =0; $i < count($errors); $i++){
                echo '<li>' . htmlspecialchars($errors[$i]) . '</li>';
            }
            echo '</ul>';
        }
        ?>
    <form action="register.php" method="POST">
        <label>Vorname</label><br>
        <input type="text" name="vorname"><br>

        <label>Nachname</label><br>
        <input type="text" name="nachname"><br>

        <label>E-Mail</label><br>
        <input type="text" name="email"><br>

        <label>Password (mind. 8 Zeichen)</label><br>
        <input type="password" name="passwort"><br>

        <label>Geburtsdatum</label><br>
        <input type="text" name="geburtsdatum"><br>

        <button name="bt_register">Registrieren</button>
    </form>

</main>
    
</body>
</html>