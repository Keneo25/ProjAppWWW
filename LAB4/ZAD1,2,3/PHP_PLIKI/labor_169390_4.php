<?php
 $nr_indeksu = '169390';
 $nrGrupy = '4';
 echo 'Konrad Trzcinski ' . $nr_indeksu . ' grupa: ' . $nrGrupy . ' <br /><br />';
 echo 'Zastosowanie metody include() <br />';


echo 'a) Metoda include() i require_once(): <br />';
echo 'Zastosowanie metody include() w celu wczytania zewnętrznego pliku: <br />';
include 'include.php';
echo '<br />';
echo 'Zastosowanie metody require_once() w celu jednorazowego wczytania pliku: <br />';
require_once 'include.php';
echo '<br /><br />';


echo 'b) Warunki if, else, elseif, switch: <br />';
$liczba = 10;
echo 'Przykład z if, else, elseif: <br />';
if ($liczba > 10) {
    echo 'Liczba jest większa niż 10 <br />';
} elseif ($liczba == 10) {
    echo 'Liczba jest równa 10 <br />';
} else {
    echo 'Liczba jest mniejsza niż 10 <br />';
}

echo 'Przykład ze switch: <br />';
$ocena = 5;
switch ($ocena) {
    case 5:
        echo 'Ocena: bardzo dobra <br />';
        break;
    case 4:
        echo 'Ocena: dobra <br />';
        break;
    case 3:
        echo 'Ocena: dostateczna <br />';
        break;
    default:
        echo 'Inna ocena <br />';
}
echo '<br /><br />';


echo 'c) Pętla while() i for(): <br />';
echo 'Pętla while(): <br />';
$i = 1;
while ($i <= 5) {
    echo 'Liczba: ' . $i . '<br />';
    $i++;
}

echo 'Pętla for(): <br />';
for ($j = 1; $j <= 5; $j++) {
    echo 'Liczba: ' . $j . '<br />';
}
echo '<br /><br />';


echo 'd) Typy zmiennych $_GET, $_POST, $_SESSION: <br />';
echo 'Przykład użycia $_GET: <br />';
if (isset($_GET['imie'])) {
    echo 'Witaj, ' . $_GET['imie'] . '! <br />';
} else {
    echo 'Podaj swoje imię w adresie URL, np. ?imie=Konrad <br />';
}

echo 'Przykład użycia $_POST: <br />';
echo '<form method="POST">
        <input type="text" name="nazwisko" placeholder="Podaj nazwisko">
        <input type="submit" value="Wyślij">
      </form>';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nazwisko'])) {
    echo 'Witaj, ' . $_POST['nazwisko'] . '! <br />';
}

session_start();
$_SESSION['wiek'] = 25; 
echo 'Przykład użycia $_SESSION: Wiek = ' . $_SESSION['wiek'] . '<br />';
?>