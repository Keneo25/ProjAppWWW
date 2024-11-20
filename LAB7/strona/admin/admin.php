<?php
session_start();
include('../cfg.php');

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administracyjny</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php


function dbConnect() {
    global $dbhost, $dbuser, $dbpass, $baza;
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $baza);
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $link;
}

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

function ListaPodstron() {
    $link = dbConnect();
    $query = "SELECT * FROM page_list";
    $result = mysqli_query($link, $query);
    
    echo '<div class="admin-form">';
    echo '<h2>Lista podstron</h2>';
    echo '<table class="admin-table">';
    echo '<tr><th>ID</th><th>Tytuł</th><th>Akcje</th></tr>';
    
    while($row = mysqli_fetch_array($result)) {
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
    
    mysqli_close($link);
}


function EdytujPodstrone() {
    $link = dbConnect();
    $id = $_GET['id'];
    
    if(isset($_POST['update'])) {
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;
        
        $query = "UPDATE page_list SET page_title='$title', page_content='$content', status=$status WHERE id=$id LIMIT 1";
        mysqli_query($link, $query);
        header("Location: admin.php");
        exit();
    }
    
    $query = "SELECT * FROM page_list WHERE id=$id LIMIT 1";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    
    echo '<div class="edit-form">';
    echo '<h2>Edytuj podstronę</h2>';
    echo '<form method="post" action="">';
    echo 'Tytuł: <input type="text" name="title" value="'.$row['page_title'].'"><br><br>';
    echo 'Treść: <textarea name="content" rows="10" cols="50">'.$row['page_content'].'</textarea><br><br>';
    echo 'Aktywna: <input type="checkbox" name="status" '.($row['status'] ? 'checked' : '').'><br><br>';
    echo '<input type="submit" name="update" value="Zapisz zmiany">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link);
}


function DodajNowaPodstrone() {
    $link = dbConnect();
    
    if(isset($_POST['add'])) {
        $title = mysqli_real_escape_string($link, $_POST['title']);
        $content = mysqli_real_escape_string($link, $_POST['content']);
        $status = isset($_POST['status']) ? 1 : 0;
        
        $query = "INSERT INTO page_list (page_title, page_content, status) VALUES ('$title', '$content', $status)";
        mysqli_query($link, $query);
        header("Location: admin.php");
        exit();
    }
    
    echo '<div class="add-form">';
    echo '<h2>Dodaj nową podstronę</h2>';
    echo '<form method="post" action="">';
    echo 'Tytuł: <input type="text" name="title" required><br><br>';
    echo 'Treść: <textarea name="content" rows="10" cols="50" required></textarea><br><br>';
    echo 'Aktywna: <input type="checkbox" name="status" checked><br><br>';
    echo '<input type="submit" name="add" value="Dodaj podstronę">';
    echo '</form>';
    echo '</div>';
    
    mysqli_close($link);
}


function UsunPodstrone() {
    $link = dbConnect();
    $id = $_GET['id'];
    
    $query = "DELETE FROM page_list WHERE id=$id LIMIT 1";
    mysqli_query($link, $query);
    header("Location: admin.php");
    
    mysqli_close($link);
}


if(isset($_POST['zaloguj'])) {
    if($_POST['login'] == $login && $_POST['pass'] == $pass) {
        $_SESSION['zalogowany'] = true;
        header("Location: admin.php");
        exit();
    } else {
        echo '<div class="error">Błędny login lub hasło!</div>';
        echo FormularzLogowania();
        exit();
    }
}


if(isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true) {
   
    if(isset($_GET['action'])) {
        switch($_GET['action']) {
            case 'edit':
                EdytujPodstrone();
                break;
            case 'add':
                DodajNowaPodstrone();
                break;
            case 'delete':
                UsunPodstrone();
                break;
            default:
                ListaPodstron();
        }
    } else {
        ListaPodstron();
    }
    
   
    echo '<form method="post" class="logout-form">';
    echo '<input type="submit" name="wyloguj" value="Wyloguj">';
    echo '</form>';
} else {
    echo FormularzLogowania();
}


if(isset($_POST['wyloguj'])) {
    session_destroy();
    header("Location: admin.php");
}

?>
</body>
</html>