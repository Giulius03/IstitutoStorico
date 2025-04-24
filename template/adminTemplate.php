<h1 class="text-center fs-1 fw-bold mt-4">Gestione dei Contenuti</h1>
<div class="mx-4 mt-5" id="pagesContainer">
    <a class="btn btn-dark" href="newPage.php" role="button">Inserisci una nuova pagina</a>
    <?php $pages = $dbh->getPages();
    if (count($pages) == 0): ?>
    <div class="text-center pt-5">
        <p class="fst-italic">Al momento non sono presenti pagine.</p>
    </div>
    <?php else: ?>
    <table class="table mt-3">
        <caption>Pagine attualmente presenti</caption>
        <thead>
            <tr>
                <th scope="col">Titolo</th>
                <th scope="col">Ultima Modifica</th>
                <th scope="col">Tipo</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($pages as $page): ?>
            <tr>
                <td class="align-middle"><?php echo $page['title'] ?></td>
                <td class="align-middle"><?php echo ($page['updatedDate'] == null ? $page['creationDate'] : $page['updatedDate']) ?></td>
                <td class="align-middle"><?php echo $page['type'] ?></td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="#" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="#" role="button">Cancella</a>
                </td>
            </tr>
            <?php
            endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>