<?php
// Włączenie raportowania błędów, z wyłączeniem powiadomień i ostrzeżeń
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); // Ustawia poziom raportowania błędów
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Ustawia kodowanie znaków na UTF-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Ustawia widok dla urządzeń mobilnych -->
    <link rel="stylesheet" href="../css/style.css"> <!-- Łączenie arkusza stylów CSS -->
    <script src="https://kit.fontawesome.com/2fa7370336.js" crossorigin="anonymous"></script> <!-- Ładowanie Font Awesome dla ikon -->
    <script src="../js/kolorujtlo.js" type="text/javascript"></script> <!-- Skrypt do kolorowania tła -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!-- Ładowanie jQuery -->
    <script src="../js/timedata.js" type="text/javascript"></script> <!-- Skrypt do zarządzania danymi czasowymi -->
    <title>Największe budynki świata</title> <!-- Tytuł strony -->
</head>
<body>
    <header>
        <h1 id="title">Największe budynki świata</h1> <!-- Nagłówek strony -->
        
        <!-- Nawigacja -->
        <nav class="nav-right">
            <ul>
                <li><a href="index.php?id=future">Budynki przyszłości</a></li> <!-- Link do sekcji o budynkach przyszłości -->
                <li><a href="index.php?id=innowacje">Innowacje w budownictwie wieżowców</a></li> <!-- Link do sekcji o innowacjach -->
                <li><a href="index.php?id=filmy">Filmy</a></li> <!-- Link do sekcji o filmach -->
                <li><a href="index.php?id=history"><i class="fa-solid fa-landmark"></i></a></li> <!-- Link do sekcji o historii -->
                <li><a href="index.php?id=logowanie"><i class="fa-solid fa-user"></i></a></li> <!-- Link do logowania -->
                <li><a href="index.php?id=glowna"><i class="fa-solid fa-image"></i></a></li> <!-- Link do strony głównej -->
            </ul>
        </nav>
    </header>

    <div id="content">
        <!-- Włączenie zawartości strony z pliku showpage.php -->
        <?php include('showpage.php'); ?> <!-- Włącza zawartość z innego pliku PHP -->
    </div>

    <footer>
        <?php
            // Informacje o autorze
            $nr_indeksu = "169390"; // Numer indeksu autora
            $nrGrupy = "4"; // Numer grupy autora
            echo "Autor: Konrad Trzciński ".$nr_indeksu." grupa ".$nrGrupy." <br /><br />"; // Wyświetla informacje o autorze
        ?>
    </footer>
</body>
</html> 