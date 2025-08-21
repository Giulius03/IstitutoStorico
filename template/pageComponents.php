<?php if (isAdminLoggedIn()): ?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo "Modifica ".$templateParams["componentName"]; ?>
</h1>
<form action="<?php echo UTILS_PATH . $templateParams["actionFile"] ?>" method="POST" class="mx-5 mt-4" id="pageComponentsForm">
    <ul class="list-unstyled m-0 mt-5 px-2">
    <?php if ($templateParams["componentName"] == "voce dell'indice"): ?>
        <li class="form-floating mb-3">
            <input name="titolo" type="text" class="form-control" id="titolo" placeholder="Titolo" value="<?php echo $templateParams["component"][0]['title'] ?>" required />
            <label for="titolo">Titolo</label>
        </li>
        <li class="form-floating mb-3">
            <input value="<?php echo $templateParams['component'][0]['position'] ?>" name="Posizione" type="number" class="form-control" id="Posizione" placeholder="PosizioneVoce" required />
            <label for="Posizione">Posizione Voce</label>
        </li>
        <li class="border mb-3 rounded pt-2 ps-2">
            <label>Seleziona la pagina collegata a questa voce dell'indice (opzionale)</label>
            <ul class="mt-2 p-0 listPagesContained" style="height: 150px;">
            <?php $pages = $dbh->getPages("title");
            foreach ($pages as $page): ?>
                <li class="form-check">
                    <input class="form-check-input" type="radio" name="linkToPage" value="<?php echo $page['idPage'] ?>" <?php echo $page['idPage'] == $templateParams['component'][0]['page'] ? "checked" : ""?> />
                    <label class="form-check-label" for="linkToPage"><?php echo $page['title'] ?></label>
                </li>
            <?php endforeach; ?>
            </ul>
        </li>
    <?php elseif ($templateParams["componentName"] == "nota"): ?>
        <li class="form-floating mb-3">
            <input name="testo" type="text" class="form-control" id="testo" placeholder="testo" value="<?php echo $templateParams["component"][0]['text'] ?>" required />
            <label for="testo">Testo</label>
        </li>
        <li class="form-floating mb-3">
            <input name="autore" type="text" class="form-control" id="autore" placeholder="autore" value="<?php echo $templateParams["component"][0]['author'] ?>" required />
            <label for="autore">Autore</label>
        </li>
    <?php endif; ?>
        <li class="form-floating mb-3">
            <input name="ancora" type="text" class="form-control" id="ancora" placeholder="ancora" value="<?php echo $templateParams["component"][0]['anchor'] ?>" required />
            <label for="ancora"><?php echo $templateParams["componentName"] == "nota" ? "Ancora nota" : "Ancora di destinazione" ?> (DEVE iniziare con #!)</label>
        </li>
    </ul>
    <div class="text-center my-4">
        <a class="btn btn-dark w-25 me-4 text-decoration-none" role="button" href="<?php echo CONTENT_EDITORS_SCRIPT_PATH . "modifyPage.php?id=".$_GET['idPage']?>">Torna indietro</a>
        <input class="btn btn-dark ms-4 w-25" type="submit" value="Salva" />   
    </div>
    <?php require_once(TEMPLATE_PATH . "dontSaveModal.php");?>
</form>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>