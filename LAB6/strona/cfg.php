<?php
$host = 'localhost';     // Adres serwera bazy danych
$user = 'root';          // Nazwa użytkownika bazy danych (domyślnie 'root' w XAMPP)
$password = '';          // Hasło do bazy danych (domyślnie puste w XAMPP)
$database = 'moja_strona';  // Nazwa bazy danych

// Tworzenie połączenia
$conn = new mysqli($host, $user, $password, $database);

// Sprawdzanie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Ustawienie kodowania na UTF-8 (zalecane dla polskich znaków)
$conn->set_charset("utf8");

?>