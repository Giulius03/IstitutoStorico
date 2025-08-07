<div class="container">
<?php 
if (count($templateParams["page"]) == 0) {
    echo "<div class='text-center pt-5'>
            <p class='fst-italic'>Pagina non trovata.</p>
        </div>";
} else if (!$templateParams["page"][0]["isVisibile"]) {
    echo "<div class='text-center pt-5'>
            <p class='fst-italic'>Pagina al momento non disponibile.</p>
        </div>";
} else {
    echo $templateParams["page"][0]["text"];
}
?>
</div>