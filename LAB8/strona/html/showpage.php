<?php
require_once('../cfg.php');

function PokazPodstrone($id) {
    global $dbhost, $dbuser, $dbpass, $baza;
    
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }

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
            $strona = '404.html';
            break;
    }
    
    if(file_exists($strona)) {
        $web_content = file_get_contents($strona);
    } else {
        $web_content = '<h2>Error 404 - Page not found</h2>';
    }
    
    mysqli_close($link);
    return $web_content;
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 1; 
}

echo PokazPodstrone($id);
?> 