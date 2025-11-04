<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once 'db/dbaccess.inc.php';
$dba = new DbAccess();
$errors = [];

if(isset($_POST['bt_add_veranstaltungsart'])) {
    $bezeichnungva = trim($_POST['bezeichnungva']);

    if(empty($bezeichnungva)){
        $errors[] = "Bitt etwas eingeben";
    } else if ($dba->getVeranstaltungsartByBezeichnung($bezeichnungva) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }
    

    if(count($errors) == 0){
        $dba->createVeranstaltungsart($bezeichnungva);
        header('Location: veranstaltungsart.php');
        exit();
    }


}

// Löschen einer Veranstaltungsart verarbeiten
if(isset($_POST['delete_va'])){
        $id = $_POST['id'];
        $dba->deleteVeranstaltungsart($id);
    
    header('Location: veranstaltungsart.php');
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veranstaltungsart</title>
</head>
<body>
    <h1>Veranstaltungsart</h1>
    <?php include 'mainmenu.inc.php'; ?>
    <?php include 'showerrors.inc.php'; ?>

    <form action="veranstaltungsart.php" method="POST">
        <label>Veranstaltung Erstellen</label><br>
        <input type="text" name="bezeichnungva"><br>

        <button name="bt_add_veranstaltungsart">Speichern</button>
    </form>
    <?php
    $veranstaltungsart = $dba->getVeranstaltungsart();
    ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Bezeichnung</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($veranstaltungsart as $v){
                echo '<tr>';
                echo '<td>' . htmlspecialchars($v->id) . '</td>';
                echo '<td>' . htmlspecialchars($v->bezeichnung) . '</td>';
                echo '<td>';
                // Bearbeiten Link
                echo '<a href="admin_edit_veranstaltungsart.php?id='.$v->id.'">Bearbeiten</a> ';
                // Lösch-Formular
                echo '<form method="POST" action="veranstaltungsart.php">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($v->id) . '">';
                echo '<button name="delete_va">Löschen</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</body>
</html>