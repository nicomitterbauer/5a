<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once 'db/dbaccess.inc.php';
$dba = new DbAccess();
$errors = [];

if(isset($_POST['bt_add_kenntniss'])){
    $bezeichnung = trim($_POST['bezeichnung']);

    if(empty($bezeichnung)){
        $errors[] = 'Bezeichnung eingeben';
    } else if ($dba->getKenntnissByBezeichnung($bezeichnung) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

    // Wenn es keine Fehlermeldungen gibt, dann speichern

    if(count($errors) == 0){
        $dba->createKentniss($bezeichnung);
        header('Location: kenntnisse.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kenntnisse</title>
</head>
<body>
    <h1>Kenntnisse</h1>
    <?php include 'mainmenu.inc.php'; ?> 
    <?php include 'showerrors.inc.php'; ?>
    <h2>Kenntnisse anlegen</h2>
    <form action="kenntnisse.php" method="POST">
        <label>Bezeichnung:</label><br>
        <input type="text" name="bezeichnung"><br>

        <button name="bt_add_kenntniss">Neue kenntnisse Speichern</button>

    </form>

    <h2>Alle Kenntnisse</h2>
    <?php
    // Lade Kenntnisse als Array von Objekten der Klasse Kenntniss
    $kenntnisse = $dba->getKenntnisse();
    ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bezeichnung</th>
                <th>Bearbeiten</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mit einer Schleife über das Array iterieren ind für jedes Element eine Tabellenzeile ausgeben
            foreach($kenntnisse as $k){
                echo '<tr>';
                echo '<td>' . htmlspecialchars($k->id) . '</td>';
                echo '<td>' . htmlspecialchars($k->bezeichnung) . '</td>';
                echo '<td><a href="admin_edit_kenntnisse.php?id='.$k->id.'">Bearbeiten</a></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>