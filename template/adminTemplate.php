<?php
$options = ['Pagine', 'Menù', 'Tag', 'Articoli d\'inventario', 'Strumenti di corredo'];
if (isAdminLoggedIn()):
?>
<h1 class="text-center fs-1 fw-bold mt-4" id="adminTitle"></h1>
<div class="mx-4 mt-4" id="contentsShower">
    
</div>
<?php else: ?>
<div class="text-center">
    <p class="fst-italic">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>