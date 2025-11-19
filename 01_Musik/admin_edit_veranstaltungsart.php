<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

require_once 'db/dbaccess.inc.php';
$dba = new DbAccess();
$errors = [];

if(!isset($_GET['id'])){
    exit('Get-Parameter id fehlt');
}
if(filter_var($_GET['id'], FILTER_VALIDATE_INT) === false){
    exit('Get-Parameter id ungültig');
}

$id = $_GET['id'];

$veranstaltungsart = $dba->getVeranstaltungsartById($id);
if($veranstaltungsart === false){
    exit('Keine Veranstaltungsart mit der ID gefunden');
}

if(isset($_POST['bt_update_veranstaltungsart'])){
    print_r($_FILES);
    $bezeichnungva = trim($_POST['bezeichnung']);

    if(empty($bezeichnungva)){
        $errors[] = 'Bezeichnung eingeben';
    }
    // hole di VA
    $va = $dba->getVeranstaltungsartByBezeichnung($bezeichnungva);

    if($va !== false && $va->id != $id){
        $errors[] = 'Bezeichnung bereits vorhanden';
    }

    $veranstaltungsart->bezeichnung = $bezeichnungva;

    if(count($errors) == 0){
        $dba->updateVeranstaltungsart($veranstaltungsart);
        header('Location: veranstaltungsart.php');
        exit();
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>veranstaltungsart bearbeiten</title>
</head>
<body>
    <h1>veranstaltungsart bearbeiten</h1>
    <?php include 'mainmenu.inc.php'; ?>
    <?php include 'showerrors.inc.php'; ?>
    <form action="admin_edit_veranstaltungsart.php?id=<?php echo $veranstaltungsart->id; ?>" method="POST">
        <label>Bezeichnung:</label><br>
        <input type="text" name="bezeichnung" value="<?php echo $veranstaltungsart->bezeichnung; ?>"><br>

        <button name="bt_update_veranstaltungsart">bearbeiten</button>
    </form>
</body>
</html>