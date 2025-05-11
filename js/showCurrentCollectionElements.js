const bibliographyElemsTableHeadHtml = getTableHeadHtml(['Citazione'], "Elementi di bibliografia");
const chronologyElemsTableHeadHtml = getTableHeadHtml(['Data', 'Località'], "Elementi di cronologia");
const newsPaperLibraryElemsTableHeadHtml = getTableHeadHtml(['Testata Giornalistica', 'Titolo'], "Elementi di emeroteca");
const photoLibraryElemsTableHeadHtml = getTableHeadHtml(['Descrizione'], "Elementi di fototeca");
const netowrkResourcesTableHeadHtml = getTableHeadHtml(['Titolo', 'Fonte'], "Risorse in rete");

function getTableHeadHtml(fields, caption) {
    let html = `
    <table class="table mt-4">
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
    getCollectionElements(document.getElementById("idPage").value);
    areButtonsNotEnabled = document.getElementById("btnsDisab").value;
});

function showRows(rows, tableHeadHtml, fields) {
    let rowsHtml = `<tr>`;
    rows.forEach(row => {
        fields.forEach(field => {
            rowsHtml += `<td class="align-middle">${row[field]}</td>`;
        });
        rowsHtml += `
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? "modifyIndexItem.php?id="+row['ID']+"&idPage="+document.getElementById("idPage").value : "#"}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/removeIndexItem.php?id="+row['ID']+"&idPage="+document.getElementById("idPage").value : "#"}"role="button">Cancella</a>
                </td>
            </tr>`;   
    }); 
    rowsHtml += `</tbody></table>`;
    document.getElementById("collectionElemForms").innerHTML += tableHeadHtml + rowsHtml;
}

async function getCollectionElements(pageID) {
    const url = '../../utils/getters/getCollectionElements.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        showRows(json["bibliografia"], bibliographyElemsTableHeadHtml, ['cit']);
        showRows(json["cronologia"], chronologyElemsTableHeadHtml, ['data', 'loc']);
        showRows(json["emeroteca"], newsPaperLibraryElemsTableHeadHtml, ['giornale', 'titolo']);
        showRows(json["fototeca"], photoLibraryElemsTableHeadHtml, ['descrizione']);
        showRows(json["rete"], netowrkResourcesTableHeadHtml, ['titolo', 'fonte']);
    } catch (error) {
        console.log(error.message);
    }
}