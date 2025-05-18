<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "E" ? "Modifica" : "Cancella")." elemento di raccolta" ?>
</h1>
<form action="../../utils/<?php echo $templateParams["actionFile"] ?>" method="POST" class="mx-5 mt-4" id="collectionElemForm">
    <ul class="list-unstyled m-0 mt-5">
    <?php if ($_GET["type"] == "bibliografia"): ?>
        <li class="form-floating mb-3">
            <textarea name="cit" type="text" class="form-control" id="cit" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>><?php echo $templateParams['element'][0]['cit'] ?></textarea>
            <label for="cit">Citazione</label>
        </li>
    <?php elseif ($_GET["type"] == "cronologia"): ?>
        <li class="form-floating mb-3">
            <input name="localita" type="text" class="form-control" id="localita" placeholder="localita" value="<?php echo $templateParams['element'][0]['localita'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="localita">Località</label>
        </li>
    <?php elseif ($_GET["type"] == "emeroteca"): ?>
        <li class="form-floating mb-3">
            <input name="giornale" type="text" class="form-control" id="giornale" placeholder="giornale" value="<?php echo $templateParams['element'][0]['giornale'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="giornale">Testata Giornalistica</label>
        </li>
    <?php elseif ($_GET["type"] == "rete"): ?>
        <li class="form-floating mb-3">
            <input name="tipo" type="text" class="form-control" id="tipo" placeholder="tipo" value="<?php echo $templateParams['element'][0]['tipo'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="tipo">Tipologia Risorsa</label>
        </li>
        <li class="form-floating mb-3">
            <input name="fonte" type="text" class="form-control" id="fonte" placeholder="fonte" value="<?php echo $templateParams['element'][0]['fonte'] ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="fonte">Fonte</label>
        </li>
    <?php endif; ?>
    <?php if ($_GET["type"] == "emeroteca" || $_GET["type"] == "cronologia"): ?>
        <li class="form-floating mb-3">
            <input name="data" type="date" class="form-control" id="data" placeholder="data" value="<?php echo $templateParams['element'][0]['data'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="data">Data <?php echo $_GET["type"] == "emeroteca" ? "Pubblicazione" : "" ?></label>
        </li>
    <?php endif; ?>
    <?php if ($_GET["type"] == "fototeca" || $_GET["type"] == "cronologia"): ?>
        <li class="form-floating mb-3">
            <input name="descrizione" type="text" class="form-control" id="descrizione" placeholder="descrizione" value="<?php echo $templateParams['element'][0]['descr'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="descrizione">Descrizione</label>
        </li>
    <?php endif; ?>
    <?php if ($_GET["type"] == "emeroteca" || $_GET["type"] == "rete"): ?>
        <li class="form-floating mb-3">
            <input name="titolo" type="text" class="form-control" id="titolo" placeholder="titolo" value="<?php echo $templateParams['element'][0]['titolo'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="titolo">Titolo <?php echo $_GET["type"] == "emeroteca" ? "Articolo" : "Risorsa" ?></label>
        </li>
    <?php endif; ?>
    <?php if ($_GET["type"] == "emeroteca" || $_GET["type"] == "rete" || $_GET["type"] == "bibliografia"): ?>
        <li class="form-floating mb-3">
            <input name="href" type="text" class="form-control" id="href" placeholder="href" value="<?php echo $templateParams['element'][0]['href'] ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="href">HREF</label>
        </li>
    <?php endif; ?>
    <?php if ($_GET["type"] == "rete" || $_GET["type"] == "bibliografia"): ?>
        <li class="form-floating mb-3">
            <input name="doi" type="text" class="form-control" id="doi" placeholder="doi" value="<?php echo $templateParams['element'][0]['DOI'] ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="doi">DOI</label>
        </li>
    <?php endif; ?>
    </ul>
    <div class="text-center my-4">
        <?php $previousPagePath = $templateParams['action'] == "E" ? "" : "../editing/"; ?>
        <a class="btn btn-dark w-25 me-4" role="button" href="<?php echo $previousPagePath . "modifyResourceCollection.php?id=".$templateParams['element'][0]['raccolta']."&idPage=".$_GET['idPage']?>">Torna indietro</a>
        <input class="btn btn-dark ms-4 w-25" type="submit" id="btnCreatePage" value="<?php echo ($templateParams["action"] == "E" ? "Salva" : "Elimina") ?>" />
    </div>
</form>