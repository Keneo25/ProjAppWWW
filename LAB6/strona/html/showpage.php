<?php
// Database connection parameters
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$baza = 'moja_strona';

// Create connection
$link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to get page content
function PokazPodstrone($id) {
    global $link;
    
    if (empty($id)) {
        $id = 'glowna';  // Default to home page
    }
    
    // Prepare SQL query with protection against SQL injection
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($link, $query);
    
    $row = mysqli_fetch_array($result);
    
    // Check if page exists in database
    if(empty($row['id'])) {
        // Handle HTML files if not found in database
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
        
        // Check if file exists in current directory
        if(file_exists($strona)) {
            return file_get_contents($strona);
        } else {
            return '<h2>Error 404 - Page not found</h2>';
        }
    }
    
    // Return page content from database
    return $row['page_content'];
}

// Get the page id from URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = '';
}

// Display the page content
echo PokazPodstrone($id);

// Close database connection
mysqli_close($link);
?> 