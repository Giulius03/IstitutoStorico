<?php
$editOrDelete = $templateParams["action"] == "E" || $templateParams["action"] == "D";
?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuovo" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." ".$templateParams['noPageType'] ?>
</h1>
<form action="../../utils/<?php echo $templateParams['actionFile'] ?>" method="POST" class="mx-5 mt-5" id="newNoPageForm">
    <input type="hidden" name="numeroVoci" id="numeroVoci" />
    <div class="form-floating mb-3">
        <input name="Nome" type="text" class="form-control" id="Nome" placeholder="Nome" 
            value="<?php echo $editOrDelete ? $templateParams['content'][0]['name'] : "" ?>" required 
            <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?> />
        <label for="Nome">Nome <?php echo $templateParams['noPageType'] ?></label>
    </div>
    <?php if ($templateParams['noPageType'] == "Menù"): ?>
    <a class="btn btn-dark mb-3" id="btnAddMenuItem" role="button">Aggiungi una voce al menù</a>
    <div id="menuItemsForms">

    </div>
    <?php elseif ($templateParams['noPageType'] == "Tag"): ?>
    <div class="form-floating mb-3">
        <textarea name="Descrizione" type="text" class="form-control" id="Descrizione" placeholder="Descrizione" required <?php echo $templateParams["action"] == "D" ? "disabled" : "" ?>><?php echo $editOrDelete ? $templateParams['content'][0]['description'] : "" ?></textarea>
        <label for="Descrizione">Descrizione <?php echo $templateParams['noPageType'] ?></label>
    </div>
    <?php endif; ?>
    <div class="text-center my-4">
        <a class="btn btn-dark w-25 me-4" role="button" href="../../admin.php?cont=<?php echo $templateParams['noPageType'] ?>">Torna indietro</a>
        <input class="btn btn-dark w-25 ms-4" type="submit" id="btnCreate"
        value="<?php echo ($templateParams["action"] == "I" ? "Crea" : ($templateParams["action"] == "E" ? "Salva" : "Elimina")) ?>" />
    </div>
</form>