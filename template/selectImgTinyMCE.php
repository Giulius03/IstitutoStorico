<?php
$dir = "../upload/";
$files = array_diff(scandir($dir), array('..', '.'));
?>
<h3>Seleziona un'immagine:</h3>
<div class="d-flex flex-wrap gap-2 imageSelectorContainer">
    <?php foreach ($files as $file): ?>
        <img src="<?php echo $dir . $file; ?>" onclick="select('<?php echo $dir . $file; ?>')" alt="<?php echo $file; ?>" />
    <?php endforeach; ?>
</div>