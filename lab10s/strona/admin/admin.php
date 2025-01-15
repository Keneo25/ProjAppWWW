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
    echo '<a href="admin.php?action=categories" class="edit-btn">Zarządzaj kategoriami</a>';
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

// Funkcja wyświetlająca listę kategorii
function ListaKategorii() {
    $link = dbConnect();
    
    echo '<div class="admin-form">';
    // Dodanie przycisku powrotu
    echo '<div class="navigation-buttons">';
    echo '<a href="admin.php" class="back-btn">Powrót do listy podstron</a>';
    echo '</div>';
    
    echo '<h2>Zarządzanie Kategoriami</h2>';
    
    // Wyświetl formularz dodawania kategorii
    echo '<form method="post" action="admin.php?action=categories" class="category-form">';
    echo '<div class="form-group">';
    echo '<label for="category_name">Nazwa kategorii:</label>';
    echo '<input type="text" name="category_name" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="parent_id">Kategoria nadrzędna:</label>';
    echo '<select name="parent_id">';
    echo '<option value="0">Brak (kategoria główna)</option>';
    
    // Funkcja pomocnicza do rekurencyjnego wyświetlania kategorii
    function displayCategoryOptions($parent_id = 0, $level = 0, $exclude_id = null) {
        $link = dbConnect();
        $indent = str_repeat('--', $level);
        
        $query = "SELECT * FROM categories WHERE parent_id = $parent_id ORDER BY name";
        $result = mysqli_query($link, $query);
        
        while($row = mysqli_fetch_array($result)) {
            if($row['id'] != $exclude_id) {
                echo '<option value="'.$row['id'].'">'.$indent.' '.$row['name'].'</option>';
                // Rekurencyjne wywołanie dla podkategorii
                displayCategoryOptions($row['id'], $level + 1, $exclude_id);
            }
        }
        
        mysqli_close($link);
    }
    
    // Wywołanie funkcji pomocniczej
    displayCategoryOptions();
    
    echo '</select>';
    echo '</div>';
    
    echo '<input type="submit" name="add_category" value="Dodaj kategorię" class="button-primary">';
    echo '</form>';
    
    // Modyfikacja wyświetlania struktury kategorii
    function displayCategoryTree($parent_id = 0, $level = 0) {
        $link = dbConnect();
        $query = "SELECT * FROM categories WHERE parent_id = $parent_id ORDER BY name";
        $result = mysqli_query($link, $query);
        
        if(mysqli_num_rows($result) > 0) {
            while($category = mysqli_fetch_array($result)) {
                echo '<div class="category-main level-'.$level.'">';
                echo '<div class="category-row main-category">';
                echo '<div class="category-info">';
                echo '<span class="category-icon">'.str_repeat('└─', $level).' 📁</span>';
                echo '<span class="category-name">'.$category['name'].'</span>';
                echo '<span class="category-type">(poziom '.$level.')</span>';
                echo '</div>';
                echo '<div class="category-actions">';
                echo '<a href="admin.php?action=edit_category&id='.$category['id'].'" class="edit-btn">Edytuj</a>';
                echo '<a href="admin.php?action=delete_category&id='.$category['id'].'" class="delete-btn" 
                        onclick="return confirm(\'Czy na pewno chcesz usunąć kategorię '.$category['name'].' i przenieść jej podkategorie do kategorii głównych?\')">Usuń</a>';
                echo '</div>';
                echo '</div>';
                
                // Rekurencyjne wywołanie dla podkategorii
                displayCategoryTree($category['id'], $level + 1);
                
                echo '</div>';
            }
        } else if($level > 0) {
            echo '<div class="no-subcategories">Brak podkategorii</div>';
        }
        
        mysqli_close($link);
    }
    
    // Wyświetl drzewo kategorii
    echo '<div class="categories-tree">';
    echo '<h3>Struktura kategorii</h3>';
    
    if(mysqli_num_rows(mysqli_query($link, "SELECT * FROM categories WHERE parent_id = 0")) > 0) {
        echo '<div class="category-structure">';
        displayCategoryTree();
        echo '</div>';
    } else {
        echo '<div class="no-categories">Brak kategorii</div>';
    }
    
    echo '</div>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja dodająca nową kategorię
function DodajKategorie() {
    if(isset($_POST['add_category'])) {
        $link = dbConnect();
        $name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = (int)$_POST['parent_id'];
        
        $query = "INSERT INTO categories (name, parent_id) VALUES ('$name', $parent_id)";
        mysqli_query($link, $query);
        
        mysqli_close($link);
        header("Location: admin.php?action=categories");
        exit();
    }
}

// Funkcja edytująca kategorię
function EdytujKategorie() {
    $link = dbConnect();
    $id = (int)$_GET['id'];
    
    if(isset($_POST['update_category'])) {
        $name = mysqli_real_escape_string($link, $_POST['category_name']);
        $parent_id = (int)$_POST['parent_id'];
        
        $query = "UPDATE categories SET name='$name', parent_id=$parent_id WHERE id=$id";
        mysqli_query($link, $query);
        
        header("Location: admin.php?action=categories");
        exit();
    }
    
    $query = "SELECT * FROM categories WHERE id=$id";
    $result = mysqli_query($link, $query);
    $category = mysqli_fetch_array($result);
    
    echo '<div class="admin-form">';
    // Dodanie przycisku powrotu
    echo '<div class="navigation-buttons">';
    echo '<a href="admin.php?action=categories" class="back-btn">Powrót do kategorii</a>';
    echo '</div>';
    
    echo '<h2>Edytuj kategorię</h2>';
    echo '<form method="post" action="" class="category-form">';
    echo '<div class="form-group">';
    echo '<label for="category_name">Nazwa kategorii:</label>';
    echo '<input type="text" name="category_name" value="'.$category['name'].'" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="parent_id">Kategoria nadrzędna:</label>';
    echo '<select name="parent_id">';
    echo '<option value="0" '.($category['parent_id'] == 0 ? 'selected' : '').'>Brak (kategoria główna)</option>';
    
    // Wykorzystanie tej samej funkcji pomocniczej
    displayCategoryOptions(0, 0, $id);
    
    echo '</select>';
    echo '</div>';
    
    echo '<input type="submit" name="update_category" value="Zapisz zmiany" class="button-primary">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja usuwająca kategorię
function UsunKategorie() {
    $link = dbConnect();
    $id = (int)$_GET['id'];
    
    // Najpierw przenieś wszystkie podkategorie do kategorii głównej
    $query = "UPDATE categories SET parent_id = 0 WHERE parent_id = $id";
    mysqli_query($link, $query);
    
    // Następnie usuń kategorię
    $query = "DELETE FROM categories WHERE id = $id LIMIT 1";
    mysqli_query($link, $query);
    
    mysqli_close($link);
    header("Location: admin.php?action=categories");
    exit();
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
            case 'categories':
                DodajKategorie();
                ListaKategorii();
                break;
            case 'edit_category':
                EdytujKategorie();
                break;
            case 'delete_category':
                UsunKategorie();
                break;
            default:
                ListaPodstron(); // Domyślnie wyświetlenie listy podstron
        }
    } else {
        ListaPodstron(); // Domyślnie wyświetlenie listy podstron
    }
    
    // Formularz do wylogowania
    echo '<form method="post" class="logout-form">';
    echo '<input type="submit" name="wyloguj" value="Wyloguj">'; // Przycisk do wylogowania
    echo '</form>';
} else {
    echo FormularzLogowania(); // Wyświetlenie formularza logowania
}

// Wylogowanie użytkownika
if(isset($_POST['wyloguj'])) {
    session_destroy(); // Zakończenie sesji
    header("Location: admin.php"); // Przekierowanie do strony logowania
}

?>