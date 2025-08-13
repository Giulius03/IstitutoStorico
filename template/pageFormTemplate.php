<?php
$editOrDelete = $templateParams["action"] == "E" || $templateParams["action"] == "D";
if (isAdminLoggedIn()):
?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuova" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." pagina" ?>
</h1>
<form action="../../utils/<?php echo $templateParams["actionFile"] ?>" method="POST" class="mx-5 mt-4" id="newPageForm">
    <?php if ($templateParams["action"] == "I"): ?>
    <fieldset class="d-flex flex-column align-items-center">
        <label>Seleziona il tipo di pagina:</label>
        <div class="mt-2">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pageType" value="no" id="no" checked />
                <label class="form-check-label" for="no">Nessuno</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="pageType" value="archivio" id="archivio" />
                <label class="form-check-label" for="archivio">Pagina di Archivio</label>
            </div>
            <div class="form-check mt-1">
                <input class="form-check-input" type="radio" name="pageType" value="raccolta" id="raccolta" />
                <label class="form-check-label" for="raccolta">Raccolta di Risorse</label>
            </div>
        </div>
    </fieldset>
    <?php endif; ?>
    <input type="hidden" name="idPage" id="idPage" value="<?php echo $editOrDelete ? $_GET['id'] : "" ?>" />
    <input type="hidden" name="btnsDisab" id="btnsDisab" value="<?php echo $templateParams["action"] == "D" ? "true" : "false" ?>" />
    <fieldset class="border-top mt-4">
        <legend>Attributi</legend>
        <ul class="list-unstyled m-0 mt-5 px-2">
            <li class="form-floating mb-3">
                <input name="titolo" type="text" class="form-control" id="titolo" placeholder="Titolo" value="<?php echo $editOrDelete ? $templateParams['page']['title'] : "" ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="titolo">Titolo</label>
            </li>
            <li class="form-floating mb-3">
                <input name="slug" type="text" class="form-control" id="slug" placeholder="slug" value="<?php echo $editOrDelete ? $templateParams['page']['slug'] : "" ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="slug">Slug</label>
            </li>
            <li class="form-floating mb-3">
                <input name="autore" type="text" class="form-control" id="autore" placeholder="autore" value="<?php echo $editOrDelete ? $templateParams['page']['author'] : "" ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>/>
                <label for="autore">Autore</label>
            </li>
            <li class="form-check mb-3">
                <input class="form-check-input" type="checkbox" value="visible" id="visible" name="visible" <?php echo $templateParams["action"] == "I" || ($editOrDelete && $templateParams['page']['isVisibile'] == true) ? "checked" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label class="form-check-label" for="visible">Visibile</label>
            </li>
            <li class="mb-3">
                <label for="content" class="form-label">Inserisci il contenuto della pagina:</label>
                <textarea class="form-control" name="content" id="content" rows="10"><?php echo $editOrDelete ? $templateParams['page']['text'] : "" ?></textarea>
                <input type="hidden" name="tinymceDisabled" id="tinymceDisabled" value="<?php echo $templateParams['action'] == "D" ? "true" : "" ?>" />
            </li>
            <li class="mb-3">
                <label>Seleziona i tag a cui appartiene la pagina:</label>
                <ul class="mt-2 p-0">
                    <?php $tags = $dbh->getTags();
                    foreach ($tags as $tag): ?>
                    <li class="form-check me-5">
                        <input class="form-check-input" type="checkbox" value="<?php echo $tag['ID'] ?>" id="<?php echo "tag".$tag['ID'] ?>" name="<?php echo "tag".$tag['ID'] ?>" <?php echo $editOrDelete && in_array($tag['ID'], $templateParams["pageTags"]) ? "checked" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                        <label class="form-check-label" for="<?php echo "tag".$tag['ID'] ?>"><?php echo $tag['name'] ?></label>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </fieldset>
    <fieldset class="border-top mt-4">
        <legend>Search Engine Optimization</legend>
        <ul class="list-unstyled mt-5 px-2">
            <li class="form-floating mb-3">
                <input name="titoloSEO" type="text" class="form-control" id="titoloSEO" placeholder="TitoloSEO" value="<?php echo $editOrDelete ? $templateParams['page']['seoTitle'] : "" ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="titoloSEO">Titolo SEO</label>
            </li>
            <li class="form-floating mb-3">
                <input name="testoSEO" type="text" class="form-control" id="testoSEO" placeholder="testoSEO" value="<?php echo $editOrDelete ? $templateParams['page']['seoText'] : "" ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="testoSEO">Testo SEO</label>
            </li>
            <li class="form-floating mb-3">
                <input name="chiaviSEO" type="text" class="form-control" id="chiaviSEO" placeholder="ChiaviSEO" value="<?php echo $editOrDelete ? $templateParams['page']['seoKeywords'] : "" ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="chiaviSEO">Parole Chiave SEO</label>
            </li>
        </ul>
    </fieldset>
    <fieldset class="border-top my-4">
        <legend>Indice</legend>
        <a class="btn btn-dark text-decoration-none mb-3 <?php echo $templateParams["action"] == "D" ? "d-none" : "" ?>" id="btnAddIndexItem" role="button">Aggiungi una voce all'indice</a>
        <input type="hidden" name="numVoci" id="numVoci" value="0" />
        <div id="indexItemsForms">

        </div>
    </fieldset>
    <fieldset class="border-top my-4">
        <legend>Note</legend>
        <a class="btn btn-dark text-decoration-none mb-3 <?php echo $templateParams["action"] == "D" ? "d-none" : "" ?>" id="btnAddNote" role="button">Aggiungi una nota</a>
        <input type="hidden" name="numNote" id="numNote" value="0" />
        <div id="notesForms">

        </div>
    </fieldset>
    <fieldset class="border-top my-4">
        <legend>Pagine Contenute</legend>
        <label>Seleziona i tag le cui pagine saranno contenute in quella che stai creando:</label>
        <ul class="mt-2 p-0">
            <?php foreach ($tags as $tag): ?>
            <li class="form-check me-5">
                <input class="form-check-input" type="checkbox" value="<?php echo $tag['ID'] ?>" id="<?php echo "tagContenuto".$tag['ID'] ?>" name="<?php echo "tagContenuto".$tag['ID'] ?>" <?php echo $editOrDelete && in_array($tag['ID'], $templateParams["containedPagesTags"]) ? "checked" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label class="form-check-label" for="<?php echo "tagContenuto".$tag['ID'] ?>"><?php echo $tag['name'] ?></label>
            </li>
            <?php endforeach; ?>
        </ul>
    </fieldset>
    <div id="archivePageInfo" class="<?php echo $templateParams['action'] == "I" || (isset($templateParams['page']) && $templateParams['page']['type'] != "Pagina di Archivio") ? "d-none" : "" ?>">
        <fieldset class="border-top mb-4">
            <legend>Attributi Pagina di Archivio</legend>
            <div class="form-floating mt-3">
                <input step="1" name="dataInizio" type="number" class="form-control" id="dataInizio" placeholder="dataInizio" value="<?php echo $editOrDelete && isset($templateParams['archivePage']) ? $templateParams['archivePage'][0]['start'] : "" ?>" <?php echo $editOrDelete && isset($templateParams['archivePage']) ? "required" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="dataInizio">Data cronologica di inizio</label>
            </div>
            <div class="form-floating mt-3">
                <input step="1" name="dataFine" type="number" class="form-control" id="dataFine" placeholder="dataFine" value="<?php echo $editOrDelete && isset($templateParams['archivePage']) ? $templateParams['archivePage'][0]['end'] : "" ?>" <?php echo $editOrDelete && isset($templateParams['archivePage']) ? "required" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                <label for="dataFine">Data cronologica di fine</label>
            </div>
        </fieldset>
        <fieldset class="border-top mb-4">
            <legend>Strumenti di Corredo</legend>
            <label>Seleziona gli strumenti di corredo appartenenti alla pagina:</label>
            <ul class="mt-2 p-0">
                <?php $referenceTools = $dbh->getReferenceTools();
                foreach ($referenceTools as $rTool): ?>
                <li class="form-check me-5">
                    <input class="form-check-input" type="checkbox" value="<?php echo $rTool['ID'] ?>" id="<?php echo "strumento".$rTool['ID'] ?>" name="<?php echo "strumento".$rTool['ID'] ?>" 
                    <?php if (isset($templateParams["referenceTools"])) {
                        echo $editOrDelete && in_array($rTool['ID'], $templateParams["referenceTools"]) ? "checked" : ""; 
                    }?> 
                    <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                    <label class="form-check-label" for="<?php echo "strumento".$rTool['ID'] ?>"><?php echo $rTool['name'] ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
        <fieldset class="border-top border-bottom mb-4">
            <legend>Articoli d'Inventario</legend>
            <label>Seleziona gli articoli d'inventario della pagina e la rispettiva quantità:</label>
            <ul class="mt-2 p-0 list-unstyled" id="invItemList">
                <?php $inventoryItems = $dbh->getInventoryItems();
                foreach ($inventoryItems as $iItem): ?>
                <?php 
                $index = isset($templateParams["inventoryItems"]) ? array_search($iItem['ID'], array_column($templateParams["inventoryItems"], 'ID')) : false;
                $contained = $editOrDelete && $index !== false; ?>
                <li class="addingInvItem d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" value="<?php echo $iItem['ID'] ?>" id="<?php echo "articolo".$iItem['ID'] ?>" name="<?php echo "articolo".$iItem['ID'] ?>" <?php echo $contained ? "checked" : "" ?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                        <label class="form-check-label" for="<?php echo "articolo".$iItem['ID'] ?>"><?php echo $iItem['name'] ?></label>
                    </div>
                    <input step="1" min="1" name="<?php echo "quantita".$iItem['ID'] ?>" type="number" class="form-control py-1 ps-2 pe-0 <?php echo $contained ? "" : "d-none" ?>" id="<?php echo "quantita".$iItem['ID'] ?>" placeholder="Quantità" value="<?php echo $contained ? $templateParams['inventoryItems'][$index]['quantity'] : "" ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                    <label class="visually-hidden" for="<?php echo "quantita".$iItem['ID'] ?>"><?php echo "Quantità ".$iItem['name'] ?></label>
                </li>
                <?php endforeach; ?>
            </ul>
        </fieldset>
    </div>
    <fieldset id="resourceCollectorInfo" class="border-top mb-4 <?php echo $templateParams['action'] == "I" || (isset($templateParams['page']) && $templateParams['page']['type'] != "Raccolta di Risorse") ? "d-none" : "" ?>">
        <legend>Raccolte di Risorse</legend>
        <p class="fst-italic">Sarà possibile aggiungere elementi alle raccolte una volta creata la pagina.</p>
        <a class="btn btn-dark text-decoration-none mb-3 <?php echo $templateParams["action"] == "D" ? "d-none" : "" ?>" id="btnAddCollection" role="button">Aggiungi una raccolta di risorse</a>
        <input type="hidden" name="numCollections" id="numCollections" value="0" />
        <div id="collectionsForms">

        </div>
    </fieldset>
    <div class="text-center my-4">
        <a class="btn btn-dark w-25 me-4 text-decoration-none" role="button" href="../../admin.php?cont=Pagine">Torna indietro</a>
        <?php if ($templateParams["action"] == "E" || $templateParams["action"] == "I"): ?>
        <input class="btn btn-dark ms-4 w-25" type="submit" value="<?php echo $templateParams["action"] == "I" ? "Crea" : "Salva" ?>" />
        <?php else: ?>
        <button type="button" class="btn btn-dark ms-4 w-25" data-bs-toggle="modal" data-bs-target="#confirmElimination">Elimina</button>
        <?php endif; ?>    
    </div>
    <?php require_once("../../template/dontSaveModal.php"); ?>
</form>
<div class="modal fade" id="confirmElimination" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" id="eliminationForm" method="GET">
                <input type="hidden" name="id" id="contentid" />
                <input type="hidden" name="idPage" id="pageid" />
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle">Conferma eliminazione</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <input class="btn btn-danger" type="submit" value="Elimina" />
                </div>
            </form>
        </div>
    </div>
</div>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>