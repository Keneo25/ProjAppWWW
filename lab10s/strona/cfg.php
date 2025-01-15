<?php
// Ustawienia logowania
$login = "admin"; // Nazwa użytkownika
$pass = "admin123"; // Hasło użytkownika

// Ustawienia bazy danych
$dbhost = 'localhost'; // Host bazy danych
$dbuser = 'root';      // Użytkownik bazy danych
$dbpass = '';          // Hasło użytkownika bazy danych
$baza = 'moja_strona'; // Nazwa bazy danych

// Nawiązanie połączenia z bazą danych
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

// Sprawdzenie, czy połączenie się powiodło
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error()); // Zatrzymanie skryptu w przypadku błędu połączenia
}

// Zamknięcie połączenia z bazą danych
mysqli_close($conn);
?>