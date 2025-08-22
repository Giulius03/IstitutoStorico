const indexItemsTableHeadHtml = getTableHeadHtml(['Titolo', 'Posizione'], "Voci dell'indice");
const notesTableHeadHtml = getTableHeadHtml(['Testo'], "Note");

/**
 * Restituisce la <thead> della tabella contenente le voci di un indice o le note. In base alla scelta, si visualizzano attributi diversi.
 * @param {string[]} fields Array di stringhe contenente i nomi degli attributi da mostrare. 
 * @param {string} caption Caption della tabella (varia per ogni tipo).
 * @returns {string} <thead> della tabella.
 */
function getTableHeadHtml(fields, caption) {
    let html = `
    <table class="table mt-3">
        <caption>${caption} attualmente presenti</caption>
        <thead>
            <tr>`;
    fields.forEach(field => {
        html += `
                <th scope="col">${field}</th>
        `;
    });
    html += `
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>`;
    return html;
}

document.addEventListener('DOMContentLoaded', function() {
    getIndexItems(document.getElementById("idPage").value);
    getNotes(document.getElementById("idPage").value);
});

/**
 * Mostra la tabella contenente note o voci di un indice.
 * @param {Promise<any[]>} rows Array di elementi dello stesso tipo (nota o voce) 
 * @param {string} tableHeadHtml <thead> della tabella coerente coi campi del contenuto che si vuole mostrare.
 * @param {string} fields Campi da mostrare, da utilizzare per ricavare le singole informazioni ottenute dal database.
 * @param {string} divID Attributo id del tag <div> dentro il quale verrà inserito l'HTML generato.
 * @param {string} editFile Nome del file contenente lo script per modificare il contenuto.
 * @param {string} plural Stringa utile per varie label e controlli.
 */
function showTable(rows, tableHeadHtml, fields, divID, editFile, plural) {
    let rowsHtml = ``;
    if (rows.length === 0) {
        rowsHtml += `
        <div class="text-center pt-3" id="no${plural}">
            <p class="fst-italic">Al momento non sono presenti ${plural}.</p>
        </div>`;
        document.getElementById(divID).innerHTML = rowsHtml;
    } else {
        rows.forEach(row => {
        rowsHtml += `<tr>`;
        fields.forEach(field => {
            rowsHtml += `
                <td class="align-middle">${row[field]}</td>`;
            });
            rowsHtml += `
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="${editFile}?id=${row['ID']}&idPage=${document.getElementById("idPage").value}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-content="${plural}" data-contentid="${row['ID']}" data-pageid="${document.getElementById("idPage").value}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
                </td>
            </tr>`;
            }); 
        rowsHtml += `</tbody></table>`;
        document.getElementById(divID).innerHTML = tableHeadHtml + rowsHtml;

        document.querySelectorAll('td').forEach (td => {
            td.addEventListener('click', function(e) {
                if (e.target.matches('a[data-contentid]')) {
                    e.preventDefault();
                    const contentID = e.target.dataset.contentid;
                    const pageID = e.target.dataset.pageid;
                    document.getElementById("contentid").value = contentID;
                    document.getElementById("pageid").value = pageID;
                    if (e.target.dataset.content === "note" || e.target.dataset.content === "voci") {
                        document.getElementById("eliminationForm").action = CONTENT_REMOVERS_SCRIPT_URL + (e.target.dataset.content === "note" ? "deleteNote.php" : "deleteIndexItem.php");
                        document.querySelector("#confirmElimination p").textContent = "L'eliminazione di questa " + (e.target.dataset.content === "note" ? "nota" : "voce dell'indice") + " sarà permanente. Proseguire?";
                    }
                }
            });
        });
    }
}

/**
 * Ricava dal database le voci dell'indice contenuto in una pagina, utilizzando AJAX e script PHP.
 * @param {number} pageID ID della pagina.
 */
async function getIndexItems(pageID) {
    const url = CONTENT_GETTERS_SCRIPT_URL + 'getIndexItems.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showTable(json, indexItemsTableHeadHtml, ['title', 'position'], "indexItemsForms", CONTENTS_EDITING_URL + "modifyIndexItem.php", "voci");
    } catch (error) {
        console.log(error.message);
    }
}

/**
 * Ricava dal database le note contenute in una pagina, utilizzando AJAX e script PHP.
 * @param {number} pageID ID della pagina.
 */
async function getNotes(pageID) {
    const url = CONTENT_GETTERS_SCRIPT_URL + 'getNotes.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showTable(json, notesTableHeadHtml, ['text'], "notesForms", CONTENTS_EDITING_URL + "modifyNote.php", "note");    
    } catch (error) {
        console.log(error.message);
    }
}