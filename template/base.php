<!DOCTYPE html>
<html lang="it">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
        <link rel="stylesheet" type="text/css" href="./css/style.css" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
        <?php
        if(isset($templateParams["css"])):
            foreach($templateParams["css"] as $style):
        ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $style ?>" />
        <?php
            endforeach;
        endif;
        ?>
        <link rel="stylesheet" type="text/css" href="./css/mediaqueries.css" />
        <?php
        if (isset($templateParams["mediaqueries"])) {
            echo "<link rel='stylesheet' type='text/css' href='./" . $templateParams["mediaqueries"] . "' />";
        }
        ?>
    </head>
    <body onload="<?php echo isset($templateParams["onloadFunctions"]) ? $templateParams["onloadFunctions"] : "" ?>">
        <header>
            <nav>
                <ul>
                    <li>
                    <form id="searchFormPC" action="fromSearch.php" method="get">
                            <label for="searchBarPC">Cerca</label>
                            <input name="search" id="searchBarPC" type="search" placeholder="<?php echo $currentLanguage == "en" ? "Search" : "Cerca" ?> ..." />
                            <span class="bi bi-search"></span>
                        </form>
                    </li>
                    <li>
                        <button id="menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation"><span class="bi bi-list"></span></button>
                    </li>
                    <li>
                        <button id="btnSearchAppear" type="button"><span class="bi bi-search"></span></button>
                    </li>
                    <li>
                        <a class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button" id="productsLink"><span class="fa fa-shirt"></span><span id="prodTextPC"><?php echo $currentLanguage == "en" ? "Products" : "Prodotti" ?></span></a>
                        <ul id="productsMenu" class="dropdown-menu" aria-labelledby="productsLink">
                            <li><a id="kitsTextPC" class="dropdown-item" href="products.php?category=Divise"><?php echo $currentLanguage == "en" ? "Kits" : "Divise" ?></a></li>
                            <li><a id="hoodTextPC" class="dropdown-item" href="products.php?category=Felpe"><?php echo $currentLanguage == "en" ? "Hoodies" : "Felpe" ?></a></li>
                            <li><a id="tsTextPC" class="dropdown-item" href="products.php?category=Magliette"><?php echo $currentLanguage == "en" ? "T-Shirts" : "Magliette" ?></a></li>
                            <li><a id="capsTextPC" class="dropdown-item" href="products.php?category=Cappelli"><?php echo $currentLanguage == "en" ? "Caps" : "Cappelli" ?></a></li>
                            <li><a id="trouTextPC" class="dropdown-item" href="products.php?category=Pantaloni"><?php echo $currentLanguage == "en" ? "Trousers" : "Pantaloni" ?></a></li>
                            <li><a class="dropdown-item" href="products.php?category=Souvenirs">Souvenirs</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="tickets.php"><span class="fa fa-ticket"></span><span id="tickTextPC"><?php echo $currentLanguage == "en" ? "Tickets" : "Biglietti" ?></span></a>
                    </li>
                    <li>
                        <a href="<?php echo isUserAnAdmin() == false ? "index.php" : "adminHome.php" ?>"><img src="upload/Stemma.png" alt="Logo squadra. Torna alla Home."></a>
                    </li>
                    <li>
                        <a class="dropdown-toggle" role="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false"><span class="bi bi-person"></span><span id="userText"><?php echo isUserLoggedIn() ? $_SESSION["username"] : ($currentLanguage == "en" ? "User" : "Utente") ?></span></a>
                        <ul class="dropdown-menu" aria-labelledby="userMenuButton">
                            <?php if (!isUserLoggedIn()): ?>
                                <li><a id="logText" class="dropdown-item" href="login.php"><?php echo $currentLanguage == "en" ? "Log In" : "Accedi" ?></a></li>
                                <li><a id="signText" class="dropdown-item" href="signup.php"><?php echo $currentLanguage == "en" ? "Sign Up" : "Registrati" ?></a></li>
                            <?php else: ?>
                                <?php if(isUserAnAdmin() == false): ?>
                                <li><a id="cartText" class="dropdown-item" href="cart.php"><?php echo $currentLanguage == "en" ? "Cart" : "Carrello" ?></a></li>
                                <li><a id="ordersText" class="dropdown-item" href="orders.php"><?php echo $currentLanguage == "en" ? "Orders" : "Ordini" ?></a></li>
                                <li><a id="favText" class="dropdown-item" href="favourites.php"><?php echo $currentLanguage == "en" ? "Favourites" : "Preferiti" ?></a></li>
                                <?php endif; ?>
                                <li><a onclick="logOut('<?php echo $currentLanguage ?>')" id="logOutText" class="dropdown-item" href="index.php"><?php echo $currentLanguage == "en" ? "Log Out" : "Esci" ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <li>
                        <a href="notifications.php"><span class="bi bi-bell"></span><span id="notText"><?php echo $currentLanguage == "en" ? "Notifications" : "Notifiche" ?></span></a>
                    </li>
                    <li>
                        <a id="LangMenuButton" class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span id="currentFlag" class="<?php echo $currentLanguage == "en" ? "fi fi-gb" : "fi fi-it" ?>"></span><span id="langText"><?php echo $currentLanguage == "en" ? "ENG" : "ITA" ?></span></a>
                        <ul class="dropdown-menu" aria-labelledby="LangMenuButton">
                            <li><a id="btnIta2" onclick="setLang('it')" class="dropdown-item" role="button"><span class="fi fi-it"></span>Italiano</a></li>
                            <li><a id="btnEng2" onclick="setLang('en')" class="dropdown-item" role="button"><span class="fi fi-gb"></span>English</a></li>
                        </ul>
                    </li>
                </ul>
                <form action="fromSearch.php" method="get" id="searchPhone">
                    <label for="searchBar">Cerca</label>
                    <input name="search" id="searchBar" type="search" class="form-control" placeholder="<?php echo $currentLanguage == "en" ? "Search" : "Cerca" ?> ..." />
                    <button type="submit"><span class="bi bi-search"></span></button>
                </form>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h4 class="offcanvas-title" id="offcanvasNavbarLabel">Bugs Burnley Shop</h4>
                        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close"><span class="bi bi-x"></span></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item dropdown">
                                <a id="dropdownLink" class="nav-link dropdown-toggle" href="#" role="button">
                                    <?php echo $currentLanguage == "en" ? "Language" : "Lingua" ?>
                                </a>
                                <ul id="dropdownList">
                                    <li><a id="btnIta1" onclick="setLang('it')" class="nav-link" role="button"><span class="fi fi-it"></span>Italiano</a></li>
                                    <li><a id="btnEng1" onclick="setLang('en')" class="nav-link" role="button"><span class="fi fi-gb"></span>English</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a id="prodText" class="nav-link"><?php echo $currentLanguage == "en" ? "Products" : "Prodotti" ?></a>
                                <ul>
                                    <li><a id="clotText" class="nav-link"><?php echo $currentLanguage == "en" ? "Clothing" : "Abbigliamento" ?></a></li>
                                    <li>
                                        <ul>
                                        <li><a id="kitsText" href="products.php?category=Divise" class="nav-link"><?php echo $currentLanguage == "en" ? "Kits" : "Divise" ?></a></li>
                                            <li><a id="hoodText" href="products.php?category=Felpe" class="nav-link"><?php echo $currentLanguage == "en" ? "Hoodies" : "Felpe" ?></a></li>
                                            <li><a id="tsText" href="products.php?category=Magliette" class="nav-link"><?php echo $currentLanguage == "en" ? "T-Shirts" : "Magliette" ?></a></li>
                                            <li><a id="capsText" href="products.php?category=Cappelli" class="nav-link"><?php echo $currentLanguage == "en" ? "Caps" : "Cappelli" ?></a></li>
                                            <li><a id="trouText" href="products.php?category=Pantaloni" class="nav-link"><?php echo $currentLanguage == "en" ? "Trousers" : "Pantaloni" ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="products.php" class="nav-link">Souvenirs</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a id="tickText" class="nav-link" href="tickets.php"><?php echo $currentLanguage == "en" ? "Tickets" : "Biglietti" ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
        <?php 
        if(isset($templateParams["nome"])){
            require($templateParams["nome"]);
        } 
        ?>
        </main>
        <footer>
            <strong id="contText"><?php echo $currentLanguage == "en" ? "Contact Us" : "Contattaci" ?></strong>
            <ul>
                <li>
                    <ul>
                        <li><span class="bi bi-telephone"></span></li>
                        <li><p>+44 452 367 2241</p></li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li><span class="bi bi-instagram"></span></li>
                        <li><p>@bugsburnley_shop</p></li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li><span class="bi bi-youtube"></span></li>
                        <li><p>Bugs Burnley Shop</p></li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li><span class="fa-brands fa-facebook-f"></span></li>
                        <li><p>Bugs Burnley Shop</p></li>
                    </ul>
                </li>
                <li>
                    <ul>
                        <li><span class="bi bi-envelope"></span></li>
                        <li><p>bugsburnley.shop@gmail.com</p></li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li><span class="bi bi-c-circle"></span></li>
                <li><p id="copyText">2025 Bugs Burnley. <?php echo $currentLanguage == "en" ? "All Rights Reserved" : "Tutti i Diritti Riservati" ?>.</p></li>
            </ul>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
        <script src="./js/base.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
        <?php
        if(isset($templateParams["js"])):
            foreach($templateParams["js"] as $script):
        ?>
            <script src="<?php echo $script; ?>"></script>
        <?php
            endforeach;
        endif;
        ?>
    </body>
</html>