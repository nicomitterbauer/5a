<?php

// Aus jeder Tabelle wird eine Model-Klasse

class Mitglied {
    public int $id;
    public string $vorname;
    public string $nachname;
    public string $email;
    public string $passwort; // gehashedes Passwort
    // Damit __set() funktioniert, muss die Eigenschaft hier anders heißen als in der DB.
    public DateTime $geburtsdatumDateTime;
    public bool $is_admin;

    // PHP kann zwischen SQL Date/Datetime und PHP DateTime nicht automatisch konvertieren --> extra Funktion
    // __set() wird immer dann aufgerufen, wenn eine Eigenschaft gesetzt wird, die es nicht gibt.
    
    public function __set($property, $value)
        {
            if($property === 'geburtsdatum'){
                // DB: 2025-10-01
                // erzeuge DateTIme OBjekt
                $date = DateTime::createFromFormat('Y-m-d', $value);
                // setze das DateTime Objekt als Geburtsdatum im Objekt der Klasse Mitglied
                $this->geburtsdatumDateTime = $date;
                
            } else {
                $this->$property = $value;
            }
        }

}

class Kenntniss {
    public int $id;
    public string $bezeichnung;
}

class Veranstaltungsart {
    public int $id;
    public string $bezeichnung;
}
   

?> 