<h1 class="text-center fs-1 fw-bold mt-4">
    <?php echo ($templateParams["action"] == "I" ? "Inserisci nuova" : ($templateParams["action"] == "E" ? "Modifica" : "Cancella"))." pagina" ?>
</h1>
<form class="mx-5 mt-4" onsubmit="">
    <?php if ($templateParams["action"] == "I"): ?>
    <div class="d-flex flex-column align-items-center">
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
    </div>
    <?php endif; ?>
    <fieldset class="border-top mt-4">
        <ul class="newPageForm list-unstyled mt-4 px-2">
            <li class="form-floating mb-3">
                <input name="titolo" type="text" class="form-control" id="titolo" placeholder="Titolo" required />
                <label for="titolo">Titolo</label>
            </li>
            <li class="form-floating mb-3">
                <input name="slug" type="text" class="form-control" id="slug" placeholder="slug" required />
                <label for="slug">Slug</label>
            </li>
            <li>
                <label for="content">Inserisci il contenuto HTML della pagina:</label>
                <textarea class="form-control" name="content" id="content"></textarea>
                <iframe id="liveHTML"></iframe>
            </li>
            <!-- 
            <li class="text-center mb-4">
                <input class="btn btn-dark w-75" type="submit" id="btnLog" value="Entra" />
            </li> -->
            <li id="loginError" class="text-center mb-4">

            </li>
        </ul>
    </fieldset>
</form>