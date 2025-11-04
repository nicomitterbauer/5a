<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'db/dbaccess.inc.php';
// erzeugt ein neues Objekt der Klasse DbAccess
$dba = new DbAccess();

?>

<?php
$errors = [];

if (isset($_POST['bt_login'])) {
    // Daten aus dem Formular holen
    $email = trim($_POST['email']);
    $passwort = trim($_POST['passwort']);

    if (empty($email)) {
        $errors[] = 'Email eingeben';
    }
    if (empty($passwort)) {
        $errors[] = 'Passwort eingeben';

    }
    if (count($errors) == 0) {
        $success = $dba->login($email, $passwort);
        if ($success) {
            header('Location: dashboard.php');
            exit();
        } else {
            $errors[] = 'Email / Passwort falsch';
        }
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
    <h1>Login</h1>
    <?php include 'mainmenu.inc.php'; ?>
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

<!-- action: wohin sollen die Daten gesendet werden? --->
<!-- method: mit welcher Methode (POST oder GET) sollen die Daten gesendet werden? --->
<!-- GET: Daten werden in der URL übergeben (sichtbar) --->
<!-- POST: Daten werden im Body der HTTP-Anfrage gesendet (nicht sichtbar) --->

    <form action="login.php" method="POST">
        <label>E-Mail</label><br>
        <input type="text" name="email"><br>

        <label>Password</label><br>
        <input type="password" name="passwort"><br>

        <button name="bt_login">Login</button>
</body>
</html>