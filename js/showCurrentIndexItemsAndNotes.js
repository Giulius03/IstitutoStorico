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
    areButtonsNotEnabled = document.getElementById("btnsDisab").value;
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
                    <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? editFile+"?id="+row['ID']+"&idPage="+document.getElementById("idPage").value : "#"}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/"+removeFile+"?id="+row['ID']+"&idPage="+document.getElementById("idPage").value : "#"}" role="button">Cancella</a>
                </td>
            </tr>`;
            }); 
        rowsHtml += `</tbody></table>`;
        document.getElementById(divID).innerHTML = tableHeadHtml + rowsHtml;
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
        showTable(json, indexItemsTableHeadHtml, ['title', 'position'], "indexItemsForms", "modifyIndexItem.php", "removeIndexItem.php", "voci");
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
        showTable(json, notesTableHeadHtml, ['text'], "notesForms", "modifyNote.php", "removeNote.php", "note");    
    } catch (error) {
        console.log(error.message);
    }
}