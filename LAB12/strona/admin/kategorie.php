<?php
// Włączenie pliku konfiguracyjnego zawierającego dane dostępowe do bazy danych
require_once('../cfg.php');

// Funkcja nawiązująca połączenie z bazą danych
// Zwraca obiekt połączenia lub kończy skrypt w przypadku błędu
function dbConnect() {
    // Używamy zmiennych globalnych z pliku konfiguracyjnego
    global $dbhost, $dbuser, $dbpass, $baza;
    // Próba nawiązania połączenia z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    // Sprawdzenie czy połączenie się powiodło
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $link;
}

// Funkcja dodająca nową kategorię do bazy danych
// Parametry: nazwa kategorii i opcjonalne ID kategorii nadrzędnej
function DodajKategorie($nazwa, $matka_id = 0) {
    // Nawiązanie połączenia z bazą
    $link = dbConnect();
    // Zapytanie SQL wstawiające nową kategorię
    $query = "INSERT INTO kategorie (matka_id, nazwa) VALUES ('$matka_id', '$nazwa')";
    // Wykonanie zapytania
    mysqli_query($link, $query);
    // Zamknięcie połączenia
    mysqli_close($link);
}

// Funkcja usuwająca kategorię o podanym ID z bazy danych
function UsunKategorie($id) {
    // Nawiązanie połączenia z bazą
    $link = dbConnect();
    // Zapytanie SQL usuwające kategorię
    $query = "DELETE FROM kategorie WHERE id='$id'";
    // Wykonanie zapytania
    mysqli_query($link, $query);
    // Zamknięcie połączenia
    mysqli_close($link);
}

// Funkcja edytująca istniejącą kategorię
// Parametry: ID kategorii, nowa nazwa i nowe ID kategorii nadrzędnej
function EdytujKategorie($id, $nazwa, $matka_id) {
    // Nawiązanie połączenia z bazą
    $link = dbConnect();
    // Zapytanie SQL aktualizujące dane kategorii
    $query = "UPDATE kategorie SET nazwa='$nazwa', matka_id='$matka_id' WHERE id='$id'";
    // Wykonanie zapytania
    mysqli_query($link, $query);
    // Zamknięcie połączenia
    mysqli_close($link);
}

// Funkcja wyświetlająca drzewo kategorii
// Parametr: ID kategorii nadrzędnej (domyślnie 0 dla kategorii głównych)
function PokazKategorie($matka_id = 0) {
    // Nawiązanie połączenia z bazą
    $link = dbConnect();
    // Zapytanie SQL pobierające kategorie podrzędne dla danej kategorii nadrzędnej
    $query = "SELECT * FROM kategorie WHERE matka_id='$matka_id'";
    // Wykonanie zapytania
    $result = mysqli_query($link, $query);
    
    // Jeśli znaleziono kategorie podrzędne
    if (mysqli_num_rows($result) > 0) {
        // Rozpoczęcie listy HTML
        echo '<ul>';
        // Iteracja przez wszystkie znalezione kategorie
        while ($row = mysqli_fetch_assoc($result)) {
            // Wyświetlenie nazwy kategorii
            echo '<li>' . $row['nazwa'];
            // Rekurencyjne wywołanie funkcji dla podkategorii
            PokazKategorie($row['id']);
            echo '</li>';
        }
        // Zakończenie listy HTML
        echo '</ul>';
    }
    
    // Zamknięcie połączenia
    mysqli_close($link);
}

// Przykładowe wywołania funkcji demonstrujące ich działanie
DodajKategorie('Nowa Kategoria', 0); // Dodanie nowej kategorii głównej
EdytujKategorie(1, 'Zmieniona Kategoria', 0); // Edycja kategorii o ID=1
UsunKategorie(2); // Usunięcie kategorii o ID=2
PokazKategorie(); // Wyświetlenie całego drzewa kategorii
?>