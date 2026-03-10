<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <!-- Polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Moon+Dance&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <title>Vite & Gourmand</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- ================= PAGE D’ACCUEIL ================= -->
    <header class="main-header">
        <div class="header-container">

            <div class="logo">
                <h1>Vite & Gourmand</h1>
                <p>Traiteur événementiel à Bordeaux depuis plus de 25 ans</p>
            </div>

            <nav class="main-nav">
                <a href="index.php">Accueil</a>
                <a href="menus.php">Menus</a>
                <a href="contact.php">Contact</a>

                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="login.php">Connexion</a>
                <?php else: ?>
                    <a href="mes_commandes.php">Mes commandes</a>
                    <?php if ($_SESSION['user']['role'] === 'employe'): ?>
                        <a href="employe/commandes_employe.php">Commandes clients</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    <a href="logout.php">Déconnexion</a>
                <?php endif; ?>
            </nav>


        </div>
    </header>

    <!-- Carousel d’images (mise en valeur des prestations) -->
    <section id="carousel">
        <img src="images/accueil/event1.jpg" class="active" alt="Événement Mariage">
        <img src="images/accueil/event2.jpg" alt="Événement Noël">
        <img src="images/accueil/event3.jpg" alt="Cocktail entreprise">
    </section>

    <!-- Présentation de l’entreprise -->
    <section>
        <h2>Présentation</h2>
        <p>
            Fondée par Julie et José, l’entreprise Vite & Gourmand accompagne
            particuliers et professionnels dans l’organisation de leurs événements
            grâce à des menus variés et adaptés à chaque occasion.
        </p>
    </section>

    <!-- Mise en avant du professionnalisme -->
    <section>
        <h2>Notre engagement</h2>
        <p>
            Notre équipe met son expérience et son savoir-faire au service de
            prestations fiables, ponctuelles et de qualité.
        </p>
    </section>

    <!-- Avis clients validés -->
    <section>
        <h2>Avis clients</h2>

        <article class="avis">
            <strong>★★★★★</strong>
            <p>Service impeccable et menus délicieux.</p>
        </article>

        <article class="avis">
            <strong>★★★★☆</strong>
            <p>Très bon rapport qualité/prix, équipe professionnelle.</p>
        </article>
    </section>

    <script src="js/carousel.js"></script>

    <footer>
        <div class="footer-content">

            <div class="footer-hours">
                <strong>Horaires :</strong>
                Lundi - Samedi : 9h00 - 19h00
            </div>

            <div class="footer-links">
                <a href="mentions.php">Mentions légales</a>
                <a href="cgv.php">Conditions générales de vente</a>
            </div>

        </div>
    </footer>

</body>

</html>