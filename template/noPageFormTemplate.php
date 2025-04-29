<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuovo" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." menù" ?>
</h1>
<form action="../utils/addNewMenu.php" method="POST" class="mx-5 mt-5" id="newMenuForm">
    <input type="hidden" name="numeroVoci" id="numeroVoci" />
    <div class="form-floating mb-3">
        <input name="NomeMenu" type="text" class="form-control" id="NomeMenu" placeholder="NomeMenu" required />
        <label for="NomeMenu">Nome Menù</label>
    </div>
    <a class="btn btn-dark mb-3" id="btnAddMenuItem" role="button">Aggiungi una voce al menù</a>
    <div id="menuItemsForms">

    </div>
    <div class="text-center my-4">
        <input class="btn btn-dark w-25" type="submit" id="btnCreateMenu" value="Crea" />
    </div>
</form>