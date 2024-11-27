<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

if (!$link) {
    die("Connection failed: " . mysqli_connect_error());

function PokazPodstrone($id) {
    global $link;
    
    if (empty($id)) {
        $id = 'glowna';
    }
    
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    
    $row = mysqli_fetch_array($result);
    
    if(empty($row['id'])) {
        $strona = '';
        
        switch($id_clear) {
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
                $strona = '404.html';
                break;
        }
        
        if(file_exists($strona)) {
            return file_get_contents($strona);
        } else {
            return '<h2>Error 404 - Page not found</h2>';
        }
    }
    
    return $row['page_content'];
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = '';
}

echo PokazPodstrone($id);
mysqli_close($link);
?> 