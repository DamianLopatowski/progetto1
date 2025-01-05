<?php

session_start();

// Connessione al database
$servername = "localhost";
$username = "damiantest";
$password = ""; // Password vuota
$dbname = "my_damiantest";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la connessione
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Controllo se il carrello esiste nella sessione, altrimenti lo creo
if (!isset($_SESSION['carrello'])) {
    $_SESSION['carrello'] = [];
}

// Aggiungere prodotti al carrello
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $bike_id = $_POST['id'];
        $taglia = $_POST['taglia'];
        $quantita = (int)$_POST['quantita'];
        $nome = $_POST['nome'];
        $prezzo = (float)$_POST['prezzo'];
        $immagine = $_POST['immagine']; // Nome dell'immagine

        // Controllo la disponibilità nel database
        $sql = "SELECT pezzi_disponibili FROM taglie_biciclette WHERE bike_id = ? AND taglia = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $bike_id, $taglia); // bind bike_id (int) e taglia (stringa)
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $disponibilita = (int)$row['pezzi_disponibili'];

            // Verifica se la quantità richiesta è maggiore di quella disponibile
            if ($quantita > $disponibilita) {
                // Limita la quantità disponibile
                $quantita = $disponibilita;
                echo "<script>alert('La quantità richiesta è maggiore di quella disponibile. Aggiunta la quantità disponibile.');</script>";
            }

            // Se la disponibilità è zero, non aggiungere al carrello
            if ($disponibilita == 0) {
                echo "<script>alert('Il prodotto selezionato non è più disponibile.');</script>";
                $quantita = 0;
            }
        } else {
            echo "<script>alert('Il prodotto selezionato non è disponibile.');</script>";
            $quantita = 0; // Se il prodotto non è trovato nel database, non aggiungere nulla
        }

        // Controllo se il prodotto con la stessa taglia è già nel carrello
        $found = false;
        foreach ($_SESSION['carrello'] as &$prodotto) {
            if ($prodotto['id'] === $bike_id && $prodotto['taglia'] === $taglia) {
                // Se il prodotto esiste già nel carrello, aggiorna la quantità
                $prodotto['quantita'] += $quantita;
                // Limita la quantità in base alla disponibilità
                if ($prodotto['quantita'] > $disponibilita) {
                    $prodotto['quantita'] = $disponibilita;
                    echo "<script>alert('La quantità nel carrello è stata limitata alla disponibilità.');</script>";
                }
                $found = true;
                break;
            }
        }
        unset($prodotto); // Buona pratica: liberare il riferimento

        // Se non trovato, aggiungilo al carrello
        if (!$found && $quantita > 0) {
            $_SESSION['carrello'][] = [
                'id' => $bike_id,
                'nome' => $nome,
                'taglia' => $taglia,
                'quantita' => $quantita,
                'prezzo' => $prezzo,
                'immagine' => $immagine, // Aggiungi l'immagine
            ];
        }
    } elseif ($_POST['action'] === 'remove') {
        $indice = $_POST['indice'];
        if (isset($_SESSION['carrello'][$indice])) {
            unset($_SESSION['carrello'][$indice]);
            $_SESSION['carrello'] = array_values($_SESSION['carrello']); // Riorganizzare gli indici
        }
    }
}

// Calcolare il totale del carrello
function calcolaTotale($carrello) {
    $totale = 0;
    foreach ($carrello as $prodotto) {
        $totale += $prodotto['quantita'] * $prodotto['prezzo'];
    }
    return $totale;
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello - Bike Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="carrello.css">
    <link rel="stylesheet" href="contatti.css">
    <link rel="stylesheet" href="bicicletta.css">
    <style>
        /* Aggiungi il tuo CSS qui */
        .carrello-container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .carrello-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .carrello-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .carrello-item-details {
            flex: 1;
            margin-left: 20px;
        }
        .carrello-item-actions {
            text-align: center;
        }
        .remove-btn {
            color: #ff0000;
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        .checkout-btn {
            display: block;
            margin: 20px auto;
            padding: 15px 30px;
            font-size: 18px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }
        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<nav>
    <div class="logo">
        <a href="index.html">Bike Garage</a>
    </div>
    <div class="navbar-links">
        <div class="dropdown">
            <a href="index.html">Biciclette</a>
            <div class="dropdown-content">
                <ul>
                    <li><a href="mountain.php">Mountain</a></li>
                    <li><a href="#">City</a></li>
                    <li><a href="#">Road</a></li>
                </ul>
            </div>
        </div>
        <div class="dropdown">
            <a href="#">eBike</a>
            <div class="dropdown-content">
                <ul>
                    <li><a href="#">Urban</a></li>
                    <li><a href="#">Sport</a></li>
                </ul>
            </div>
        </div>
        <a href="#">Accessori e Ricambi</a>
        <a href="#">Abbigliamento</a>
        <a href="contatti.html">Contatti</a>
    </div>
    <div class="hamburger" onclick="toggleMobileMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="cart-container">
        <a href="carrello.php" class="cart-link">
            <i class="fas fa-shopping-cart cart-icon"></i> <!-- Icona del carrello -->
        </a>
    </div>
</nav>

<!-- Menu mobile visibile solo quando attivo -->
<div class="mobile-menu" id="mobileMenu">
    <div class="dropdown-mobile">
        <a href="#">Biciclette</a>
        <div class="dropdown-content-mobile">
            <a href="mountain.php">Mountain</a>
            <a href="#">City</a>
            <a href="#">Road</a>
        </div>
    </div>
    <div class="dropdown-mobile">
        <a href="#">eBike</a>
        <div class="dropdown-content-mobile">
            <a href="#">Urban</a>
            <a href="#">Sport</a>
        </div>
    </div>
    <a href="#">Accessori e Ricambi</a>
    <a href="#">Abbigliamento</a>
    <a href="contatti.html">Contatti</a>
</div>


<div class="carrello-container">
    <h1>Il tuo carrello</h1>
    <?php if (count($_SESSION['carrello']) > 0): ?>
        <?php foreach ($_SESSION['carrello'] as $indice => $prodotto): ?>
            <div class="carrello-item">
                <img src="immaginimountainnuovo/<?php echo $prodotto['immagine']; ?>" alt="<?php echo $prodotto['nome']; ?>">
                <div class="carrello-item-details">
                    <h3><?php echo $prodotto['nome']; ?></h3>
                    <p>Taglia: <?php echo $prodotto['taglia']; ?></p>
                    <p>Quantità: <?php echo $prodotto['quantita']; ?></p>
                    <p>Prezzo unitario: €<?php echo number_format($prodotto['prezzo'], 2, ',', '.'); ?></p>
                    <p>Totale: €<?php echo number_format($prodotto['quantita'] * $prodotto['prezzo'], 2, ',', '.'); ?></p>
                </div>
                <div class="carrello-item-actions">
                    <form method="POST">
                        <input type="hidden" name="action" value="remove">
                        <input type="hidden" name="indice" value="<?php echo $indice; ?>">
                        <button type="submit" class="remove-btn">Rimuovi</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="totale">
            <h3>Totale carrello: €<?php echo number_format(calcolaTotale($_SESSION['carrello']), 2, ',', '.'); ?></h3>
        </div>
        <button class="checkout-btn" onclick="alert('Funzione di pagamento non ancora implementata!')">Procedi al pagamento</button>
    <?php else: ?>
        <p class="empty-cart">Il tuo carrello è vuoto. <a href="mountain.php">Inizia a fare shopping!</a></p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2024 Bike Garage. Tutti i diritti riservati.</p>
</footer>

<script src="scripts.js"></script>
</body>
</html>

