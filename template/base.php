<!DOCTYPE html>
<html lang="it">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
        <link href="https://fonts.googleapis.com/css2?family=Dosis&display=swap" rel="stylesheet">
        <script src="https://cdn.tiny.cloud/1/6gplww47ipt1mcj4201z6ifh6lzef44nk6f0ys88bvjvlymh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <?php
        if(isset($templateParams["css"])):?>
        <link rel="stylesheet" type="text/css" href="<?php echo $templateParams["css"] ?>" />
        <?php endif; ?>
    </head>
    <body class="bg-light" onload="<?php echo isset($templateParams["onloadFunctions"]) ? $templateParams["onloadFunctions"] : "" ?>">
        <main>
            <header class="d-flex justify-content-center align-items-center bg-white">
                <a href="<?php echo isAdminLoggedIn() ? "#" : "." ?>" class="fs-4 fw-semibold text-dark text-decoration-none">Istituto storico di Forlì-Cesena</a>
            </header>
            <div id="mobileNavbar" class="bg-white">
                <nav class="pb-3">
                    <span class="bi bi-list float-start ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuPrincipaleMobile" aria-controls="menuPrincipaleMobile"></span>
                    <?php if (!isAdminLoggedIn()): ?>
                    <a href="./login.php" class="float-end pe-3 text-dark" title="Accedi come amministratore"><span class="bi bi-person-fill-gear" aria-label="Accedi come amministratore"></span></a>
                    <?php else: ?>
                        <a class="float-end pe-3 text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="bi bi-person-fill-gear"><span></a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-decoration-none" href="#" id="logoutMobile">Logout</a></li>
                        </ul>
                    <?php endif; ?>
                </nav>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="menuPrincipaleMobile" aria-labelledby="menu">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="menu">Menù</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <?php if (!isAdminLoggedIn()): ?>
                        <ul id="mainMenuItems" class="m-0 p-0 list-unstyled fs-3 fw-semibold">

                        </ul>
                        <?php else: ?>
                        <ul class="m-0 p-0 list-unstyled fs-3 fw-semibold admin-list">
                            <li class="p-1"><a href="#" data-content="Pagine" class="text-dark text-decoration-none">Pagine</a></li>
                            <li class="p-1"><a href="#" data-content="Menù" class="text-dark text-decoration-none">Menù</a></li>
                            <li class="p-1"><a href="#" data-content="Tag" class="text-dark text-decoration-none">Tag</a></li>
                            <li class="p-1"><a href="#" data-content="Articoli d'inventario" class="text-dark text-decoration-none">Articoli d'inventario</a></li>
                            <li class="p-1"><a href="#" data-content="Strumenti di corredo" class="text-dark text-decoration-none">Strumenti di corredo</a></li>
                            <li class="p-1"><a href="#" data-content="Newsletter" class="text-dark text-decoration-none">Newsletter</a></li>
                        </ul> 
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id="pcNavbar" class="bg-white">
                <nav class="navbar pb-3 d-flex justify-content-center align-items-center">
                    <?php if (!isAdminLoggedIn()): ?>
                    <div class="row pe-5" id="navbarPC">
                        
                    </div>
                    <?php else: ?>
                    <ul class="m-0 p-0 list-unstyled d-flex fs-5 admin-list">
                        <li class="me-5"><a href="#" data-content="Pagine" class="text-dark">Pagine</a></li>
                        <li class="me-5"><a href="#" data-content="Menù" class="text-dark">Menù</a></li>
                        <li class="me-5"><a href="#" data-content="Tag" class="text-dark">Tag</a></li>
                        <li class="me-5"><a href="#" data-content="Articoli d'inventario" class="text-dark">Articoli d'inventario</a></li>
                        <li class="me-5"><a href="#" data-content="Strumenti di corredo" class="text-dark">Strumenti di corredo</a></li>
                        <li class="me-5"><a href="#" data-content="Newsletter" class="text-dark">Newsletter</a></li>
                    </ul>
                    <?php endif; ?>
                    <?php if (!isAdminLoggedIn()): ?>
                    <a href="./login.php" class="text-dark" title="Accedi come amministratore"><span class="bi bi-person-fill-gear" aria-label="Accedi come amministratore"><span><a>
                    <?php else: ?>
                    <div class="dropdown">
                        <a class="text-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="bi bi-person-fill-gear"><span></a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-decoration-none" href="#" id="logoutPC">Logout</a></li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </nav>
            </div>
            <?php 
            if(isset($templateParams["nome"])){
                require($templateParams["nome"]);
            } 
            ?>
            <?php if (!isAdminLoggedIn()): ?>
            <footer>
                <div class="row p-2">
                    <div class="col-md-4 text-center py-3">
                        <p class="m-0 p-0">
                            Copyright © 2014-2025 Istituto storico della Resistenza e dell'Età contemporanea di Forlì-Cesena<br/>
                            <strong class="pe-1">Sede di Forlì</strong>:
                            <a class="pe-1 fs-6" target="_blank" href="https://www.google.com/maps/place/via+Albicini+25,+Forli,+FC,+Italia">Casa Saffi, via Albicini 25, 47121 Forlì (FC)</a>
                            | tel. <a class="px-1 fs-6" href="tel: +39054328999">+39 0543 28999</a>
                            | e-mail: <a class="px-1 fs-6" href="mailto: istorecofo@gmail.com">istorecofo@gmail.com</a><br/>
                            <strong class="pe-1">Sede di Cesena</strong>:
                            <a class="pe-1 fs-6" target="_blank" href="https://www.google.com/maps/place/Contrada+Dandini,+5,+47521+Cesena+FC,+Italia">Palazzo Nadiani, contrada Dandini 5, 47521 Cesena (FC)</a>
                        </p>
                    </div>
                    <div class="col-md-4 text-center py-3">
                        <h3 class="fw-semibold">ISCRIVITI ALLA NOSTRA NEWSLETTER</h3>
                        <form onsubmit="subscribeToTheNewsletter(event)">
                            <input class="form-control mb-2" type="text" placeholder="Nome Cognome" name="nomeCognome" id="nomeCognome" required pattern="^[A-ZÀ-Ý][a-zà-ÿ]+(?:\s[A-ZÀ-Ý][a-zà-ÿ]+)+$" title="Inserisci nome e cognome, con la prima lettera maiuscola" />
                            <label class="visually-hidden" for="nomeCognome">Inserisci nome e cognome</label>
                            <input class="form-control mb-2" type="email" placeholder="Indirizzo e-mail" name="email" id="email" required />
                            <label class="visually-hidden" for="email">Inserisci indirizzo e-mail</label>
                            <div class="g-recaptcha mb-2" data-sitekey="6LerU3UrAAAAAIR-3Iha5OdEIdqRne8xOvc1uUSf"></div>
                            <input class="btn btn-success me-4" type="submit" value="Invia" />
                            <input class="btn btn-light" type="reset" value="Reimposta" />
                            <div id="newsletterRegResult" class="text-center mt-1">

                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-center py-3">
                        <p>
                            Le attività dell'Istituto sono realizzate con il contributo della
                            <a class="fs-6" href="https://www.regione.emilia-romagna.it/" target="_blank">Regione Emilia-Romagna</a>
                        </p>
                        <a href="https://www.regione.emilia-romagna.it/" target="_blank">
                            <img class="img-responsive" src="./sites/default/images/css/regione-emilia-romagna.png" alt="Regione Emilia-Romagna" />
                        </a>
                        <hr class="text-white" />
                        <ul class="list-unstyled row">
                            <li class="col-4">
                                <a href="https://www.facebook.com/istorecofo" target="_blank">
                                    <span class="bi bi-facebook"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
            <?php endif; ?>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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