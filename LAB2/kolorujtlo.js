var a = false;
var b = 0;

function convertFrom (entry, from, to) {
    convertfrom = from.selectedIndex;
    convertto = to.selectedIndex;
    entry.display.value = (entry.input.value * from[convertfrom].value / to[convertto].value);
}

function add (wejscie, k) {
    if((k == "." && b == 0) || k != ".") {
        (wejscie.value == "" || wejscie.value == "0") ? wejscie.value = k : wejscie.value += k;
        convertFrom(wejscie.form, wejscie.form.measure1, wejscie.form.measure2);
        a = true;
        if (k == ".") {
            b = 1;
        }
    }
}

function openVothcom () {
    window.open("", "Display window", "toolbar=no,directories=no,menubar=no");
}

function clear (form) {
    form.input.value = 0;
    form.display.value = 0;
    decimal = 0;
}

function changeBackground(hexNumber) {
    document.bgColor = hexNumber;
}