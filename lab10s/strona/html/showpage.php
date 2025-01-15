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
    
    // Nawiązanie połączenia z bazą danych
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    
    // Sprawdzenie, czy połączenie się powiodło
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Zapytanie do bazy danych w celu pobrania zawartości strony
    $query = "SELECT * FROM page_list WHERE id='$id' OR page_title='$id' LIMIT 1";
    $result = mysqli_query($link, $query);
    
    // Sprawdzenie, czy zapytanie zwróciło wyniki
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        // Sprawdzenie statusu strony
        if($row['status'] == 1) { 
            $web_content = $row['page_content'];
            mysqli_close($link);
            return $web_content; // Zwrócenie zawartości strony
        }
    }
    
    // Ustalanie, która strona ma być wyświetlona na podstawie identyfikatora
    $strona = '';
    switch($id) {
        case 'glowna':
            $strona = 'glowna.html';
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
        case 'merdeka':
            $strona = 'merdeka.html';
            break;
        case 'filmy':
            $strona = 'filmy.html';
            break;
        default:
            $strona = '404.html'; // Strona 404, jeśli identyfikator nie pasuje
            break;
    }
    
    // Sprawdzenie, czy plik istnieje i pobranie jego zawartości
    if(file_exists($strona)) {
        $web_content = file_get_contents($strona);
    } else {
        $web_content = '<h2>Error 404 - Page not found</h2>'; // Zwrócenie komunikatu o błędzie
    }
    
    mysqli_close($link); // Zamknięcie połączenia z bazą danych
    return $web_content; // Zwrócenie zawartości strony
}

// Ustalanie identyfikatora na podstawie parametrów GET
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 1; // Domyślny identyfikator
}

// Wywołanie funkcji i wyświetlenie zawartości podstrony
echo PokazPodstrone($id);
?> 