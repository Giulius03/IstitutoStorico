<?php
$editOrDelete = $templateParams["action"] == "E" || $templateParams["action"] == "D";
if (isAdminLoggedIn()):
?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuovo" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." ".$templateParams['noPageType'] ?>
</h1>
<form action="../../utils/<?php echo $templateParams['actionFile'] ?>" method="POST" class="mx-5 mt-5" id="newNoPageForm">
    <div class="form-floating mb-3">
        <input name="Nome" type="text" class="form-control" id="Nome" placeholder="Nome" 
            value="<?php echo $editOrDelete ? $templateParams['content'][0]['name'] : "" ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
        <label for="Nome">Nome</label>
    </div>
    <?php if ($templateParams['noPageType'] == "menù"): ?>
    <input type="hidden" name="btnsDisab" id="btnsDisab" value="<?php echo $templateParams["action"] == "D" ? "true" : "false" ?>" />
    <input type="hidden" name="idMenu" id="idMenu" value="<?php echo $editOrDelete ? $templateParams['content'][0]['ID'] : ""?>" />
    <input type="hidden" name="idPartenza" id="idPartenza" />
    <input type="hidden" name="idFine" id="idFine" />
    <a class="btn btn-dark mb-3 <?php echo $templateParams["action"] == "D" ? "d-none" : "" ?>" id="btnAddMenuItem" role="button">Aggiungi una voce al menù</a>
    <div id="menuItemsForms">

    </div>
    <?php elseif ($templateParams['noPageType'] == "tag"): ?>
    <div class="form-floating mb-3">
        <textarea name="Descrizione" type="text" class="form-control" id="Descrizione" placeholder="Descrizione" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>><?php echo $editOrDelete ? $templateParams['content'][0]['description'] : "" ?></textarea>
        <label for="Descrizione">Descrizione</label>
    </div>
    <?php elseif ($templateParams['noPageType'] == "voce del menù"): ?>
    <div class="form-floating mb-3 pt-1">
        <div class="d-flex align-items-center">
            <label class="w-75" for="father">Seleziona il padre tramite il suo numero di voce (opzionale)</label>
            <select class="form-select w-25" name="father" id="father" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>>
                <option value="">Nessuno</option>
                <?php foreach($templateParams['otherItems'] as $item): ?>
                <?php if ($item['ID'] != $templateParams['content'][0]['ID']): ?>
                <option value="<?php echo $item['ID'] ?>"<?php echo $item['ID'] == $templateParams['content'][0]['father'] ? "selected" : "" ?>><?php echo $item['ID'] ?></option>
                <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-floating my-3">
            <input value="<?php echo $editOrDelete ? $templateParams['content'][0]['position'] : ""?>" name="Posizione" type="number" class="form-control" id="Posizione" placeholder="PosizioneVoce" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>/>
            <label for="Posizione">Posizione Voce</label>
        </div>
        <div class="border my-3 rounded pt-2 ps-2">
            <label>Seleziona la pagina collegata a questa voce del menù (opzionale)</label>
            <ul class="mt-2 p-0 listPagesContained" style="height: 150px;">
            <?php $pages = $dbh->getPages("title");
            foreach ($pages as $page): ?>
                <li class="form-check">
                    <input class="form-check-input" type="radio" name="linkToPage" value="<?php echo $page['idPage'] ?>" <?php echo $page['idPage'] == $templateParams['content'][0]['page'] ? "checked" : ""?> <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
                    <label class="form-check-label" for="linkToPage"><?php echo $page['title'] ?></label>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
        <?php if ($templateParams["action"] == "D"): ?>
        <div class="text-center pt-1">
            <p class="fst-italic">N.B. I figli di questa voce, al momento della sua eliminazione, verranno considerati figli diretti del menù.</p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="text-center my-4">
        <?php $previousPagePath = $templateParams['noPageType'] == "voce del menù" 
            ? "../editing/modifyMenu.php?id=".$_GET['idMenu'] 
            : "../../admin.php?cont=".$templateParams['noPageType'];?>
        <a class="btn btn-dark w-25 me-4" role="button" href="<?php echo $previousPagePath ?>">Torna indietro</a>
        <?php if ($templateParams["action"] == "E" || $templateParams["action"] == "I"): ?>
        <input class="btn btn-dark ms-4 w-25" type="submit" value="<?php echo $templateParams["action"] == "I" ? "Crea" : "Salva" ?>" />
        <?php else: ?>
        <button type="button" class="btn btn-dark ms-4 w-25" data-bs-toggle="modal" data-bs-target="#confirmElimination">Elimina</button>
        <?php endif; ?>    
    </div>
    <?php require_once("../../template/eliminationModal.php"); ?>
    </div>
</form>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>