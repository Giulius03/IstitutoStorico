<?php
$files = array_diff(scandir(UPLOAD_DIR), array('..', '.'));
?>
<h3>Seleziona un'immagine:</h3>
<div class="d-flex flex-wrap gap-2 imageSelectorContainer">
    <?php foreach ($files as $file): ?>
        <img src="<?php echo UPLOAD_DIR . $file; ?>" onclick="select('<?php echo UPLOAD_DIR . $file; ?>')" alt="<?php echo $file; ?>" />
    <?php endforeach; ?>
</div>