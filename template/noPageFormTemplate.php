<?php
$switch = [
    'menù' => fn() => "addNewMenu.php",
    'strumento di corredo' => fn() => "addNewRefTool.php",
    'articolo d\'inventario' => fn() => "addNewInvItem.php",
    'tag' => fn() => "addNewTag.php",
];
$action = ($switch[$templateParams['noPageType']])();
?>
<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuovo" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." ".$templateParams['noPageType'] ?>
</h1>
<form action="../utils/newContentAdders/<?php echo $action ?>" method="POST" class="mx-5 mt-5" id="newNoPageForm">
    <input type="hidden" name="numeroVoci" id="numeroVoci" />
    <div class="form-floating mb-3">
        <input name="Nome" type="text" class="form-control" id="Nome" placeholder="Nome" required />
        <label for="Nome">Nome <?php echo $templateParams['noPageType'] ?></label>
    </div>
    <?php if ($templateParams['noPageType'] == "menù"): ?>
    <a class="btn btn-dark mb-3" id="btnAddMenuItem" role="button">Aggiungi una voce al menù</a>
    <div id="menuItemsForms">

    </div>
    <?php elseif ($templateParams['noPageType'] == "tag"): ?>
    <div class="form-floating mb-3">
        <input name="Descrizione" type="text" class="form-control" id="Descrizione" placeholder="Descrizione" required />
        <label for="Descrizione">Descrizione <?php echo $templateParams['noPageType'] ?></label>
    </div>
    <?php endif; ?>
    <div class="text-center my-4">
        <input class="btn btn-dark w-25" type="submit" id="btnCreate" value="Crea" />
    </div>
</form>