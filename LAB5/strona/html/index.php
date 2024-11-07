<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

if ($_GET['idp'] == ''){
    $strona = "glowna.html";
}
else if ($_GET['idp'] == 'glowna') {
    $strona = 'glowna.html';
} elseif ($_GET['idp'] == 'bur') {
    $strona = 'bur.html';
} elseif ($_GET['idp'] == 'future') {
    $strona = 'future.html';
} elseif ($_GET['idp'] == 'history') {
    $strona = 'history.html';
}
elseif ($_GET['idp'] == 'innowacje') {
    $strona = 'innowacje.html';
}
elseif ($_GET['idp'] == 'logowanie') {
    $strona = 'logowanie.html';
}
elseif ($_GET['idp'] == 'merdeka') {
    $strona = 'merdeka.html';
} 
elseif ($_GET['idp'] == 'filmy') {
    $strona = 'filmy.html';
} 
else {
    $strona = 'html/404.html'; 
}


if (!file_exists($strona)) {
    $strona = '/mnt/data/404.html';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <li><a href="index.php?idp=future">Budynki przyszłości</a></li>
            <li><a href="index.php?idp=innowacje">Innowacje w budownictwie wieżowców</a></li>
            <li><a href="index.php?idp=filmy">Filmy</a></li>
            <li><a href="index.php?idp=history"><i class="fa-solid fa-landmark"></i></a></li>
            <li><a href="index.php?idp=logowanie"><i class="fa-solid fa-user"></i></a></li>
            <li><a href="index.php?idp=glowna"><i class="fa-solid fa-image"></i></a></li>
        </ul>
    </nav>
</header>
    <div id="content">
        <?php include($strona); ?>
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

