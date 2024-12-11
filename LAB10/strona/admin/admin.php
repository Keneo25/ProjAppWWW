<?php
// Inicjalizacja sesji PHP do zarządzania logowaniem użytkownika
session_start();
include('../cfg.php'); // Włączenie pliku konfiguracyjnego, który zawiera dane logowania do bazy danych

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Łączenie arkusza CSS -->
</head>
<body>
<?php

// Funkcja do połączenia z bazą danych
function dbConnect() {
    global $dbhost, $dbuser, $dbpass, $baza;
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza); // Nawiązanie połączenia z bazą danych
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error()); // Obsługa błędów połączenia
    }
    return $link;
}

// Funkcja generująca formularz logowania
function FormularzLogowania() {
    $wynik = '
    <div class="admin-form">
        <h1>Panel administracyjny</h1>
        <form method="post" action="' . $_SERVER['REQUEST_URI'] . '">
            <div class="form-group">
                <label for="login">Login:</label>
                <input type="text" name="login" id="login" required>
            </div>
            <div class="form-group">
                <label for="pass">Hasło:</label>
                <input type="password" name="pass" id="pass" required>
            </div>
            <input type="submit" name="zaloguj" value="Zaloguj">
        </form>
    </div>';
    return $wynik;
}

// Funkcja wyświetlająca listę podstron w panelu administracyjnym
function ListaPodstron() {
    $link = dbConnect(); // Nawiązanie połączenia z bazą danych
    $query = "SELECT * FROM page_list"; // Pobranie danych o podstronach z bazy danych
    $result = mysqli_query($link, $query);
    
    echo '<div class="admin-form">';
    echo '<h2>Lista podstron</h2>';
    echo '<table class="admin-table">'; // Tabela z listą podstron
    echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
    
    while($row = mysqli_fetch_array($result)) {
        // Generowanie wiersza dla każdej podstrony
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        echo '<td>'.$row['page_title'].'</td>';
        echo '<td class="action-buttons">
                <a href="admin.php?action=edit&id='.$row['id'].'" class="edit-btn">Edytuj</a>
                <a href="admin.php?action=delete&id='.$row['id'].'" class="delete-btn" onclick="return confirm(\'Czy na pewno chcesz usunąć?\')">Usuń</a>
              </td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '<div class="action-buttons">';
    echo '<a href="admin.php?action=add" class="edit-btn">Dodaj nową podstronę</a>';
    echo '</div>';
    echo '</div>';
    
    mysqli_close($link); // Zamknięcie połączenia z bazą danych
}

// Funkcja do edycji podstrony
function EdytujPodstrone() {
    $link = dbConnect(); // Nawiązanie połączenia z bazą danych
    $id = $_GET['id']; // Pobranie ID podstrony z parametru URL
    
    if(isset($_POST['update'])) {
        // Aktualizacja danych podstrony w bazie
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;
        
        $query = "UPDATE page_list SET page_title='$title', page_content='$content', status=$status WHERE id=$id LIMIT 1";
        mysqli_query($link, $query); // Wykonanie zapytania aktualizującego
        header("Location: admin.php"); // Przekierowanie po zapisaniu zmian
        exit();
    }
    
    $query = "SELECT * FROM page_list WHERE id=$id LIMIT 1"; // Pobranie danych edytowanej podstrony
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    
    // Formularz do edycji podstrony
    echo '<div class="edit-form">';
    echo '<h2>Edytuj podstronę</h2>';
    echo '<form method="post" action="">';
    echo 'Tytuł: <input type="text" name="title" value="'.$row['page_title'].'"><br><br>';
    echo 'Treść: <textarea name="content" rows="10" cols="50">'.$row['page_content'].'</textarea><br><br>';
    echo 'Aktywna: <input type="checkbox" name="status" '.($row['status'] ? 'checked' : '').'><br><br>';
    echo '<input type="submit" name="update" value="Zapisz zmiany">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link); // Zamknięcie połączenia z bazą danych
}

// Funkcja do dodawania nowej podstrony
function DodajNowaPodstrone() {
    $link = dbConnect(); // Nawiązanie połączenia z bazą danych
    
    if(isset($_POST['add'])) {
        // Wstawianie nowej podstrony do bazy danych
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;
        
        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        mysqli_query($link, $query); // Wykonanie zapytania wstawiającego
        header("Location: admin.php"); // Przekierowanie po dodaniu podstrony
        exit();
    }
    
    // Formularz do dodawania podstrony
    echo '<div class="add-form">';
    echo '<h2>Dodaj nową podstronę</h2>';
    echo '<form method="post" action="">';
    echo 'Tytuł: <input type="text" name="title" required><br><br>';
    echo 'Treść: <textarea name="content" rows="10" cols="50" required></textarea><br><br>';
    echo 'Aktywna: <input type="checkbox" name="status" checked><br><br>';
    echo '<input type="submit" name="add" value="Dodaj podstronę">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link); // Zamknięcie połączenia z bazą danych
}

// Funkcja do usuwania podstrony
function UsunPodstrone() {
    $link = dbConnect(); // Nawiązanie połączenia z bazą danych
    $id = $_GET['id']; // Pobranie ID podstrony z parametru URL
    
    $query = "DELETE FROM page_list WHERE id=$id LIMIT 1"; // Usuwanie podstrony z bazy danych
    mysqli_query($link, $query); // Wykonanie zapytania usuwającego
    header("Location: admin.php"); // Przekierowanie po usunięciu podstrony
    
    mysqli_close($link); // Zamknięcie połączenia z bazą danych
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
        echo '<ul style="list-style-type: none; padding: 0;">';
        while ($row = mysqli_fetch_assoc($result)) {
            // Sprawdzenie, czy kategoria jest główną (matka_id = 0)
            $isMainCategory = $row['matka_id'] == 0;

            // Dodanie stylu do głównych kategorii
            $fontWeight = $isMainCategory ? 'bold' : 'normal'; // Wytłuszczenie głównych kategorii

            echo '<li style="text-align: center; background-color: #f0f0f0; padding: 10px; border-radius: 5px; max-width: 400px; margin: 10px auto; font-weight: ' . $fontWeight . ';">' . $row['nazwa'] . ' (ID: ' . $row['id'] . ')'; // Dodano ID obok nazwy
            // Rekursywne wywołanie dla podkategorii
            PokazKategorie($row['id']);
            echo '</li>';
        }
        echo '</ul>';
    }
    
    mysqli_close($link);
}

