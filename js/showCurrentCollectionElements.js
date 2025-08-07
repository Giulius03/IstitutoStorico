const bibliographyElemsTableHeadHtml = getTableHeadHtml(['Citazione'], "Elementi di bibliografia");
const chronologyElemsTableHeadHtml = getTableHeadHtml(['Data', 'Località'], "Elementi di cronologia");
const newsPaperLibraryElemsTableHeadHtml = getTableHeadHtml(['Testata Giornalistica', 'Titolo'], "Elementi di emeroteca");
const photoLibraryElemsTableHeadHtml = getTableHeadHtml(['Descrizione'], "Elementi di fototeca");
const netowrkResourcesTableHeadHtml = getTableHeadHtml(['Titolo', 'Fonte'], "Risorse in rete");

function getTableHeadHtml(fields, caption) {
    let html = `
    <table class="table mt-2">
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
    getCollectionElements(document.getElementById("idCollection").value);
    areButtonsNotEnabled = document.getElementById("btnsDisab").value;
});

function showRows(rows, tableHeadHtml, fields, value) {
    let rowsHtml = ``;
    if (rows.length > 0) {
        document.querySelector('input[name="elemType"][value="'+value+'"]').checked = true;
        document.querySelectorAll('input[name="elemType"]').forEach (i => {
            if (i.checked === false) {
                i.disabled = true;
            }
        });
        rows.forEach(row => {
            rowsHtml += `<tr>`;
            fields.forEach(field => {
                rowsHtml += `<td class="align-middle">${row[field]}</td>`;
            });
            rowsHtml += `
                    <td class="align-middle">
                        <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="${areButtonsNotEnabled === "false" ? "modifyCollectionElement.php?id="+row['ID']+"&idPage="+document.getElementById("idPage").value+"&type="+document.querySelector('input[name="elemType"]:checked').value : "#"}" role="button">Modifica</a>
                    </td>
                    <td class="align-middle">
                        <a class="btn btn-danger px-0 py-1 text-decoration-none" href="${areButtonsNotEnabled === "false" ? "../elimination/removeCollectionElement.php?id="+row['ID']+"&idPage="+document.getElementById("idPage").value+"&type="+document.querySelector('input[name="elemType"]:checked').value : "#"}" role="button">Cancella</a>
                    </td>
                </tr>`;   
        }); 
        rowsHtml += `</tbody></table>`;
        document.getElementById("currentElemsTable").innerHTML += tableHeadHtml + rowsHtml;
    }
}

async function getCollectionElements(collectionID) {
    const url = '../../utils/getters/getCollectionElements.php?collectionID=' + collectionID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        let totElems = 0;
        for (let type in json) {
            totElems += json[type].length;
        }
        if (totElems === 0) {
            document.getElementById("collectionElemsForms").innerHTML = `
            <div class="text-center pt-3" id="noElems">
                <p class="fst-italic">Al momento non sono presenti elementi.</p>
            </div>`;
        } else {
            document.getElementById("btnAddCollectionElem").disabled = false;
            showRows(json["bibliografia"], bibliographyElemsTableHeadHtml, ['cit'], "bibliografia");
            showRows(json["cronologia"], chronologyElemsTableHeadHtml, ['data', 'loc'], "cronologia");
            showRows(json["emeroteca"], newsPaperLibraryElemsTableHeadHtml, ['giornale', 'titolo'], "emeroteca");
            showRows(json["fototeca"], photoLibraryElemsTableHeadHtml, ['descrizione'], "fototeca");
            showRows(json["rete"], netowrkResourcesTableHeadHtml, ['titolo', 'fonte'], "rete");
        }
    } catch (error) {
        console.log(error.message);
    }
}