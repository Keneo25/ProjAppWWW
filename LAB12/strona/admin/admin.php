<?php
// Inicjalizacja sesji PHP do zarządzania logowaniem użytkownika
session_start();
include('../cfg.php'); // Włączenie pliku konfiguracyjnego, który zawiera dane logowania do bazy danych

// Przeniesienie obsługi wylogowania na początek
if(isset($_POST['wyloguj'])) {
    session_destroy(); // Zakończenie sesji
    header("Location: admin.php"); // Przekierowanie do strony logowania
    exit(); // Dodanie exit() aby zatrzymać dalsze wykonywanie skryptu
}

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
    echo '<a href="admin.php?action=products" class="edit-btn">Zarządzaj produktami</a>';
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
                echo '<div class="category-row">';
                echo '<div class="category-info">';
                // Ikona folderu i strzałki dla lepszej wizualizacji hierarchii
                echo '<span class="category-icon">'.($level > 0 ? '└─ ' : '').'📁</span>';
                echo '<span class="category-name">'.$category['name'].'</span>';
                if($level > 0) {
                    echo '<span class="category-type">(podkategoria)</span>';
                }
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
            case 'products':
                ListaProduktow();
                break;
            case 'add_product':
                DodajProdukt();
                break;
            case 'edit_product':
                EdytujProdukt();
                break;
            case 'delete_product':
                UsunProdukt();
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

