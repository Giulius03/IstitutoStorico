const contentTypeSelect = document.getElementById("contentType");

contentTypeSelect.addEventListener('change', function(event) {
    show();
});

function show() {
    let btnInsertText = "";
    const addContDir = "contentsManagement/insertion/";
    const editContDir = "contentsManagement/editing/";
    const removeContDir = "contentsManagement/elimination/";
    
    switch (contentTypeSelect.value) {
        case "Pagine":
            btnInsertText = "Inserisci una nuova pagina";
            showContents("getPages.php?ordBy=updatedDate", addContDir + "newPage.php", editContDir + "", removeContDir + "", btnInsertText, "pagine", [ "Titolo", "Ultima Modifica", "Tipo" ]);
            break;
        case "Menù":
            btnInsertText = "Inserisci un nuovo menù";
            showContents("getMenus.php", addContDir + "newMenu.php", editContDir + "modifyMenu.php", removeContDir + "removeMenu.php", btnInsertText, "menù", [ "Nome" ]);
            break;
        case "Tag":
            btnInsertText = "Inserisci un nuovo tag";
            showContents("getTags.php", addContDir + "newTag.php", editContDir + "modifyTag.php", removeContDir + "removeTag.php", btnInsertText, "tag", [ "Nome" ]);
            break;
        case "Articoli d'inventario":
            btnInsertText = "Inserisci un nuovo articolo d'inventario";
            showContents("getInventoryItems.php", addContDir + "newInventoryItem.php", editContDir + "modifyInvItem.php", removeContDir + "removeInvItem.php", btnInsertText, "articoli d'inventario", [ "Nome" ]);
            break;
        case "Strumenti di corredo":
            btnInsertText = "Inserisci un nuovo strumento di corredo";
            showContents("getReferenceTools.php", addContDir + "newReferenceTool.php", editContDir + "modifyRefTool.php", removeContDir + "removeRefTool.php", btnInsertText, "strumenti di corredo", [ "Nome" ]);
            break;
    }
}

async function showContents(getterFile, addLink, editLink, removeLink, btnInsertText, plural, fields) {
    const contents = await getContents(getterFile);
    let contentsHTML = `<a class="btn btn-dark" href="${addLink}" role="button">${btnInsertText}</a>`;
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
        contentsHTML += plural === "pagine" ? showPages(contents, editLink, removeLink) : showOther(contents, editLink, removeLink);
    }

    document.getElementById("contentsShower").innerHTML = contentsHTML;
}

function showPages(pages, editLink, removeLink) {
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
                    <a class="btn btn-secondary px-0 py-1" href="${editLink}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${removeLink}" role="button">Cancella</a>
                </td>
            </tr>`;
    });
    return html;
}

function showOther(contents, editLink, removeLink) {
    let html = ``;
    contents.forEach(c => {
        html += `
            <tr>
                <td class="align-middle">${c['name']}</td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="${editLink}?id=${c['ID']}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${removeLink}?id=${c['ID']}" role="button">Cancella</a>
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

show();