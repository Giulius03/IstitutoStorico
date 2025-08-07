<!DOCTYPE html>
<html lang="it">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo $templateParams["titolo"]; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />
        <script src="https://cdn.tiny.cloud/1/6gplww47ipt1mcj4201z6ifh6lzef44nk6f0ys88bvjvlymh/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <?php
        if(isset($templateParams["css"])):?>
        <link rel="stylesheet" type="text/css" href="<?php echo $templateParams["css"] ?>" />
        <?php endif; ?>
    </head>
    <body>
        <?php
        $baseDir = realpath("../sites/default/images/");
        $currentDir = isset($_GET['path']) ? realpath($baseDir . '/' . $_GET['path']) : $baseDir;

        // Sicurezza: impedisci directory traversal
        if (strpos($currentDir, $baseDir) !== 0) {
            die("Accesso non consentito.");
        }

        $files = array_diff(scandir($currentDir), array('..', '.'));

        function isImage($file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        }

        // Mostra il percorso relativo per navigare correttamente
        $relativePath = str_replace($baseDir, '', $currentDir);
        ?>

        <h3 class="text-center mt-4">Sfoglia immagini:</h3>
        <div class="d-block text-center">
            <?php if ($currentDir !== $baseDir): ?>
                <!-- Link per tornare indietro -->
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
                        $webBasePath = '/sites/default/images/'; // percorso assoluto dalla root
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
        <?php
        if(isset($templateParams["js"])):
            foreach($templateParams["js"] as $script):
        ?>
            <script src="<?php echo $script; ?>"></script>
        <?php
            endforeach;
        endif;
        ?>
    </body>
</html>