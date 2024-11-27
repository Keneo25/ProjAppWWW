<?php
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontakt</title>
    <link rel="stylesheet" href="../css/style.css"> 
</head>
<body>

<?php

class Contact {
    
    public function PokazKontakt() {
        return '
        <h2>Kontakt</h2>
        <form method="POST" action="" class="contact-form">
            <label for="temat">Temat:</label>
            <input type="text" id="temat" name="temat" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="tresc">Wiadomość:</label><br>
            <textarea id="tresc" name="tresc" rows="4" required></textarea><br><br>
            
            <input type="submit" name="submit" value="Wyślij">
        </form>
        ';
    }

    public function WyslijMailKontakt($odbiorca) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) {
                echo "[nie_wypelniles_pola]";
                echo $this->PokazKontakt(); 
            } else {
                $mail['subject'] = $_POST['temat'];
                $mail['body'] = $_POST['tresc'];
                $mail['sender'] = $_POST['email'];
                $mail['recipient'] = $odbiorca; 
                
                $header = "From: Formularz kontaktowy <" . $mail['sender'] . ">\n";
                $header .= "MIME-Version: 1.0\nContent-Type: text/plain; charset=utf-8\nContent-Transfer-Encoding: quoted-printable\n";
                $header .= "X-Sender: <" . $mail['sender'] . ">\n";
                $header .= "X-Mailer: Ręczny mail 1.2\n";
                $header .= "X-Priority: 3\n";
                $header .= "Return-Path: <" . $mail['sender'] . ">\n";

                if (mail($mail['recipient'], $mail['subject'], $mail['body'], $header)) {
                    echo "[wiadomosc_wyslana]";
                } else {
                    echo "[problem_z_wyslaniem_wiadomosci]";
                }
            }
        } else {
            echo $this->PokazKontakt(); 
        }
    }

    public function PrzypomnijHaslo($email) {
        if ($this->sprawdzEmail($email)) {
            $haslo = "TwojeHaslo123"; 
            $to = $email;
            $subject = 'Przypomnienie hasła do panelu admina';
            $body = "Twoje hasło do panelu admina to: $haslo";

            return $this->WyslijMailKontakt($to, $subject, $body, 'admin@example.com');
        } else {
            return "Podany adres e-mail nie istnieje w naszej bazie.";
        }
    }


    private function sprawdzEmail($email) {
        $istniejące_emails = ['user1@example.com', 'user2@example.com'];
        return in_array($email, $istniejące_emails);
    }
}


$contact = new Contact();
echo $contact->PokazKontakt();


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $contact->WyslijMailKontakt('kontakt@example.com'); 
}

?>

</body>
</html>
