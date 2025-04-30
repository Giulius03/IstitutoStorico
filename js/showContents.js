const contentTypeSelect = document.getElementById("contentType");

contentTypeSelect.addEventListener('change', function(event) {
    let btnInsertText = "";
    const addContDir = "contentsManagement/insertion/";
    switch (contentTypeSelect.value) {
        case "pages":
            btnInsertText = "Inserisci una nuova pagina";
            showContents("getPages.php?ordBy=updatedDate", addContDir + "newPage.php", btnInsertText, "pagine", [ "Titolo", "Ultima Modifica", "Tipo" ]);
            break;
        case "menu":
            btnInsertText = "Inserisci un nuovo menù";
            showContents("getMenus.php", addContDir + "newMenu.php", btnInsertText, "menù", [ "Nome" ]);
            break;
        case "tags":
            btnInsertText = "Inserisci un nuovo tag";
            showContents("getTags.php", addContDir + "newTag.php", btnInsertText, "tag", [ "Nome" ]);
            break;
        case "inventoryItem":
            btnInsertText = "Inserisci un nuovo articolo d'inventario";
            showContents("getInventoryItems.php", addContDir + "newInventoryItem.php", btnInsertText, "articoli d'inventario", [ "Nome" ]);
            break;
        case "referenceTools":
            btnInsertText = "Inserisci un nuovo strumento di corredo";
            showContents("getReferenceTools.php", addContDir + "newReferenceTool.php", btnInsertText, "strumenti di corredo", [ "Nome" ]);
            break;
    }
});

async function showContents(getterFile, link, btnInsertText, plural, fields) {
    const contents = await getContents(getterFile);
    let contentsHTML = `<a class="btn btn-dark" href="${link}" role="button">${btnInsertText}</a>`;
    if (contents.length === 0) {
        contentsHTML += `
        <div class="text-center pt-5">
            <p class="fst-italic">Al momento non sono presenti ${plural}.</p>
        </div>
        `;
    } else {
        let txtOrdered = plural === "pagine" ? "(ordinate per ultima modifica)" : "";
        contentsHTML += `
        <table class="table mt-3">
            <caption>${plural.charAt(0).toUpperCase() + plural.slice(1)} attualmente presenti ${txtOrdered}`;
        contentsHTML += `
            </caption>
            <thead>
                <tr>`;

        fields.forEach(field => {
            contentsHTML += `
                    <th scope="col">${field}</th>
            `;
        });
        contentsHTML += `
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
        `;
        contentsHTML += plural === "pagine" ? showPages(contents) : showOther(contents);
    }

    document.getElementById("contentsShower").innerHTML = contentsHTML;
}

function showPages(pages) {
    let html = ``;
    pages.forEach(page => {
        html += `
            <tr>
                <td class="align-middle">${page['title']}</td>
                <td class="align-middle">`;
        html += page['updatedDate'] === null ? `${page['creationDate']}</td>` : `${page['updatedDate']}</td>`
        html += `
                <td class="align-middle">${page['type']}</td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="#" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="#" role="button">Cancella</a>
                </td>
            </tr>`;
    });
    return html;
}

function showOther(contents) {
    let html = ``;
    contents.forEach(c => {
        html += `
            <tr>
                <td class="align-middle">${c['name']}</td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="#" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="#" role="button">Cancella</a>
                </td>
            </tr>
        `;
    });
    return html;
}

async function getContents(utilFunction) {
    const url = 'utils/getters/' + utilFunction;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        return json;
    } catch (error) {
        console.log(error.message);
    }
}