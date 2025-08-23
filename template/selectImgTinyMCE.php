<?php
$baseDir = realpath(IMAGES_PATH);
$currentDir = isset($_GET['path']) ? realpath($baseDir . '/' . $_GET['path']) : $baseDir;

if (strpos($currentDir, $baseDir) !== 0) {
    die("Accesso non consentito.");
}

$files = array_diff(scandir($currentDir), array('..', '.'));

function isImage($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

$relativePath = str_replace($baseDir, '', $currentDir);
?>
<?php if (isAdminLoggedIn()): ?>
<h3 class="text-center mt-4">Sfoglia immagini:</h3>
<div class="d-block text-center">
    <?php if ($currentDir !== $baseDir): ?>
        <a class="text-decoration-none" href="?path=<?php echo urlencode(dirname($relativePath)); ?>">Torna indietro</a>
    <?php endif; ?>
    <div class="gap-2 imageSelectorContainer mt-1">
        <?php foreach ($files as $file): 
            $fullPath = $currentDir . DIRECTORY_SEPARATOR . $file;
            $relativeFilePath = ltrim($relativePath . '/' . $file, '/');

            if (is_dir($fullPath)): ?>
                <div class="folder">
                    <a class="text-decoration-none" href="?path=<?php echo urlencode($relativeFilePath); ?>">📁 <?php echo $file; ?></a>
                </div>
            <?php elseif (isImage($file)): ?>
                <?php
                $webBasePath = IMAGES_URL; // percorso assoluto dalla root
                $webPath = $webBasePath . $relativeFilePath;
                ?>
                <img 
                    src="<?php echo $webPath; ?>" 
                    onclick="select('<?php echo $webPath; ?>')" 
                    alt="<?php echo $file; ?>" 
                    style="max-width: 150px; cursor: pointer;"
                />
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
<?php else: ?>
<div class="text-center">
    <p class="fst-italic mt-5">Devi essere loggato come amministratore per poter accedere a questa pagina.</p>
</div>
<?php endif; ?>