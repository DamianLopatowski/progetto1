<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biciclette Mountain - Bike Garage</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="contatti.css">
    <style>
        /* Stile base per layout desktop */
        .info-box {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px; /* Aumentato lo spazio tra gli elementi */
        }

        .info-item {
            width: 45%; /* Due biciclette affiancate su schermi grandi */
            box-sizing: border-box;
            margin-bottom: 30px;
        }

        .info-item img {
            width: 100%;
            border-radius: 12px;
            object-fit: cover; /* Assicura che l'immagine si adatti senza distorsioni */
        }

        .info-item h3 {
            font-size: 1.8rem; /* Dimensione titolo desktop */
            margin: 10px 0;
        }

        .info-item p {
            font-size: 1.2rem; /* Testo normale desktop */
            line-height: 1.5;
        }

        /* Stile per il selettore di taglie */
        #taglia-selector {
            text-align: center;
            margin: 20px 0;
        }

        #taglia {
            padding: 10px;
            font-size: 1.2rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        /* Stile per dispositivi mobili */
        @media screen and (max-width: 768px) {
            .info-box {
                display: flex;
                overflow-x: auto; /* Permette lo scorrimento orizzontale */
                flex-wrap: nowrap; /* Evita che gli articoli vadano a capo */
                gap: 15px; /* Distanza tra i prodotti */
                padding: 15px 10px; /* Padding per migliorare la separazione */
                justify-content: start; /* Allineamento a sinistra */
            }

            .info-item {
                width: 100%; /* Ogni prodotto occuperà l'intera larghezza disponibile */
                flex: 0 0 auto; /* Non farà il wrapping e manterrà la larghezza definita */
                background-color: #f9f9f9; /* Colore di sfondo chiaro */
                border-radius: 10px; /* Bordo arrotondato */
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Ombra per effetto tridimensionale */
                text-align: center;
                padding: 15px; /* Spazio interno per il contenuto */
                min-width: 80%; /* Assicura che ogni articolo sia abbastanza largo */
                box-sizing: border-box; /* Include il padding nella larghezza */
            }

            .info-item img {
                width: 100%; /* L'immagine occupa tutta la larghezza del contenitore */
                height: auto; /* Mantiene le proporzioni */
                object-fit: cover; /* L'immagine si adatta senza distorsioni */
                border-radius: 10px; /* Bordi arrotondati per l'immagine */
            }

            .info-item h3 {
                font-size: 1.8rem; /* Titolo leggibile e ben visibile (uguale a desktop) */
                margin: 15px 0; /* Distanza tra titolo e immagine */
            }

            .info-item p {
                font-size: 1.2rem; /* Font per descrizione e prezzo (uguale a desktop) */
                line-height: 1.5;
                margin: 10px 0;
            }

            .info-item p strong {
                font-size: 1.6rem; /* Prezzo evidenziato con un font maggiore (uguale a desktop) */
            }
        }

        .hidden {
            display: none;
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
</nav>

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

<header class="header-contatti">
    <h1>Biciclette Mountain</h1>
    <p class="subheader">Scopri la nostra selezione di biciclette da montagna.</p>
    <p class="subheader">
    Se non trovi la bicicletta del colore o della taglia che desideri, contattaci direttamente su
    <a href="https://wa.me/393312726099?text=Salve,%20vorrei%20maggiore%20informazione%20sulla%20bicicletta" target="_blank" style="color: green; text-decoration: underline; text-decoration-color: green;">WhatsApp</a>
</p>
</header>


<section id="taglia-selector">
    <label for="taglia">Seleziona la taglia:</label>
    <select id="taglia" onchange="filterBikes()">
        <option value="">Tutte</option>
        <!-- Le taglie saranno caricate dinamicamente -->
        <?php
        // Connessione al database
        $servername = "localhost";
        $username = "damiantest";
        $password = "";
        $dbname = "my_damiantest";

        // Creazione connessione
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Controllo connessione
        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        // Query per recuperare le taglie uniche
        $sql = "SELECT DISTINCT taglia FROM taglie_biciclette";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($row["taglia"]) . '">' . htmlspecialchars($row["taglia"]) . '</option>';
            }
        }

        $conn->close();
        ?>
    </select>
</section>

<section id="mountain-bikes" class="contact-info">
    <div class="container">
        <h2>La Nostra Offerta</h2>
        <div class="info-box">
            <?php
            // Connessione al database
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connessione fallita: " . $conn->connect_error);
            }

            // Query per recuperare tutte le biciclette di tipo "mountain" con le loro taglie
            $sql = "SELECT b.id, b.nome, b.descrizione, b.prezzo, b.immagine, GROUP_CONCAT(t.taglia) AS taglie
                    FROM biciclette b
                    LEFT JOIN taglie_biciclette t ON b.id = t.bike_id
                    WHERE b.tipo = 'mountain'
                    GROUP BY b.id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $taglie = explode(',', $row["taglie"]); // Estrai tutte le taglie per ogni bicicletta
                    $taglieString = implode(', ', $taglie); // Unisci le taglie per visualizzarle

                    echo '<div class="info-item" data-taglia="' . htmlspecialchars($taglieString) . '">';
                    echo '<a href="bicicletta.php?id=' . htmlspecialchars($row["id"]) . '">';
                    echo '<img src="immaginimountainnuovo/' . htmlspecialchars($row["immagine"]) . '" alt="' . htmlspecialchars($row["nome"]) . '">';
                    echo '<h3>' . htmlspecialchars($row["nome"]) . '</h3>';
                    echo '<p>' . htmlspecialchars($row["descrizione"]) . '</p>';
                    echo '<p><strong>Prezzo:</strong> €' . htmlspecialchars($row["prezzo"]) . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nessuna bicicletta disponibile al momento.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2024 Bike Garage. Tutti i diritti riservati.</p>
</footer>

<script>
    // Filtrare le biciclette in base alla taglia selezionata
    function filterBikes() {
        const selectedTaglia = document.getElementById('taglia').value;
        const bikes = document.querySelectorAll('.info-item');

        bikes.forEach(bike => {
            const bikeTaglie = bike.getAttribute('data-taglia');
            if (selectedTaglia === '' || bikeTaglie.includes(selectedTaglia)) {
                bike.classList.remove('hidden');
            } else {
                bike.classList.add('hidden');
            }
        });
    }
</script>

<script src="scripts.js"></script>

</body>
</html>

