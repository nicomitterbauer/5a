<?php
// Gibt es die Variable
if (isset($errors)) {
    // Ausgabe der Fehlermeldungen
    if (is_array($errors) && count($errors) > 0) {
        echo '<ul>';
        for ($i = 0; $i < count($errors); $i++) {
            echo '<li>' . htmlspecialchars($errors[$i], ENT_QUOTES, 'UTF-8') . '</li>';
        }
        echo '</ul>';
    }
} else {
    echo '<p><strong>Lieber Freund der Sonne, bitte die Variable $errors deklarieren!</strong></p>';
}

?>