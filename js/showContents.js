const PagesFilter = {
    NO: "nessuno",
    NORMAL: "normale",
    ARCHIVE: "archivio",
    COLLECTION: "collezione"
};

function getContParamName(cont) {
    switch(cont) {
        case "Menù":
            return "menù";
        case "Tag":
            return "tag";
        case "Articoli d'inventario":
            return "articolo d'inventario";
        case "Strumenti di corredo":
            return "strumento di corredo";
        default:
            return "Pagine";
    }
}

function underlineRightLink(rightLinkContent) {
     document.querySelectorAll('#pcNavbar .admin-list a').forEach(a => {
        a.style.textDecoration = a.dataset.content === rightLinkContent ? "underline" : "none";
    });
}

document.querySelectorAll('.admin-list').forEach(l => {
    l.addEventListener('click', function(e) {
        if (e.target.matches('a[data-content]')) {
            e.preventDefault();
            const content = e.target.dataset.content;
            underlineRightLink(content)
            show(content);
            history.replaceState(null, "", "/admin.php?cont=" + getContParamName(content));
        }
    })
});

window.addEventListener('DOMContentLoaded', () => {
    const contParam = new URLSearchParams(window.location.search).get('cont');
    switch(contParam) {
        case "menù":
            show("Menù");
            underlineRightLink("Menù");
            break;
        case "tag":
            show("Tag");
            underlineRightLink("Tag");
            break;
        case "articolo d'inventario":
            show("Articoli d'inventario");
            underlineRightLink("Articoli d'inventario");
            break;
        case "strumento di corredo":
            show("Strumenti di corredo");
            underlineRightLink("Strumenti di corredo");
            break;
        case "Pagine":
        default:
            show("Pagine");
            underlineRightLink("Pagine");
    }
});

function show(content, searching = false, pagesFilter = PagesFilter.NO) {
    let btnInsertText = "";
    const addContDir = "contentsManagement/insertion/";
    const editContDir = "contentsManagement/editing/";
    const removeContDir = "contentsManagement/elimination/";
    
    switch (content) {
        case "Pagine":
            btnInsertText = "Inserisci una nuova pagina";
            const pagesFields = [ "Titolo", "Ultima Modifica" ];
            if (pagesFilter === PagesFilter.NO) {
                pagesFields.push("Tipo");
            }
            showContents("getPages.php?ordBy=updatedDate", addContDir + "newPage.php", editContDir + "modifyPage.php", removeContDir + "removePage.php", btnInsertText, "pagine", pagesFields, searching, pagesFilter);
            break;
        case "Menù":
            btnInsertText = "Inserisci un nuovo menù";
            showContents("getMenus.php", addContDir + "newMenu.php", editContDir + "modifyMenu.php", removeContDir + "removeMenu.php", btnInsertText, "menù", [ "Nome" ], searching);
            break;
        case "Tag":
            btnInsertText = "Inserisci un nuovo tag";
            showContents("getTags.php", addContDir + "newTag.php", editContDir + "modifyTag.php", removeContDir + "removeTag.php", btnInsertText, "tag", [ "Nome" ], searching);
            break;
        case "Articoli d'inventario":
            btnInsertText = "Inserisci un nuovo articolo d'inventario";
            showContents("getInventoryItems.php", addContDir + "newInvItem.php", editContDir + "modifyInvItem.php", removeContDir + "removeInvItem.php", btnInsertText, "articoli d'inventario", [ "Nome" ], searching);
            break;
        case "Strumenti di corredo":
            btnInsertText = "Inserisci un nuovo strumento di corredo";
            showContents("getReferenceTools.php", addContDir + "newReferenceTool.php", editContDir + "modifyRefTool.php", removeContDir + "removeRefTool.php", btnInsertText, "strumenti di corredo", [ "Nome" ], searching);
            break;
    }
}

