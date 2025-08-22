<?php
$edit = $templateParams["action"] == "E";
if (isAdminLoggedIn()):
?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuovo" : "Modifica")." ".$templateParams['noPageType'] ?>
</h1>
<form action="<?php echo $templateParams['actionFile'] ?>" method="POST" class="mx-5 mt-5" id="newNoPageForm">
    <div class="form-floating mb-3">
        <input name="Nome" type="text" class="form-control" id="Nome" placeholder="Nome" value="<?php echo $edit ? $templateParams['content'][0]['name'] : "" ?>" required />
        <label for="Nome">Nome</label>
    </div>
    <?php if ($templateParams['noPageType'] == "menù"): ?>
    <input type="hidden" name="idMenu" id="idMenu" value="<?php echo $edit ? $templateParams['content'][0]['ID'] : ""?>" />
    <input type="hidden" name="idPartenza" id="idPartenza" />
    <input type="hidden" name="idFine" id="idFine" />
    <a class="btn btn-dark text-decoration-none mb-3" id="btnAddMenuItem" role="button">Aggiungi una voce al menù</a>
    <div id="menuItemsForms">

    </div>
    <?php elseif ($templateParams['noPageType'] == "tag"): ?>
    <div class="form-floating mb-3">
        <textarea name="Descrizione" type="text" class="form-control" id="Descrizione" placeholder="Descrizione" required><?php echo $edit ? $templateParams['content'][0]['description'] : "" ?></textarea>
        <label for="Descrizione">Descrizione</label>
    </div>
    <?php elseif ($templateParams['noPageType'] == "voce del menù"): ?>
    <div class="form-floating mb-3 pt-1">
        <div class="d-flex align-items-center">
            <label class="w-75" for="father">Seleziona il padre tramite il suo numero di voce (opzionale)</label>
            <select class="form-select w-25" name="father" id="father">
                <option value="">Nessuno</option>
                <?php foreach($templateParams['otherItems'] as $item): ?>
                <?php if ($item['ID'] != $templateParams['content'][0]['ID']): ?>
                <option value="<?php echo $item['ID'] ?>"<?php echo $item['ID'] == $templateParams['content'][0]['father'] ? "selected" : "" ?>><?php echo $item['ID'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-floating my-3">
            <input value="<?php echo $edit ? $templateParams['content'][0]['position'] : ""?>" name="Posizione" type="number" class="form-control" id="Posizione" placeholder="PosizioneVoce" required />
            <label for="Posizione">Posizione Voce</label>
        </div>
        <div class="border my-3 rounded pt-2 ps-2">
            <label>Seleziona la pagina collegata a questa voce del menù (opzionale)</label>
            <ul class="mt-2 p-0 listPagesContained" style="height: 150px;">
            <?php $pages = $dbh->getPages("title");
            foreach ($pages as $page): ?>
                <li class="form-check">
                    <input class="form-check-input" type="radio" name="linkToPage" value="<?php echo $page['idPage'] ?>" <?php echo $page['idPage'] == $templateParams['content'][0]['page'] ? "checked" : ""?> />
                    <label class="form-check-label" for="linkToPage"><?php echo $page['title'] ?></label>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
    <div class="text-center my-4">
        <?php $previousPagePath = $templateParams['noPageType'] == "voce del menù" 
            ? (CONTENTS_EDITING_URL . "modifyMenu.php?id=".$_GET['idMenu'])
            : (ADMIN_PAGE_PATH . "?cont=".$templateParams['noPageType']);?>
        <a class="btn btn-dark w-25 me-4 text-decoration-none" role="button" href="<?php echo $previousPagePath ?>">Torna indietro</a>
        <input class="btn btn-dark ms-4 w-25" type="submit" value="<?php echo $templateParams["action"] == "I" ? "Crea" : "Salva" ?>" />   
    </div>
    <?php require_once(TEMPLATE_PATH . "dontSaveModal.php"); ?>
    </div>
</form>
<?php if ($templateParams['noPageType'] == "menù"): ?>
<div class="modal fade" id="confirmElimination" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo CONTENT_REMOVERS_SCRIPT_PATH ?>deleteMenuItem.php" id="eliminationForm" method="GET">
                <input type="hidden" name="id" id="contentid" />
                <input type="hidden" name="idMenu" id="menuid" />
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle">Conferma eliminazione</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>L'eliminazione di questa voce del menù sarà permanente. Proseguire?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                    <input class="btn btn-danger" type="submit" value="Elimina" />
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>