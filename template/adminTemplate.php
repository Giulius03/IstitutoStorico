<?php
$options = ['Pagine', 'Menù', 'Tag', 'Articoli d\'inventario', 'Strumenti di corredo'];
?>
<h1 class="text-center fs-1 fw-bold mt-4">Gestione dei Contenuti</h1>
<div class="mx-4 mt-5">
    <div id="selectorContainer">
        <label for="contentType">Scegli il tipo di contenuto da gestire:</label>
        <select class="form-select" name="contentType" id="contentType">
            <option value="" <?php echo !isset($_GET['cont']) ? "selected" : "" ?> disabled>-- Seleziona --</option>
            <?php foreach ($options as $opt): ?>
            <option <?php echo (isset($_GET['cont']) && strtolower(substr($_GET['cont'], 0, 3)) == strtolower(substr($opt, 0, 3))) ? "selected" : "" ?> value="<?php echo $opt ?>"><?php echo $opt ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mt-5" id="contentsShower">

    </div>
</div>