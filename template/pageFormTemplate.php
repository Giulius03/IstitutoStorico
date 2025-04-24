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
        <ul class="list-unstyled m-0 mt-4 px-2">
            <li class="form-floating mb-3">
                <input name="titolo" type="text" class="form-control" id="titolo" placeholder="Titolo" required />
                <label for="titolo">Titolo</label>
            </li>
            <li class="form-floating mb-3">
                <input name="slug" type="text" class="form-control" id="slug" placeholder="slug" required />
                <label for="slug">Slug</label>
            </li>
            <li class="mb-3">
                <label for="content" class="form-label">Inserisci il contenuto della pagina:</label>
                <textarea class="form-control" name="content" id="content" rows="10" required></textarea>
            </li>
            <li class="form-check">
                <input class="form-check-input" type="checkbox" value="visible" id="visible" name="visible" checked />
                <label class="form-check-label" for="visible">Visibile</label>
            </li>
        </ul>
    </fieldset>
    <fieldset class="border-top mt-4">
        <legend>Search Engine Optimization</legend>
        <ul class="list-unstyled mt-5 px-2">
            <li class="form-floating mb-3">
                <input name="titoloSEO" type="text" class="form-control" id="titoloSEO" placeholder="TitoloSEO" required />
                <label for="titoloSEO">Titolo SEO</label>
            </li>
            <li class="form-floating mb-3">
                <input name="testoSEO" type="text" class="form-control" id="testoSEO" placeholder="testoSEO" required />
                <label for="testoSEO">Testo SEO</label>
            </li>
            <li class="form-floating mb-3">
                <input name="chiaviSEO" type="text" class="form-control" id="chiaviSEO" placeholder="ChiaviSEO" required />
                <label for="chiaviSEO">Parole Chiave SEO</label>
            </li>
        </ul>
    </fieldset>
</form>
 <!-- 
            <li class="text-center mb-4">
                <input class="btn btn-dark w-75" type="submit" id="btnLog" value="Entra" />
            </li> -->