// Funkcja wyświetlająca listę produktów
function ListaProduktow() {
    $link = dbConnect();
    
    echo '<div class="products-section">';
    echo '<h2>Zarządzanie Produktami</h2>';
    
    // Dodanie przycisku powrotu
    echo '<div class="navigation-buttons">';
    echo '<a href="admin.php" class="back-btn">Powrót do listy podstron</a>';
    echo '</div>';
    
    // Przycisk dodawania nowego produktu
    echo '<div class="action-buttons">';
    echo '<a href="admin.php?action=add_product" class="edit-btn">Dodaj nowy produkt</a>';
    echo '</div>';
    
    $query = "SELECT p.*, c.name as category_name FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              ORDER BY p.creation_date DESC";
    $result = mysqli_query($link, $query);
    
    echo '<table class="products-table">';
    echo '<tr>
            <th>ID</th>
            <th>Zdjęcie</th>
            <th>Tytuł</th>
            <th>Cena</th>
            <th>Stan</th>
            <th>Kategoria</th>
            <th>Status</th>
            <th>Akcje</th>
          </tr>';
    
    while($row = mysqli_fetch_array($result)) {
        $status_class = 'status-' . $row['availability_status'];
        $status_text = ucfirst(str_replace('_', ' ', $row['availability_status']));
        
        echo '<tr>';
        echo '<td>'.$row['id'].'</td>';
        echo '<td><img src="'.($row['image_url'] ? '..'.$row['image_url'] : '../img/no-image.png').'" alt="'.$row['title'].'"></td>';
        echo '<td>'.$row['title'].'</td>';
        echo '<td>'.number_format($row['net_price'] * (1 + $row['vat_rate']/100), 2).' PLN</td>';
        echo '<td>'.$row['stock_quantity'].'</td>';
        echo '<td>'.$row['category_name'].'</td>';
        echo '<td><span class="status-badge '.$status_class.'">'.$status_text.'</span></td>';
        echo '<td>
                <div class="action-buttons">
                    <a href="admin.php?action=edit_product&id='.$row['id'].'" class="edit-btn">Edytuj</a>
                    <a href="admin.php?action=delete_product&id='.$row['id'].'" class="delete-btn" 
                       onclick="return confirm(\'Czy na pewno chcesz usunąć ten produkt?\')">Usuń</a>
                </div>
              </td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja dodająca nowy produkt
function DodajProdukt() {
    $link = dbConnect();
    
    if(isset($_POST['add_product'])) {
        try {
            // Obsługa przesłanego zdjęcia
            $image_url = '';
            if(isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
                $image_url = ObslugaZdjecia($_FILES["product_image"]);
            }
            
            $title = mysqli_real_escape_string($link, $_POST['title']);
            $description = mysqli_real_escape_string($link, $_POST['description']);
            $net_price = (float)$_POST['net_price'];
            $vat_rate = (float)$_POST['vat_rate'];
            $stock_quantity = (int)$_POST['stock_quantity'];
            $category_id = (int)$_POST['category_id'];
            $dimensions = mysqli_real_escape_string($link, $_POST['dimensions']);
            $expiration_date = $_POST['expiration_date'];
            $availability_status = mysqli_real_escape_string($link, $_POST['availability_status']);
            
            $query = "INSERT INTO products (title, description, net_price, vat_rate, stock_quantity, 
                      category_id, dimensions, image_url, expiration_date, availability_status) 
                      VALUES ('$title', '$description', $net_price, $vat_rate, $stock_quantity, 
                      $category_id, '$dimensions', '$image_url', '$expiration_date', '$availability_status')";
            
            mysqli_query($link, $query);
            header("Location: admin.php?action=products");
            exit();
        } catch (Exception $e) {
            echo '<div class="error">'.$e->getMessage().'</div>';
        }
    }
    
    echo '<div class="product-form">';
    echo '<h2>Dodaj nowy produkt</h2>';
    
    // Dodanie przycisku powrotu
    echo '<div class="navigation-buttons">';
    echo '<a href="admin.php?action=products" class="back-btn">Powrót do listy produktów</a>';
    echo '</div>';
    
    WyswietlFormularzProduktu();
    // Dodaj przycisk submit
    echo '<div class="form-group">';
    echo '<input type="submit" name="add_product" value="Dodaj produkt" class="button-primary">';
    echo '</div>';
    echo '</form>'; // Zamknij formularz
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja edytująca produkt
function EdytujProdukt() {
    $link = dbConnect();
    $id = (int)$_GET['id'];
    
    if(isset($_POST['update_product'])) {
        try {
            // Obsługa przesłanego zdjęcia
            $image_url = $_POST['current_image'] ?? ''; // Zachowaj obecne zdjęcie
            if(isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
                $image_url = ObslugaZdjecia($_FILES["product_image"]);
                
                // Usuń stare zdjęcie jeśli istnieje
                if(!empty($_POST['current_image'])) {
                    $old_file = $_SERVER['DOCUMENT_ROOT'] . $_POST['current_image'];
                    if(file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
            }
            
            $title = mysqli_real_escape_string($link, $_POST['title']);
            $description = mysqli_real_escape_string($link, $_POST['description']);
            $net_price = (float)$_POST['net_price'];
            $vat_rate = (float)$_POST['vat_rate'];
            $stock_quantity = (int)$_POST['stock_quantity'];
            $category_id = (int)$_POST['category_id'];
            $dimensions = mysqli_real_escape_string($link, $_POST['dimensions']);
            $expiration_date = $_POST['expiration_date'];
            $availability_status = mysqli_real_escape_string($link, $_POST['availability_status']);
            
            // Dodaj image_url do zapytania UPDATE tylko jeśli się zmieniło
            $query = "UPDATE products SET 
                      title='$title', 
                      description='$description', 
                      net_price=$net_price, 
                      vat_rate=$vat_rate, 
                      stock_quantity=$stock_quantity, 
                      category_id=$category_id, 
                      dimensions='$dimensions', 
                      image_url='$image_url', 
                      expiration_date='$expiration_date', 
                      availability_status='$availability_status'
                      WHERE id=$id";
            
            mysqli_query($link, $query);
            header("Location: admin.php?action=products");
            exit();
        } catch (Exception $e) {
            echo '<div class="error">'.$e->getMessage().'</div>';
        }
    }
    
    $query = "SELECT * FROM products WHERE id=$id";
    $result = mysqli_query($link, $query);
    $product = mysqli_fetch_array($result);
    
    echo '<div class="product-form">';
    echo '<h2>Edytuj produkt</h2>';
    
    // Dodanie przycisku powrotu
    echo '<div class="navigation-buttons">';
    echo '<a href="admin.php?action=products" class="back-btn">Powrót do listy produktów</a>';
    echo '</div>';
    
    echo '<form method="post" action="" enctype="multipart/form-data">';
    
    // Dodaj ukryte pole z obecną ścieżką do obrazu
    if($product['image_url']) {
        echo '<input type="hidden" name="current_image" value="'.$product['image_url'].'">';
    }
    
    // Formularz edycji produktu
    WyswietlFormularzProduktu($product);
    
    echo '<input type="submit" name="update_product" value="Zapisz zmiany" class="button-primary">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja pomocnicza do wyświetlania formularza produktu
function WyswietlFormularzProduktu($product = null) {
    $link = dbConnect();
    
    // Otwórz formularz, ale nie zamykaj go
    echo '<form method="post" action="" enctype="multipart/form-data">';
    
    echo '<div class="form-group">';
    echo '<label for="title">Tytuł:</label>';
    echo '<input type="text" name="title" value="'.($product ? $product['title'] : '').'" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="description">Opis:</label>';
    echo '<textarea name="description" required>'.($product ? $product['description'] : '').'</textarea>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="net_price">Cena netto:</label>';
    echo '<input type="number" step="0.01" name="net_price" value="'.($product ? $product['net_price'] : '').'" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="vat_rate">Stawka VAT (%):</label>';
    echo '<input type="number" step="0.01" name="vat_rate" value="'.($product ? $product['vat_rate'] : '23').'" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="stock_quantity">Stan magazynowy:</label>';
    echo '<input type="number" name="stock_quantity" value="'.($product ? $product['stock_quantity'] : '0').'" required>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="category_id">Kategoria:</label>';
    echo '<select name="category_id" required>';
    
    $query = "SELECT * FROM categories ORDER BY name";
    $result = mysqli_query($link, $query);
    
    while($category = mysqli_fetch_array($result)) {
        $selected = ($product && $product['category_id'] == $category['id']) ? 'selected' : '';
        echo '<option value="'.$category['id'].'" '.$selected.'>'.$category['name'].'</option>';
    }
    
    echo '</select>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="dimensions">Wymiary:</label>';
    echo '<input type="text" name="dimensions" value="'.($product ? $product['dimensions'] : '').'" placeholder="np. 100x50x25 cm">';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="product_image">Zdjęcie produktu:</label>';
    echo '<div class="image-upload-container">';
    echo '<label class="image-upload-label">';
    echo '<i class="fas fa-cloud-upload-alt"></i> Wybierz zdjęcie lub upuść je tutaj';
    echo '<input type="file" name="product_image" id="product_image" accept="image/*" '.(!$product ? 'required' : '').'>';
    echo '</label>';
    echo '<div class="upload-progress"><div class="upload-progress-bar"></div></div>';
    if($product && $product['image_url']) {
        echo '<div class="image-upload-preview">';
        echo '<img src="'.$product['image_url'].'" alt="Podgląd produktu">';
        echo '<div class="remove-image" title="Usuń zdjęcie">×</div>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="expiration_date">Data wygaśnięcia:</label>';
    echo '<input type="date" name="expiration_date" value="'.($product ? $product['expiration_date'] : '').'">';
    echo '</div>';
    
    echo '<div class="form-group">';
    echo '<label for="availability_status">Status dostępności:</label>';
    echo '<select name="availability_status" required>';
    $statuses = ['available' => 'Dostępny', 'unavailable' => 'Niedostępny', 'coming_soon' => 'Wkrótce dostępny'];
    foreach($statuses as $value => $label) {
        $selected = ($product && $product['availability_status'] == $value) ? 'selected' : '';
        echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
    }
    echo '</select>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja usuwająca produkt
function UsunProdukt() {
    $link = dbConnect();
    $id = (int)$_GET['id'];
    
    $query = "DELETE FROM products WHERE id = $id LIMIT 1";
    mysqli_query($link, $query);
    
    header("Location: admin.php?action=products");
    exit();
}

// Dodaj nową funkcję do obsługi uploadu zdjęć
function ObslugaZdjecia($file) {
    $target_dir = "../uploads/products/";
    
    // Sprawdź czy katalog istnieje, jeśli nie - utwórz go
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Sprawdź czy plik jest rzeczywistym obrazem
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        throw new Exception("Plik nie jest obrazem.");
    }
    
    // Sprawdź rozmiar pliku (max 5MB)
    if ($file["size"] > 5000000) {
        throw new Exception("Plik jest zbyt duży (max 5MB).");
    }
    
    // Zezwól tylko na określone formaty plików
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($file_extension, $allowed_types)) {
        throw new Exception("Dozwolone są tylko pliki JPG, JPEG, PNG i GIF.");
    }
    
    // Przenieś plik do docelowego katalogu
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return "/uploads/products/" . $new_filename; // Zwróć ścieżkę względną
    } else {
        throw new Exception("Wystąpił błąd podczas przesyłania pliku.");
    }
}

echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    const fileInput = document.getElementById("product_image");
    const uploadContainer = document.querySelector(".image-upload-container");
    const progressBar = document.querySelector(".upload-progress-bar");
    const progress = document.querySelector(".upload-progress");
    
    if(fileInput) {
        fileInput.addEventListener("change", function(e) {
            const file = e.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let preview = uploadContainer.querySelector(".image-upload-preview");
                    if(!preview) {
                        preview = document.createElement("div");
                        preview.className = "image-upload-preview";
                        uploadContainer.appendChild(preview);
                    }
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Podgląd">
                        <div class="remove-image" title="Usuń zdjęcie">×</div>
                    `;
                    
                    // Obsługa usuwania zdjęcia
                    preview.querySelector(".remove-image").addEventListener("click", function() {
                        preview.remove();
                        fileInput.value = "";
                    });
                };
                reader.readAsDataURL(file);
                
                // Symulacja postępu uploadu
                progress.style.display = "block";
                let width = 0;
                const interval = setInterval(() => {
                    if(width >= 100) {
                        clearInterval(interval);
                        setTimeout(() => {
                            progress.style.display = "none";
                            progressBar.style.width = "0%";
                        }, 500);
                    } else {
                        width += 5;
                        progressBar.style.width = width + "%";
                    }
                }, 50);
            }
        });
        
        // Obsługa przeciągania i upuszczania
        uploadContainer.addEventListener("dragover", function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = "#3498db";
            uploadContainer.style.backgroundColor = "#f0f4f7";
        });
        
        uploadContainer.addEventListener("dragleave", function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = "#ddd";
            uploadContainer.style.backgroundColor = "#f8f9fa";
        });
        
        uploadContainer.addEventListener("drop", function(e) {
            e.preventDefault();
            uploadContainer.style.borderColor = "#ddd";
            uploadContainer.style.backgroundColor = "#f8f9fa";
            
            const file = e.dataTransfer.files[0];
            if(file && file.type.startsWith("image/")) {
                fileInput.files = e.dataTransfer.files;
                const event = new Event("change");
                fileInput.dispatchEvent(event);
            }
        });
    }
});
</script>';

?>