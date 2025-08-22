const PagesFilter = {
    NO: "nessuno",
    NORMAL: "normale",
    ARCHIVE: "archivio",
    COLLECTION: "collezione"
};

const Sorting = {
    NO: "nessuno",
    TITLE: "titolo",
    TITLE_DESC: "titoloDesc",
    NAME: "nome",
    NAME_DESC: "nomeDesc",
    LAST_EDIT: "ultimaModifica",
    LAST_EDIT_DESC: "ultimaModificaDesc"
};

let lastSorting = Sorting.NO;

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
        case "Pagine":
            return "Pagine";
        case "Newsletter":
            return "Newsletter";
    }
}

/**
 * Sottolinea la voce del menù dell'amministratore una volta eseguito un click su di essa.
 * @param {string} rightLinkContent Contenuto sul quale si è cliccato.
 */
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
            if (content !== "Newsletter") {
                underlineRightLink(content)
                show(content);
                lastSorting = Sorting.NO;
                history.replaceState(null, "", "/admin.php?cont=" + getContParamName(content));
            } else {
                window.location.href = ADMIN_PAGE_URL + "?cont=Newsletter";
            }
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
        case "Newsletter":
            show("Newsletter");
            underlineRightLink("Newsletter");
            break;
        default:
            show("Pagine");
            underlineRightLink("Pagine");
            break;
    }
});

/**
 * Funzione "tramite", che richiama la funzione ShowContents passando i giusti parametri in base al contenuto che si vuole gestire.
 * @param {string} content Tipo di contenuto di cui si vogliono visualizzare gli elementi.
 * @param {boolean} searching True se si sta eseguendo un filtro tramite la barra di ricerca, False altrimenti. 
 * @param {string} sorting Criterio di ordinamento dei contenuti. Di default non si esegue un ordinamento specifico.
 * @param {string} pagesFilter Utile solo se il contenuto è "Pagine": le pagine vengono filtrate in base al tipo selezionato (archivio, raccolta, ecc.)
 */
function show(content, searching = false, sorting = Sorting.NO, pagesFilter = PagesFilter.NO) {
    let btnInsertText = "";
    let eliminationMessage = "";
    
    switch (content) {
        case "Pagine":
            btnInsertText = "Inserisci una nuova pagina";
            eliminationMessage = "L'eliminazione di questa pagina sarà permanente e comporterà anche la cancellazione di tutte le note, di tutte le voci del suo indice, di tutti gli eventuali collegamenti con strumenti di corredo, articoli d'inventario, tag, pagine contenute e di tutte le eventuali raccolte di risorse inserite precedentemente (compresi i rispettivi elementi).";
            const pagesFields = [ "Titolo", "Ultima Modifica" ];
            if (pagesFilter === PagesFilter.NO) {
                pagesFields.push("Tipo");
            }
            showContents("getPages.php", CONTENTS_INSERTING_URL + "newPage.php", CONTENTS_EDITING_URL + "modifyPage.php", CONTENT_REMOVERS_SCRIPT_URL + "deletePage.php", eliminationMessage, btnInsertText, "pagine", pagesFields, searching, sorting, pagesFilter);
            break;
        case "Menù":
            btnInsertText = "Inserisci un nuovo menù";
            eliminationMessage = "L'eliminazione di questo menù sarà permanente e comporterà anche la cancellazione di tutte le sue voci.";
            showContents("getMenus.php", CONTENTS_INSERTING_URL + "newMenu.php", CONTENTS_EDITING_URL + "modifyMenu.php", CONTENT_REMOVERS_SCRIPT_URL + "deleteMenu.php", eliminationMessage, btnInsertText, "menù", [ "Nome" ], searching, sorting);
            break;
        case "Tag":
            btnInsertText = "Inserisci un nuovo tag";
            eliminationMessage = "L'eliminazione di questo tag sarà permanente.";
            showContents("getTags.php", CONTENTS_INSERTING_URL + "newTag.php", CONTENTS_EDITING_URL + "modifyTag.php", CONTENT_REMOVERS_SCRIPT_URL + "deleteTag.php", eliminationMessage, btnInsertText, "tag", [ "Nome" ], searching, sorting);
            break;
        case "Articoli d'inventario":
            btnInsertText = "Inserisci un nuovo articolo d'inventario";
            eliminationMessage = "L'eliminazione di questo articolo d'inventario sarà permanente.";
            showContents("getInventoryItems.php", CONTENTS_INSERTING_URL + "newInvItem.php", CONTENTS_EDITING_URL + "modifyInvItem.php", CONTENT_REMOVERS_SCRIPT_URL + "deleteInvItem.php", eliminationMessage, btnInsertText, "articoli d'inventario", [ "Nome" ], searching, sorting);
            break;
        case "Strumenti di corredo":
            btnInsertText = "Inserisci un nuovo strumento di corredo";
            eliminationMessage = "L'eliminazione di questo strumento di corredo sarà permanente.";
            showContents("getReferenceTools.php", CONTENTS_INSERTING_URL + "newReferenceTool.php", CONTENTS_EDITING_URL + "modifyRefTool.php", CONTENT_REMOVERS_SCRIPT_URL + "deleteRefTool.php", eliminationMessage, btnInsertText, "strumenti di corredo", [ "Nome" ], searching, sorting);
            break;
        case "Newsletter":
            showNewsletterSendingForm();
            break;        
    }
}

