<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Corrected paths relative to html directory -->
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://kit.fontawesome.com/2fa7370336.js" crossorigin="anonymous"></script>
    <script src="../js/kolorujtlo.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/timedata.js" type="text/javascript"></script>
    <title>Największe budynki świata</title>
</head>
<body>
    <header>
        <h1 id="title">Największe budynki świata</h1>
        
        <nav class="nav-right">
            <ul>
                <!-- Updated links to use index.php in current directory -->
                <li><a href="index.php?id=future">Budynki przyszłości</a></li>
                <li><a href="index.php?id=innowacje">Innowacje w budownictwie wieżowców</a></li>
                <li><a href="index.php?id=filmy">Filmy</a></li>
                <li><a href="index.php?id=history"><i class="fa-solid fa-landmark"></i></a></li>
                <li><a href="index.php?id=logowanie"><i class="fa-solid fa-user"></i></a></li>
                <li><a href="index.php?id=glowna"><i class="fa-solid fa-image"></i></a></li>
            </ul>
        </nav>
    </header>
    <div id="content">
        <?php include('showpage.php'); ?>
    </div>
    <footer>
        <?php
            $nr_indeksu = "169390";
            $nrGrupy = "4";
            echo "Autor: Konrad Trzciński ".$nr_indeksu." grupa ".$nrGrupy." <br /><br />";
        ?>
    </footer>
</body>
</html> 