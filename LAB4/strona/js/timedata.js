function gettheDate() {
    Dziasiaj= new Date();
    Data = "" + (Dziasiaj.getMonth() + 1) + "/" + Dziasiaj.getDate() + "/" + (Dziasiaj.getYear() - 100);
    document.getElementById("data").innerHTML = Data ;
}

var ID = null;
var b = false;

function stopclock() {
    if (b) {
        clearTimeout(ID);
        b = false;
    }
}

function startclock() {
    stopclock();
    gettheDate();
    showtime();
}

function showtime() {
    var teraz = new Date();
    var godziny = teraz.getHours();
    var minuty = teraz.getMinutes();
    var sekundy = teraz.getSeconds();
    var czas = "" + ((godziny > 12) ? godziny - 12 : godziny);
    czas += ((minuty < 10) ? ":0" : ":") + minuty;
    czas += ((sekundy < 10) ? ":0" : ":") + sekundy;
    czas += (godziny >= 12) ? " P.M." : " A.M.";
    document.getElementById("zegarek").innerHTML = czas;
    timerID = setTimeout("showtime()", 1000);
    timerRunning = true;
}