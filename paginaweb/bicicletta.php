<?php
// Connessione al database
$servername = "localhost";
$username = "damiantest";
$password = ""; // Password vuota
$dbname = "my_damiantest";

// Creazione connessione
$conn = new mysqli($servername, $username, $password, $dbname);

// Controllo connessione
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// Ottenere l'ID della bicicletta dalla query string
$bike_id = $_GET['id'];

// Query per recuperare le informazioni della bicicletta selezionata
$sql = "SELECT * FROM biciclette WHERE id = $bike_id";
$result = $conn->query($sql);

// Verifica se esistono i dati
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $prezzo = $row['prezzo']; // Recupera il prezzo dalla tabella biciclette
} else {
    echo "Bicicletta non trovata.";
    exit;
}

// Ottenere le immagini per questa bicicletta
$sql_images = "SELECT immagine FROM immagini_biciclette WHERE bike_id = $bike_id";
$result_images = $conn->query($sql_images);

$images = [];
while ($image = $result_images->fetch_assoc()) {
    $images[] = $image['immagine'];
}

// Query per recuperare le taglie disponibili per la bicicletta con il numero di pezzi disponibili
$sql_sizes = "SELECT taglia, pezzi_disponibili FROM taglie_biciclette WHERE bike_id = $bike_id";
$result_sizes = $conn->query($sql_sizes);

