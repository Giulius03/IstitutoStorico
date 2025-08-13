const indexItemsTableHeadHtml = getTableHeadHtml(['Titolo', 'Posizione'], "Voci dell'indice");
const notesTableHeadHtml = getTableHeadHtml(['Testo'], "Note");
let areButtonsNotEnabled = false;

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

function showTable(rows, tableHeadHtml, fields, divID, editFile, removeFile, plural) {
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
                        document.getElementById("eliminationForm").action = "../../utils/contentRemovers/" + (e.target.dataset.content === "note" ? "deleteNote.php" : "deleteIndexItem.php");
                        document.querySelector("#confirmElimination p").textContent = "L'eliminazione di questa " + (e.target.dataset.content === "note" ? "nota" : "voce dell'indice") + " sarà permanente. Proseguire?";
                    }
                }
            });
        });
    }
}

async function getIndexItems(pageID) {
    const url = '../../utils/getters/getIndexItems.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showTable(json, indexItemsTableHeadHtml, ['title', 'position'], "indexItemsForms", "modifyIndexItem.php", "deleteIndexItem.php", "voci");
    } catch (error) {
        console.log(error.message);
    }
}

async function getNotes(pageID) {
    const url = '../../utils/getters/getNotes.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showTable(json, notesTableHeadHtml, ['text'], "notesForms", "modifyNote.php", "deleteNote.php", "note");    
    } catch (error) {
        console.log(error.message);
    }
}