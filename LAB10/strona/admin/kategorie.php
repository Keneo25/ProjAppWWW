<?php
// Włączenie pliku konfiguracyjnego
require_once('../cfg.php');

// Funkcja do połączenia z bazą danych
function dbConnect() {
    global $dbhost, $dbuser, $dbpass, $baza;
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $link;
}

// Funkcja do dodawania kategorii
function DodajKategorie($nazwa, $matka_id = 0) {
    $link = dbConnect();
    $query = "INSERT INTO kategorie (matka_id, nazwa) VALUES ('$matka_id', '$nazwa')";
    mysqli_query($link, $query);
    mysqli_close($link);
}

// Funkcja do usuwania kategorii
function UsunKategorie($id) {
    $link = dbConnect();
    $query = "DELETE FROM kategorie WHERE id='$id'";
    mysqli_query($link, $query);
    mysqli_close($link);
}

// Funkcja do edytowania kategorii
function EdytujKategorie($id, $nazwa, $matka_id) {
    $link = dbConnect();
    $query = "UPDATE kategorie SET nazwa='$nazwa', matka_id='$matka_id' WHERE id='$id'";
    mysqli_query($link, $query);
    mysqli_close($link);
}

// Funkcja do wyświetlania kategorii w formie drzewa
function PokazKategorie($matka_id = 0) {
    $link = dbConnect();
    $query = "SELECT * FROM kategorie WHERE matka_id='$matka_id'";
    $result = mysqli_query($link, $query);
    
    if (mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<li>' . $row['nazwa'];
            // Rekursywne wywołanie dla podkategorii
            PokazKategorie($row['id']);
            echo '</li>';
        }
        echo '</ul>';
    }
    
    mysqli_close($link);
}

// Przykładowe wywołania funkcji
DodajKategorie('Nowa Kategoria', 0); // Dodanie nowej kategorii
EdytujKategorie(1, 'Zmieniona Kategoria', 0); // Edytowanie kategorii
UsunKategorie(2); // Usunięcie kategorii
PokazKategorie(); // Wyświetlenie kategorii
?>