<!DOCTYPE html>
<html lang="it">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
        <script src="https://cdn.tiny.cloud/1/6gplww47ipt1mcj4201z6ifh6lzef44nk6f0ys88bvjvlymh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <?php
        if(isset($templateParams["css"])):?>
        <link rel="stylesheet" type="text/css" href="<?php echo $templateParams["css"] ?>" />
        <?php endif; ?>
    </head>
    <body onload="<?php echo isset($templateParams["onloadFunctions"]) ? $templateParams["onloadFunctions"] : "" ?>">
        <main>
            <?php if (!$_SESSION["isAdmin"]): ?>
            <header class="d-flex justify-content-center align-items-center">
                <a href="." class="fs-4 fw-semibold text-dark text-decoration-none">Istituto storico di Forlì-Cesena</a>
            </header>
            <nav class="pb-3">
                <span class="bi bi-list float-start ps-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample"></span>
                <a href="./login.php" class="float-end pe-3 text-dark"><span class="bi bi-person-circle"></span></a>
            </nav>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div>
                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                    </div>
                    <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Dropdown button
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php 
        if(isset($templateParams["nome"])){
            require($templateParams["nome"]);
        } 
        ?>
        </main>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
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