// Logowanie użytkownika
if(isset($_POST['zaloguj'])) {
    if($_POST['login'] == $login && $_POST['pass'] == $pass) {
        $_SESSION['zalogowany'] = true; // Ustawienie sesji po poprawnym logowaniu
        header("Location: admin.php"); // Przekierowanie do panelu administracyjnego
        exit();
    } else {
        echo '<div class="error">Błędny login lub hasło!</div>'; // Komunikat o błędzie logowania
        echo FormularzLogowania(); // Wyświetlenie formularza logowania
        exit();
    }
}

// Obsługa panelu administracyjnego po zalogowaniu
if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
    if(isset($_GET['action'])) {
        // Obsługa akcji w panelu administracyjnym (edycja, dodawanie, usuwanie)
        switch($_GET['action']) {
            case 'edit':
                EdytujPodstrone(); // Wywołanie funkcji edytującej podstronę
                break;
            case 'add':
                DodajNowaPodstrone(); // Wywołanie funkcji dodającej nową podstronę
                break;
            case 'delete':
                UsunPodstrone(); // Wywołanie funkcji usuwającej podstronę
                break;
            default:
                ListaPodstron(); // Domyślnie wyświetlenie listy podstron
        }
    } else {
        ListaPodstron(); // Domyślnie wyświetlenie listy podstron
    }
} else {
    echo FormularzLogowania(); // Wyświetlenie formularza logowania
}

// Wylogowanie użytkownika
if(isset($_POST['wyloguj'])) {
    session_destroy(); // Zakończenie sesji
    header("Location: admin.php"); // Przekierowanie do strony logowania
}

// Formularz do dodawania kategorii
echo '<h2 style="text-align: center;">Dodaj kategorię</h2>';
echo '<form method="POST" action="">
        <input type="text" name="nazwa" placeholder="Nazwa kategorii" required>
        <input type="number" name="matka_id" placeholder="ID kategorii matki (0 dla głównej)">
        <input type="submit" name="dodaj" value="Dodaj kategorię">
      </form>';

// Obsługa dodawania kategorii
if (isset($_POST['dodaj'])) {
    DodajKategorie($_POST['nazwa'], $_POST['matka_id']);
}

// Formularz do edytowania kategorii
echo '<h2 style="text-align: center;">Edytuj kategorię</h2>';
echo '<form method="POST" action="">
        <input type="number" name="id" placeholder="ID kategorii do edytowania" required>
        <input type="text" name="nazwa" placeholder="Nowa nazwa kategorii" required>
        <input type="number" name="matka_id" placeholder="ID kategorii matki (0 dla głównej)">
        <input type="submit" name="edytuj" value="Edytuj kategorię">
      </form>';

// Obsługa edytowania kategorii
if (isset($_POST['edytuj'])) {
    EdytujKategorie($_POST['id'], $_POST['nazwa'], $_POST['matka_id']);
}

// Formularz do usuwania kategorii
echo '<h2 style="text-align: center;">Usuń kategorię</h2>';
echo '<form method="POST" action="">
        <input type="number" name="id" placeholder="ID kategorii do usunięcia" required>
        <input type="submit" name="usun" value="Usuń kategorię">
      </form>';

// Obsługa usuwania kategorii
if (isset($_POST['usun'])) {
    UsunKategorie($_POST['id']);
}

// Wyświetlenie kategorii
echo '<h2 style="text-align: center;">Lista kategorii</h2>';
PokazKategorie(); // Wyświetlenie kategorii

// Przeniesiono formularz do wylogowania na sam dół
echo '<form method="post" class="logout-form" style="margin-top: 20px;">'; // Dodano margines górny
echo '<input type="submit" name="wyloguj" value="Wyloguj">'; // Przycisk do wylogowania
echo '</form>';

?>