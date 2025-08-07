<?php if (isAdminLoggedIn()): ?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "E" ? "Modifica" : "Cancella")." raccolta di risorse" ?>
</h1>
<form action="../../utils/<?php echo $templateParams["actionFile"] ?>" method="POST" class="mx-5 mt-4" id="resourceCollectionForm">
    <input type="hidden" name="btnsDisab" id="btnsDisab" value="<?php echo $templateParams["action"] == "D" ? "true" : "false" ?>" />
    <input type="hidden" name="idPage" id="idPage" value="<?php echo $_GET['idPage'] ?>" />
    <input type="hidden" name="idCollection" id="idCollection" value="<?php echo $_GET['id'] ?>" />
    <input type="hidden" name="numElems" id="numElems" value="0" />
    <ul class="list-unstyled m-0 mt-5">
        <li class="form-floating mb-3">
            <input name="Nome" type="text" class="form-control" id="Nome" placeholder="Nome" 
                value="<?php echo $templateParams['collection'][0]['nome'] ?>" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
            <label for="Nome">Nome</label>
        </li>
        <li class="my-3">
            <label for="path">Path Raccolta:</label>
            <input name="path" type="file" webkitdirectory directory class="form-control mt-1" id="path" placeholder="path" value="<?php echo $templateParams['collection'][0]['path'] ?>" <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
        </li>
        <li class="my-3">
            <label>Seleziona il tipo degli elementi che verranno aggiunti:</label>
            <fieldset id="elemTypeList">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="elemType" value="bibliografia" id="bibliografia" />
                    <label class="form-check-label" for="bibliografia">Elemento di bibliografia</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="elemType" value="cronologia" id="cronologia" />
                    <label class="form-check-label" for="cronologia">Elemento di cronologia</label>
                </div>
                <div class="form-check mt-1">
                    <input class="form-check-input" type="radio" name="elemType" value="emeroteca" id="emeroteca" />
                    <label class="form-check-label" for="emeroteca">Elemento di emeroteca</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="elemType" value="fototeca" id="fototeca" />
                    <label class="form-check-label" for="fototeca">Elemento di fototeca</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="elemType" value="rete" id="rete" />
                    <label class="form-check-label" for="rete">Risorsa in rete</label>
                </div>
            </fieldset>            
        </li>
    </ul>
    <button class="btn btn-dark mb-3 <?php echo $templateParams["action"] == "D" ? "d-none" : "" ?>" id="btnAddCollectionElem" disabled type="button">Aggiungi un elemento di raccolta</button>
    <div id="collectionElemsForms">

    </div>
    <div id="currentElemsTable">

    </div>
    <div class="text-center my-4">
        <?php $previousPagePath = $templateParams['action'] == "E" ? "" : "../editing/"; ?>
        <a class="btn btn-dark w-25 me-4 text-decoration-none" role="button" href="<?php echo $previousPagePath . "modifyPage.php?id=".$_GET['idPage']?>">Torna indietro</a>
        <?php if ($templateParams["action"] == "E"): ?>
        <input class="btn btn-dark ms-4 w-25" type="submit" value="Salva" />
        <?php else: ?>
        <button type="button" class="btn btn-dark ms-4 w-25" data-bs-toggle="modal" data-bs-target="#confirmElimination">Elimina</button>
        <?php endif; ?>
    </div>
    <?php require_once("../../template/eliminationModal.php");
    require_once("../../template/dontSaveModal.php"); ?>
</form>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>