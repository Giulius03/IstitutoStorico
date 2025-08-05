<?php
$options = ['Pagine', 'Menù', 'Tag', 'Articoli d\'inventario', 'Strumenti di corredo'];
if (isAdminLoggedIn()):
?>
<h1 class="text-center fs-1 fw-bold mt-4" id="adminTitle">Gestione dei Contenuti</h1>
<div class="mx-4 mt-5" id="contentsShower">
    <div class="text-center pt-3">
        <p class="fst-italic">Seleziona un contenuto da gestire.</p>
    </div>
</div>
<?php else: ?>
<div class="text-center pt-3">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>