/**
 * Visualizza il form per l'invio di notifiche per gli iscritti alla newsletter.
 */
function showNewsletterSendingForm() {
    document.getElementById("adminTitle").innerText = "Invia mail agli iscritti alla newsletter";
    document.getElementById("contentsShower").innerHTML = `
    <form action="${UTILS_URL}sendMessageNewsletter.php" method="POST">
        <ul class="list-unstyled m-0 mt-5 px-2">
            <li class="form-floating mb-3">
                <input name="object" type="text" class="form-control" id="object" placeholder="Oggetto" required />
                <label for="object">Oggetto</label>
            </li>
            <li class="mb-3">
                <label for="mailContent" class="form-label">Inserisci il contenuto HTML della mail:</label>
                <textarea class="form-control" name="body" id="mailContent" rows="10"></textarea>
            </li>
            <li class="text-center mb-3">
                <input class="btn btn-dark w-25 me-5" type="reset" value="Reimposta" />
                <input class="btn btn-dark w-25" type="submit" value="Invia" />
            </li>
        </ul>
    </form>`;
}

/**
 * Crea la struttura HTML tramite la quale sarà possibile visualizzare, aggiungere, filtrare, modificare ed eliminare un contenuto, di qualsiasi tipo.
 * @param {string} getterFile Stringa che rappresenta il percorso per raggiungere lo script utile a ricavare gli elementi del tipo di contenuto selezionato.
 * @param {string} addLink Stringa che rappresenta il percorso per raggiungere la pagina nella quale si può inserire un nuovo elemento del tipo di contenuto selezionato.
 * @param {string} editLink Stringa che rappresenta il percorso per raggiungere la pagina nella quale si può modificare un elemento del tipo di contenuto selezionato.
 * @param {string} removeLink Stringa che rappresenta il percorso per raggiungere lo script utile ad eliminare un elemento del tipo di contenuto selezionato.
 * @param {string} eliminationMessage Stringa che verrà mostrata nel modale di conferma eliminazione (varia per ogni contenuto).
 * @param {string} btnInsertText Testo che verrà mostrato nel bottone per raggiungere la pagina di inserimento.
 * @param {string} plural Stringa utile per varie label e controlli. 
 * @param {string[]} fields Campi che verranno mostrati nella tabella contenente gli elementi del contenuto selezionato.
 * @param {boolean} isSearching True se si sta eseguendo un filtro tramite la barra di ricerca, False altrimenti. 
 * @param {string} sorting Criterio di ordinamento degli elementi.
 * @param {string} pagesFilter Utile solo se il contenuto è "Pagine": le pagine vengono filtrate in base al tipo selezionato (archivio, raccolta, ecc.)
 */
