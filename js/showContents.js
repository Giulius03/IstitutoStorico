const contentTypeSelect = document.getElementById("contentType");

contentTypeSelect.addEventListener('change', function(event) {
    switch (contentTypeSelect.value) {
        case "pages":
            getPages();
            break;
    }
});

function showPages(pages) {
    let pagesHTML = `<a class="btn btn-dark" href="newPage.php" role="button">Inserisci una nuova pagina</a>`;
    if (pages.length == 0) {
        pagesHTML += `
        <div class="text-center pt-5">
            <p class="fst-italic">Al momento non sono presenti pagine.</p>
        </div>
        `;
    } else {
        pagesHTML += `
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
        `;
        pages.forEach(page => {
            pagesHTML += `
                <tr>
                    <td class="align-middle">${page['title']}</td>
                    <td class="align-middle">`;
            pagesHTML += page['updatedDate'] === null ? `${page['creationDate']}</td>` : `${page['updatedDate']}</td>`
            pagesHTML += `
                    <td class="align-middle">${page['type']}</td>
                    <td class="align-middle">
                        <a class="btn btn-secondary px-0 py-1" href="#" role="button">Modifica</a>
                    </td>
                    <td class="align-middle">
                        <a class="btn btn-danger px-0 py-1" href="#" role="button">Cancella</a>
                    </td>
                </tr>`;
        });

        document.getElementById("contentsShower").innerHTML = pagesHTML;
    }
}

async function getPages() {
    const url = 'utils/getPages.php';

    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        showPages(json);
    } catch (error) {
        console.log(error.message);
    }
}