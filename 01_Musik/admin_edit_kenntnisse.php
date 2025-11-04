<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once 'db/dbaccess.inc.php';
$dba = new DbAccess();
$errors = [];

// Welches Kenntnis soll bearbeitet werden?
if(!isset($_GET['id'])){
    exit('Get-Parameter id fehlt');
}
if(filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
    exit('Get-Parameter id ungültig');
}
// lese ID ein
$id = $_GET['id'];

// Lade Kenntnis mit der ID
$kenntniss = $dba->getKenntnissById($id);
// Wenn es keine Kenntnis mit der ID gibt, dann Fehlermeldung ausgeben
if($kenntniss === false){
    exit('Keine Kenntnis mit der ID gefunden');
}

// wurde der button gedrückt?
if(isset($_POST['bt_update_kenntnis'])){
    $bezeichnung = trim($_POST['bezeichnung']);

    if(empty($bezeichnung)){
        $errors[] = 'Bezeichnung eingeben';
    }
    if ($dba->getKenntnissByBezeichnung($bezeichnung) !== false){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

    // Aktualisiere das Objekt
    $kenntniss->bezeichnung = $bezeichnung;

    if(count($errors) == 0){
        $dba->updateKenntniss($kenntniss);
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
    <title>kenntnis bearbeiten</title>
</head>
<body>
    <h1>Kenntnis bearbeiten</h1>
    <?php include 'mainmenu.inc.php'; ?>
    <?php include 'showerrors.inc.php'; ?>
    <!-- die ID muss als Get-Parameter mitgesendet werden! Damit man weiß was bearbeitet wird -->
    <form action="admin_edit_kenntnisse.php?id=<?php echo $kenntniss->id; ?>" method="POST">
        <label>Bezeichnung:</label><br>
        <input type="text" name="bezeichnung" value="<?php echo $kenntniss->bezeichnung; ?>"><br>

        <button name="bt_update_kenntnis">bearbeiten</button>
    </form>
</body>
</html>