<?php
// Włączenie pliku konfiguracyjnego
require_once('../cfg.php');

/**
 * Funkcja do wyświetlania podstrony na podstawie identyfikatora
 *
 * @param string $id - identyfikator podstrony
 * @return string - zawartość podstrony
 */
function PokazPodstrone($id) {
    global $dbhost, $dbuser, $dbpass, $baza;
    
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Specjalna obsługa dla strony sklepu
    if($id == 'sklep') {
        include('sklep.php');
        mysqli_close($link);
        return;
    }

    // Najpierw sprawdzamy czy istnieje plik HTML
    $strona = '';
    switch($id) {
        case 'merdeka':
            $strona = 'merdeka.html';
            break;
        case 'bur':
            $strona = 'bur.html';
            break;
        case 'future':
            $strona = 'future.html';
            break;
        case 'history':
            $strona = 'history.html';
            break;
        case 'innowacje':
            $strona = 'innowacje.html';
            break;
        case 'logowanie':
            $strona = 'logowanie.html';
            break;
        case 'filmy':
            $strona = 'filmy.html';
            break;
        case 'glowna':
            $strona = 'glowna.html';
            break;
    }

    // Jeśli znaleziono plik HTML, użyj go
    if($strona && file_exists($strona)) {
        $web_content = file_get_contents($strona);
        mysqli_close($link);
        return $web_content;
    }

    // Jeśli nie znaleziono pliku HTML, sprawdź w bazie danych
    $query = "SELECT * FROM page_list WHERE id='$id' OR page_title='$id' LIMIT 1";
    $result = mysqli_query($link, $query);
    
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row['status'] == 1) { 
            $web_content = $row['page_content'];
            mysqli_close($link);
            return $web_content;
        }
    }
    
    // Jeśli nie znaleziono ani pliku ani strony w bazie, zwróć 404
    $web_content = '<h2>Error 404 - Page not found</h2>';
    mysqli_close($link);
    return $web_content;
}

// Ustalanie identyfikatora na podstawie parametrów GET
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 1;
}

// Wywołanie funkcji i wyświetlenie zawartości podstrony
echo PokazPodstrone($id);
?> 