async function showContents(getterFile, addLink, editLink, removeLink, btnInsertText, plural, fields, isSearching, pagesFilter = null) {
    const contents = isSearching === false ?  await getContents(getterFile) : await searchContents(plural, document.getElementById("txtSearch").value);
    let titleArticle = "";
    switch(plural) {
        case "pagine":
            titleArticle = "delle";
            break;
        case "menù":
        case "tag":
            titleArticle = "dei";
            break;
        case "articoli d'inventario":
        case "strumenti di corredo":
            titleArticle = "degli";
            break;
    }
    document.getElementById("adminTitle").innerText = "Gestione " + titleArticle + " " + plural;
    const searchLabel = plural === "pagine" ? "Cerca per titolo ..." : "Cerca per nome ...";
    let contentsHTML = `
        <a class="btn btn-dark" href="${addLink}" role="button">${btnInsertText}</a>
        <div class="d-flex align-items-center mt-4">
            <label class="visually-hidden" for="txtSearch">${searchLabel}</label>
            <input type="text" class="form-control me-2" id="txtSearch" placeholder="${searchLabel}" />
            <a class="btn btn-dark w-25" href="#" role="button" id="btnSearch">Cerca</a>
        </div>`;
    if (contents.length === 0) {
        contentsHTML += `
        <div class="text-center pt-5">
            <p class="fst-italic">Al momento non sono presenti ${plural}.</p>
        </div>
        `;
    } else {
        let txtOrdered = plural === "pagine" ? "(ordinate per ultima modifica)" : "";
        if (plural === "pagine") {
            console.log(pagesFilter);
            contentsHTML += `
            <div class="mt-2" id="pagesFilters">
                <label>Filtra per il tipo della pagina che stai cercando:</label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pagesFilter" id="no" value="no" ${pagesFilter === PagesFilter.NO ? "checked" : ""} />
                        <label class="form-check-label" for="no">Nessun filtro</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pagesFilter" id="normal" value="normali" ${pagesFilter === PagesFilter.NORMAL ? "checked" : ""} />
                        <label class="form-check-label" for="normal">Normale</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pagesFilter" id="archive" value="archivi" ${pagesFilter === PagesFilter.ARCHIVE ? "checked" : ""} />
                        <label class="form-check-label" for="archive">Archivio</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pagesFilter" id="collection" value="collezioni" ${pagesFilter === PagesFilter.COLLECTION ? "checked" : ""} />
                        <label class="form-check-label" for="collection">Raccolta di risorse</label>
                    </div>
                </div>
            </div>`;
        }
        contentsHTML += `
        <table class="table mt-1">
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
        contentsHTML += plural === "pagine" ? showPages(contents, editLink, removeLink, pagesFilter) : showOther(contents, editLink, removeLink);
    }

    document.getElementById("contentsShower").innerHTML = contentsHTML;
    if (plural === "pagine") {
        document.querySelectorAll('input[name="pagesFilter"]').forEach (rb => {
            rb.addEventListener('change', () => {
                switch (rb.value) {
                    case "normali":
                        show("Pagine", isSearching, PagesFilter.NORMAL);
                        break;
                    case "archivi":
                        show("Pagine", isSearching, PagesFilter.ARCHIVE);
                        break;
                    case "collezioni":
                        show("Pagine", isSearching, PagesFilter.COLLECTION);
                        break;
                    default:
                        show("Pagine", isSearching, PagesFilter.NO);
                        break;
                }
            })
        });
    }

    document.getElementById("btnSearch").addEventListener('click', function(e) {
        e.preventDefault();
        switch (plural) {
            case "pagine":
                show("Pagine", true, pagesFilter);
                break;
            case "menù":
                show("Menù", true);
                break;
            case "tag":
                show("Tag", true);
                break;
            case "articoli d'inventario":
                show("Articoli d'inventario", true);
                break;
            case "strumenti di corredo":
                show("Strumenti di corredo", true);
                break;
        }
    });
}

function showPages(pages, editLink, removeLink, filter) {
    let html = ``;
    let filteredPages = null;
    switch (filter) {
        case PagesFilter.ARCHIVE:
            filteredPages = pages.filter(p => p['type'] === "Pagina di Archivio");
            break;
        case PagesFilter.COLLECTION:
            filteredPages = pages.filter(p => p['type'] === "Raccolta di Risorse");
            break;
        case PagesFilter.NORMAL:
            filteredPages = pages.filter(p => p['type'] === "");
            break;
        default:
            filteredPages = pages;
            break;
    }
    filteredPages.forEach(page => {
        html += `
            <tr>
                <td class="align-middle">${page['title']}</td>
                <td class="align-middle">`;
        html += page['updatedDate'] === null ? `${page['creationDate']}</td>` : `${page['updatedDate']}</td>`;
        if (filter === PagesFilter.NO) {
            html += `<td class="align-middle">${page['type']}</td>`;
        }
        html += `
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="${editLink}?id=${page['idPage']}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${removeLink}?id=${page['idPage']}" role="button">Cancella</a>
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

async function searchContents(type, researchString) {
    const url = 'utils/getters/getContentResearched.php?cont=' + type + '&string=' + researchString;
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