async function showContents(getterFile, addLink, editLink, removeLink, eliminationMessage, btnInsertText, plural, fields, isSearching, sorting, pagesFilter = null) {
    const contents = isSearching === false ? await getContents(getterFile, sorting) : await searchContents(plural, document.getElementById("txtSearch").value);
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
        <a class="btn btn-dark text-decoration-none" href="${addLink}" role="button">${btnInsertText}</a>
        <div class="d-flex align-items-center mt-4">
            <label class="visually-hidden" for="txtSearch">${searchLabel}</label>
            <input type="text" class="form-control me-2" id="txtSearch" placeholder="${searchLabel}" />
            <a class="btn btn-dark w-25 text-decoration-none" href="#" role="button" id="btnSearch">Cerca</a>
        </div>`;
    if (contents.length === 0) {
        contentsHTML += `
        <div class="text-center pt-5">
            <p class="fst-italic">Al momento non sono presenti ${plural}.</p>
        </div>
        `;
    } else {
        if (plural === "pagine") {
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
            <caption>${plural.charAt(0).toUpperCase() + plural.slice(1)} attualmente presenti (se si vuole un ordinamento, cliccare sull'attributo)`;
        contentsHTML += `
            </caption>
            <thead>
                <tr>`;

        fields.forEach(field => {
            let arrowIcon = ``;
            if (field !== "Tipo" && field !== "" && sorting !== Sorting.NO) {
                if (field === "Titolo" && sorting.includes("titolo")) {
                    arrowIcon = `<span class="bi bi-caret-${sorting === Sorting.TITLE ? "down" : "up"}-fill"></span>`;
                } else if (field === "Ultima Modifica" && sorting.includes("ultimaModifica")) {
                    arrowIcon = `<span class="bi bi-caret-${sorting === Sorting.LAST_EDIT ? "down" : "up"}-fill"></span>`;
                } else if (field === "Nome" && sorting.includes("nome")) {
                    arrowIcon = `<span class="bi bi-caret-${sorting === Sorting.NAME ? "down" : "up"}-fill"></span>`;
                }
            }
            contentsHTML += `
                    <th scope="col">${field}${arrowIcon}</th>
            `;
        });
        contentsHTML += `
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
        `;
        contentsHTML += plural === "pagine" ? showPages(contents, editLink, pagesFilter) : showOther(contents, editLink);
        contentsHTML += `
            </tbody>
        </table>
        <div class="modal fade" id="confirmElimination" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="${removeLink}" id="eliminationForm" method="GET">
                        <input type="hidden" name="id" id="contentid" />
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalTitle">Conferma eliminazione</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>${eliminationMessage} Proseguire?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input class="btn btn-danger" type="submit" value="Elimina" />
                        </div>
                    </form>
                </div>
            </div>
        </div>`;
    }

    document.getElementById("contentsShower").innerHTML = contentsHTML;
    if (plural === "pagine") {
        document.querySelectorAll('input[name="pagesFilter"]').forEach (rb => {
            rb.addEventListener('change', () => {
                switch (rb.value) {
                    case "normali":
                        show("Pagine", isSearching, sorting, PagesFilter.NORMAL);
                        break;
                    case "archivi":
                        show("Pagine", isSearching, sorting, PagesFilter.ARCHIVE);
                        break;
                    case "collezioni":
                        show("Pagine", isSearching, sorting, PagesFilter.COLLECTION);
                        break;
                    default:
                        show("Pagine", isSearching, sorting, PagesFilter.NO);
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

    document.querySelectorAll("th").forEach (th => {
        th.addEventListener('click', () => {
            if (plural === "pagine") {
                switch (th.textContent) {
                    case "Titolo":
                        switch (lastSorting) {
                            case Sorting.TITLE:
                                lastSorting = Sorting.TITLE_DESC;
                                break;
                            case Sorting.TITLE_DESC:
                            default:
                                lastSorting = Sorting.TITLE;
                                break;
                        }
                        break;
                    case "Ultima Modifica":
                        switch (lastSorting) {
                            case Sorting.LAST_EDIT:
                                lastSorting = Sorting.LAST_EDIT_DESC;
                                break;
                            case Sorting.LAST_EDIT_DESC:
                            default:
                                lastSorting = Sorting.LAST_EDIT;
                                break;
                        }
                        break;
                    default:
                        break;
                }
                show("Pagine", isSearching, lastSorting, pagesFilter);
            } else if (th.textContent === "Nome") {
                let cont = "";
                switch (plural) {
                    case "menù":
                        cont = "Menù";
                        break;
                    case "tag":
                        cont = "Tag";
                        break;
                    case "articoli d'inventario":
                        cont = "Articoli d'inventario";
                        break;
                    case "strumenti di corredo":
                        cont = "Strumenti di corredo";
                        break;
                }
                switch (lastSorting) {
                    case Sorting.NAME:
                        lastSorting = Sorting.NAME_DESC;
                        break;
                    case Sorting.NAME_DESC:
                    default:
                        lastSorting = Sorting.NAME;
                        break;
                }
                show(cont, isSearching, lastSorting);
            }
        });
    });

    document.querySelectorAll('td').forEach (td => {
        td.addEventListener('click', function(e) {
            if (e.target.matches('a[data-contentid]')) {
                e.preventDefault();
                const contentID = e.target.dataset.contentid;
                document.getElementById("contentid").value = contentID;
            }
        });
    });
}

/**
 * Crea le singole righe della tabella in cui vengono visualizzate le pagine.
 * @param {Promise<{idPage: number, title: string, creationDate: string, updatedDate: string}[]>} pages Array di pagine, ognuna delle quali ha il proprio ID, il titolo e le date di creazione e di ultima modifica.
 * @param {string} editLink Stringa che rappresenta il percorso per raggiungere la pagina nella quale si può modificare un elemento del tipo di contenuto selezionato.
 * @param {string} filter Tipo delle pagine utilizzato come filtro.
 * @returns {string} Righe della tabella.
 */
function showPages(pages, editLink, filter) {
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
                    <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="${editLink}?id=${page['idPage']}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-contentid="${page['idPage']}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
                </td>
            </tr>`;
    });
    return html;
}

/**
 * Crea le singole righe della tabella in cui vengono visualizzati i contenuti diversi dalle pagine.
 * @param {Promise<{ID: number, name: string}[]>} contents Array di contenuti diversi dalle pagine, ognuno dei quali ha il proprio ID e il nome.
 * @param {string} editLink Stringa che rappresenta il percorso per raggiungere la pagina nella quale si può modificare un elemento del tipo di contenuto selezionato.
 * @returns {string} Righe della tabella.
 */
function showOther(contents, editLink) {
    let html = ``;
    contents.forEach(c => {
        html += `
            <tr>
                <td class="align-middle">${c['name']}</td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="${editLink}?id=${c['ID']}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-contentid="${c['ID']}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
                </td>
            </tr>
        `;
    });
    return html;
}

/**
 * Ricava i contenuti di uno specifico tipo filtrati con una stringa di ricerca.
 * @param {string} type Tipo di contenuto.
 * @param {string} researchString Stringa di ricerca.
 * @returns {Promise<any[]>} Array di contenuti.
 */
async function searchContents(type, researchString) {
    const url = CONTENT_GETTERS_SCRIPT_URL + 'getContentResearched.php?cont=' + type + '&string=' + researchString;
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

/**
 * Ricava i contenuti di uno specifico tipi, eventualmente ordinati secondo un determinato criterio.
 * @param {string} utilFunction Nome del file contenente lo script per ricavare i contenuti.
 * @param {string} orderBy Criterio di ordinamento.
 * @returns {Promise<any[]>} Array di contenuti.
 */
async function getContents(utilFunction, orderBy) {
    const url = CONTENT_GETTERS_SCRIPT_URL + utilFunction + '?ordBy=' + orderBy;
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