$sizes = [];
while ($size = $result_sizes->fetch_assoc()) {
    $sizes[] = [
        'taglia' => $size['taglia'],
        'pezzi_disponibili' => $size['pezzi_disponibili']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biciclette Mountain - Bike Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="contatti.css">
    <link rel="stylesheet" href="bicicletta.css">
    <style>

    .quantity-container select {
    width: 70%; /* Imposta la larghezza al 100% del suo contenitore */
    font-size: 18px; /* Aumenta la dimensione del testo */
    padding: 12px 20px; /* Aggiunge spazio interno per renderlo più grande */
    border-radius: 8px; /* Arrotonda gli angoli per un aspetto più morbido */
    border: 1px solid #ccc; /* Aggiungi un bordo grigio chiaro */
    background-color: #f5f5f5; /* Colore di sfondo chiaro */
    cursor: pointer; /* Mostra il cursore a forma di mano per interazione */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Aggiungi un'ombra leggera per evidenziare il menu */
    transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Aggiungi una transizione morbida per l'effetto hover */
}

.quantity-container select:hover {
    border-color: #28a745; /* Cambia il colore del bordo quando l'utente passa sopra */
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3); /* Aggiungi un'ombra più forte durante il passaggio del mouse */
}

        .info-box {
            padding: 20px;
            display: flex;
            flex-direction: column;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
            border-radius: 10px;
        }
        .bike-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .bike-price {
            font-size: 20px;
            color: #28a745;
            margin-bottom: 20px;
        }
        .bike-sizes {
            margin-bottom: 20px;
        }
        .size-box-container {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .size-box {
            background-color: #e0e0e0;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            width: 80px;
        }
        .size-box:hover {
            background-color: #007bff;
            color: white;
        }
        .size-box.selected {
            background-color: #28a745;
            color: white;
        }
        .quantity-container {
            margin-top: 20px;
        }
        .quantity-container input {
            padding: 5px;
            width: 50px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .add-to-cart-container {
            margin-top: 30px;
            text-align: center;
        }
        .add-to-cart-btn {
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .add-to-cart-btn:hover {
            background-color: #218838;
        }
        .cart-container {
            margin-left: auto;
        }
        .whatsapp-container {
            margin-top: 20px;
            text-align: center;
        }
        .whatsapp-container a {
            color: #25D366;
            font-weight: bold;
        }
        .whatsapp-icon {
            color: #25D366;
            font-size: 24px;
            margin-right: 10px;
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

<div class="info-box">
    <div class="info-item">
        <!-- Link per tornare indietro alla pagina mountain.php -->
        <a href="mountain.php" class="back-button">◀︎ Torna a mountain</a>

        <div class="main-content">
    <!-- Immagine principale -->
    <div class="main-image">
        <img src="immaginimountainnuovo/<?php echo htmlspecialchars($images[0]); ?>" alt="Bicicletta Mountain" id="mainImage">
        <!-- Frecce per navigazione -->
        <span class="arrow arrow-left" onclick="changeImage('prev')">&#10094;</span>
        <span class="arrow arrow-right" onclick="changeImage('next')">&#10095;</span>
    </div>



    <!-- Nome e prezzo della bicicletta (versione mobile) -->
    <div class="bike-details">
        <div class="bike-name">
            <?php echo htmlspecialchars($row['nome']); ?> <!-- Nome della bicicletta -->
        </div>

        <div class="bike-price">
            <?php echo "€" . number_format($prezzo, 2, ',', '.'); ?> <!-- Prezzo formattato con simbolo € -->
        </div>

        <div class="bike-sizes">
    <h3>Seleziona taglia:</h3>
    <div class="size-box-container">
        <?php if (!empty($sizes)): ?>
            <?php foreach ($sizes as $size): ?>
                <div class="size-box" onclick="selectSize(this)"
                     data-taglia="<?php echo htmlspecialchars($size['taglia']); ?>"
                     data-pezzi="<?php echo $size['pezzi_disponibili']; ?>">
                    <?php echo htmlspecialchars($size['taglia']); ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nessuna taglia disponibile</p>
        <?php endif; ?>
    </div>
</div>

<div class="quantity-container">
    <h3>Seleziona quantità:</h3>
    <select id="quantity" disabled>
        <!-- Le opzioni saranno generate dinamicamente tramite JavaScript -->
    </select>
</div>



       <div class="add-to-cart-container">
    <button
        class="add-to-cart-btn"
        id="addToCartBtn">
        Aggiungi al carrello
    </button>
</div>


        <div class="whatsapp-container">
                    <i class="fab fa-whatsapp whatsapp-icon"></i>
                    <p>Hai una domanda? Non vedi la tua taglia? <a href="https://wa.me/+393312726099?text=Vi%20contatto%20in%20merito%20a%20'<?php echo urlencode($row['nome']); ?>'%20se%20fosse%20disponibile">Contattaci su WhatsApp</a></p>
                </div>
    </div>
</div>
</div>
</div>
<footer>
    <p>&copy; 2024 Bike Garage. Tutti i diritti riservati.</p>
</footer>
<script>
    let currentImageIndex = 0;
const images = <?php echo json_encode($images); ?>;

// Funzione per cambiare immagine
function changeImage(direction) {
    if (direction === 'next') {
        currentImageIndex = (currentImageIndex + 1) % images.length;
    } else if (direction === 'prev') {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    }
    document.getElementById('mainImage').src = 'immaginimountainnuovo/' + images[currentImageIndex];
}

// Funzione per selezionare la taglia e limitare la quantità
function selectSize(element) {
    const selectedTaglia = element.getAttribute('data-taglia');
    const pezziDisponibili = parseInt(element.getAttribute('data-pezzi'));

    // Mostra il menu a tendina con le opzioni di quantità
    const quantitySelect = document.getElementById('quantity');

    // Rimuovi tutte le opzioni precedenti
    quantitySelect.innerHTML = '';

    // Aggiungi l'opzione di disabilitazione iniziale
    if (pezziDisponibili > 0) {
        quantitySelect.disabled = false;
    }

    // Aggiungi opzioni per la quantità da 1 fino al massimo dei pezzi disponibili
    for (let i = 1; i <= pezziDisponibili; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = i;
        quantitySelect.appendChild(option);
    }

    // Evidenzia la taglia selezionata
    const sizeBoxes = document.querySelectorAll('.size-box');
    sizeBoxes.forEach(box => box.classList.remove('selected'));
    element.classList.add('selected');
}

// Funzione per aggiungere al carrello
function addToCart() {
    // Ottieni i dati della bicicletta selezionata
    const selectedSize = document.querySelector('.size-box.selected');
    const quantity = document.getElementById('quantity').value;

    if (!selectedSize || !quantity) {
        alert('Seleziona una taglia e una quantità.');
        return;
    }

    const size = selectedSize.getAttribute('data-taglia');
    const pezziDisponibili = selectedSize.getAttribute('data-pezzi');
    const productId = <?php echo $bike_id; ?>; // ID del prodotto dalla query PHP
    const name = '<?php echo addslashes($row['nome']); ?>'; // Nome del prodotto
    const price = <?php echo $prezzo; ?>; // Prezzo della bicicletta
    const imageName = images[currentImageIndex]; // Ottieni l'immagine corrente

    // Prepara i dati da inviare
    const data = new FormData();
    data.append('action', 'add');
    data.append('id', productId);
    data.append('taglia', size);
    data.append('quantita', quantity);
    data.append('nome', name);
    data.append('prezzo', price);
    data.append('immagine', imageName); // Aggiungi il nome dell'immagine

    // Invia i dati a carrello.php utilizzando Fetch API
    fetch('carrello.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Stampa il risultato per debug
        alert('Prodotto aggiunto al carrello!');
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Errore nell\'aggiunta al carrello.');
    });
}


// Aggiungi evento al bottone per aggiungere al carrello
document.getElementById('addToCartBtn').addEventListener('click', addToCart);


</script>


<script src="scripts.js"></script>

</body>

</html>
