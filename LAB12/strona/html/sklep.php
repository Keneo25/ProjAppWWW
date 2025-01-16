<?php
session_start();
include('../cfg.php');

// Inicjalizacja koszyka
if (!isset($_SESSION['count'])) {
    $_SESSION['count'] = 0;
}

// Obsługa dodawania do koszyka
if (isset($_POST['add_to_cart'])) {
    $id_prod = $_POST['product_id'];
    $ile_sztuk = $_POST['quantity'];
    
    // Sprawdź czy produkt już jest w koszyku
    $found = false;
    for ($i = 1; $i <= $_SESSION['count']; $i++) {
        if (isset($_SESSION[$i . '_1']) && $_SESSION[$i . '_1'] == $id_prod) {
            // Jeśli produkt istnieje, zwiększ jego ilość
            $_SESSION[$i . '_2'] += $ile_sztuk;
            $found = true;
            break;
        }
    }
    
    // Jeśli produkt nie został znaleziony, dodaj nowy
    if (!$found) {
        $_SESSION['count']++;
        $nr = $_SESSION['count'];
        
        // Zapisanie danych produktu w koszyku
        $_SESSION[$nr . '_0'] = $nr;
        $_SESSION[$nr . '_1'] = $id_prod;
        $_SESSION[$nr . '_2'] = $ile_sztuk;
        $_SESSION[$nr . '_3'] = time();
    }
    
    header('Location: index.php?id=sklep');
    exit();
}

// Obsługa usuwania z koszyka
if (isset($_POST['remove_from_cart'])) {
    $nr = $_POST['item_nr'];
    
    // Usuwanie produktu z koszyka
    unset($_SESSION[$nr . '_0']);
    unset($_SESSION[$nr . '_1']);
    unset($_SESSION[$nr . '_2']);
    unset($_SESSION[$nr . '_3']);
    
    header('Location: index.php?id=sklep');
    exit();
}

// Na początku pliku, dodaj obsługę aktualizacji ilości
if (isset($_POST['update_quantity'])) {
    $nr = $_POST['item_nr'];
    $new_quantity = (int)$_POST['new_quantity'];
    
    if ($new_quantity > 0) {
        $_SESSION[$nr . '_2'] = $new_quantity;
    }
    
    header('Location: index.php?id=sklep');
    exit();
}

// Funkcja wyświetlania koszyka
function showCart() {
    if ($_SESSION['count'] == 0) {
        echo '<div class="cart-empty">Koszyk jest pusty</div>';
        return;
    }

    $link = mysqli_connect($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['baza']);
    
    echo '<div class="cart-container">';
    echo '<h2>Twój koszyk</h2>';
    echo '<div class="cart-items">';
    
    $total = 0;
    $unavailable_products = false;
    
    // Wyświetlanie produktów w koszyku
    for ($i = 1; $i <= $_SESSION['count']; $i++) {
        if (isset($_SESSION[$i . '_1'])) {
            $product_id = $_SESSION[$i . '_1'];
            $quantity = $_SESSION[$i . '_2'];
            
            $query = "SELECT * FROM products WHERE id = $product_id";
            $result = mysqli_query($link, $query);
            
            if ($product = mysqli_fetch_array($result)) {
                // Sprawdzenie dostępności produktu
                if ($product['availability_status'] != 'available') {
                    $unavailable_products = true;
                    echo '<div class="cart-item unavailable">';
                    echo '<img src="'.($product['image_url'] ? '..'.$product['image_url'] : '../img/no-image.png').'" alt="'.$product['title'].'">';
                    echo '<div class="cart-item-details">';
                    echo '<h3>'.$product['title'].'</h3>';
                    echo '<p class="status-warning">Produkt niedostępny!</p>';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="item_nr" value="'.$i.'">';
                    echo '<button type="submit" name="remove_from_cart" class="remove-btn">Usuń</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                    continue;
                }
                
                $gross_price = $product['net_price'] * (1 + $product['vat_rate']/100);
                $item_total = $gross_price * $quantity;
                $total += $item_total;
                
                echo '<div class="cart-item">';
                echo '<img src="'.($product['image_url'] ? '..'.$product['image_url'] : '../img/no-image.png').'" alt="'.$product['title'].'">';
                echo '<div class="cart-item-details">';
                echo '<h3>'.$product['title'].'</h3>';
                echo '<p class="price">'.number_format($gross_price, 2).' PLN</p>';
                
                // Nowy kod dla formularza zmiany ilości
                echo '<form method="post" class="quantity-form">';
                echo '<label>Ilość: ';
                echo '<input type="number" name="new_quantity" value="'.$quantity.'" min="1" class="quantity-input">';
                echo '</label>';
                echo '<input type="hidden" name="item_nr" value="'.$i.'">';
                echo '<button type="submit" name="update_quantity" class="update-btn">Aktualizuj</button>';
                echo '</form>';
                
                echo '<p class="subtotal">Suma: '.number_format($item_total, 2).' PLN</p>';
                echo '<form method="post">';
                echo '<input type="hidden" name="item_nr" value="'.$i.'">';
                echo '<button type="submit" name="remove_from_cart" class="remove-btn">Usuń</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
        }
    }
    
    echo '</div>';
    echo '<div class="cart-summary">';
    if ($unavailable_products) {
        echo '<div class="cart-warning">Niektóre produkty w koszyku są niedostępne. Usuń je, aby kontynuować zakupy.</div>';
    }
    echo '<h3>Podsumowanie</h3>';
    echo '<p class="total">Razem do zapłaty: '.number_format($total, 2).' PLN</p>';
    echo '</div>';
    echo '</div>';
    
    mysqli_close($link);
}

// Funkcja wyświetlania produktów
function PobierzProdukty() {
    $link = mysqli_connect($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['baza']);
    
    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id 
              WHERE p.availability_status = 'available' 
              ORDER BY p.creation_date DESC";
              
    $result = mysqli_query($link, $query);
    
    echo '<div class="shop-container">';
    
    // Wyświetl koszyk
    showCart();
    
    if(mysqli_num_rows($result) > 0) {
        echo '<div class="products-grid">';
        while($row = mysqli_fetch_array($result)) {
            echo '<div class="product-card">';
            echo '<div class="product-image">';
            echo '<img src="'.($row['image_url'] ? '..'.$row['image_url'] : '../img/no-image.png').'" alt="'.$row['title'].'">';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h3>'.$row['title'].'</h3>';
            echo '<p class="product-category">'.$row['category_name'].'</p>';
            echo '<p class="product-description">'.substr($row['description'], 0, 100).'...</p>';
            echo '<div class="product-price">';
            echo '<span class="price">'.number_format($row['net_price'] * (1 + $row['vat_rate']/100), 2).' PLN</span>';
            echo '</div>';
            echo '<form method="post">';
            echo '<input type="hidden" name="product_id" value="'.$row['id'].'">';
            echo '<input type="number" name="quantity" value="1" min="1" class="quantity-input">';
            echo '<button type="submit" name="add_to_cart" class="add-to-cart-btn">Dodaj do koszyka</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p class="no-products">Brak dostępnych produktów</p>';
    }
    
    echo '</div>';
    
    mysqli_close($link);
}

// Wyświetl produkty
PobierzProdukty();
?> 