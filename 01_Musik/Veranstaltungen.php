<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once 'db/dbaccess.inc.php';
$dba = new DbAccess();
$errors = [];

if(isset($_POST['bt_create_veranstaltung'])){
    $thema = trim($_POST['thema']);
    $veranstaltungsartId = trim($_POST['veranstaltungsart_id']);

    // Zeitpunkt in datetime umwandeln
    $zeitpunkt = DateTime::createFromFormat('d.m.Y H:i', $_POST['zeitpunkt']);
    // Datei upload /.     Die hochgeladene Datei soll im uploads_ordner gespeichert werden
    $originalFilename = $_FILES['bild']['name'];
    // Pfad, wo die Datei gespeichert werden soll
    $uploaddir = 'uploads/';
    $uploadpath = $uploaddir . $originalFilename;

    // Verschiebe die hochgeladene Datei vom temporären Speicherort in unseren uploads-Ordner
    if(move_uploaded_file($_FILES['bild']['tmp_name'], $uploadpath)){
        // Alles oky
        echo "Datei erfolgreich hochgeladen.\n";
    } else {
        $errors[] = 'Upload Fehlgeschlagen! Keine Date? Fehlende Rechte am Uploads-Ordner?';

    }

    if(empty($thema)){
        $errors[] = 'Thema eingeben';
    }
    if(empty($veranstaltungsartId) || filter_var($veranstaltungsartId, FILTER_VALIDATE_INT) === false){
        $errors[] = 'Ungültige Veranstaltungsart';
    }
    if(empty($zeitpunkt)){
        $errors[] = 'Zeitpunkt eingeben';
    }

    if(count($errors) == 0){
        $dba->createVeranstaltung($thema, $veranstaltungsartId, $zeitpunkt);
        header('Location: veranstaltungen.php');
        exit();
    }
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
    <h1>Veranstaltungen</h1>
    <?php include 'mainmenu.inc.php'; ?>
    <?php include 'showerrors.inc.php'; ?>

    <h2>Neue Veranstaltung</h2>
    <form action="Veranstaltung.php" method="POST"></form>
        <label>Thema (TItel):</label><br>
        <input type="text" name="thema"><br>

        <label>Veranstaltungsart</label><br>
        <select name="veranstaltungsart_id">
            <?php
            // Lade alle Veranstaltungsarten
            $veranstaltungsarten = $dba->getVeranstaltungsart();
            // gebe jede Veranstaltungsart mit <option> aus
            // $v Datentyp: Veranstaltungsart
            foreach($veranstaltungsarten as $v){
                echo '<option value="' . $v->id . '">' . htmlspecialchars($v->bezeichnung) . '</option>';
            }
            ?>
        </select><br>

        <label>Veranstaltungsdatum- und Zeitpunkt(TT.MM.JJJJ hh:mm)</label>
        <input type="text" name="zeitpunkt"><br>

        <label>Bild </label><br>
        <input type="file" name="bild"><br>

        <button name="bt_create_veranstaltung">Veranstaltung erstellen</button>
</body>
</html>