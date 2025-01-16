<?php
// Włączenie raportowania błędów, z wyłączeniem powiadomień i ostrzeżeń
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> <!-- Łączenie arkusza stylów -->
    <script src="https://kit.fontawesome.com/2fa7370336.js" crossorigin="anonymous"></script> <!-- Font Awesome -->
    <script src="../js/kolorujtlo.js" type="text/javascript"></script> <!-- Skrypt do kolorowania tła -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- jQuery -->
    <script src="../js/timedata.js" type="text/javascript"></script> <!-- Skrypt do zarządzania danymi czasowymi -->
    <title>Największe budynki świata</title>
</head>
<body>
    <header>
        <h1 id="title">Największe budynki świata</h1>
        
        <!-- Nawigacja -->
        <nav class="nav-right">
            <ul>
                <li><a href="index.php?id=future">Budynki przyszłości</a></li>
                <li><a href="index.php?id=innowacje">Innowacje w budownictwie wieżowców</a></li>
                <li><a href="index.php?id=filmy">Filmy</a></li>
                <li><a href="index.php?id=history"><i class="fa-solid fa-landmark"></i></a></li>
                <li><a href="index.php?id=logowanie"><i class="fa-solid fa-user"></i></a></li>
                <li><a href="index.php?id=glowna"><i class="fa-solid fa-image"></i></a></li>
                <li><a href="index.php?id=sklep"><i class="fa-solid fa-shop"></i> Sklep</a></li>
            </ul>
        </nav>
    </header>

    <div id="content">
        <!-- Włączenie zawartości strony z pliku showpage.php -->
        <?php include('showpage.php'); ?>
    </div>

    <footer>
        <?php
            // Informacje o autorze
            $nr_indeksu = "169390";
            $nrGrupy = "4";
            echo "Autor: Konrad Trzciński ".$nr_indeksu." grupa ".$nrGrupy." <br /><br />";
        ?>
    </footer>
</body>
</html>