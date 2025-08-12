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
                        <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="modifyCollectionElement.php?id=${row['ID']}&idPage=${document.getElementById("idPage").value}&type=${document.querySelector('input[name="elemType"]:checked').value}" role="button">Modifica</a>
                    </td>
                    <td class="align-middle">
                        <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-contentid="${row['ID']}" data-pageid="${document.getElementById("idPage").value}" data-collectionid="${document.getElementById("idCollection").value}" data-type="${document.querySelector('input[name="elemType"]:checked').value}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
                    </td>
                </tr>`;   
        }); 
        rowsHtml += `
            </tbody>
        </table>
        <div class="modal fade" id="confirmElimination" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="modal-dialog">
                <form action="deleteCollectionElement.php" id="eliminationForm" method="GET">
                    <input type="hidden" name="id" id="contentid" />
                    <input type="hidden" name="idPage" id="pageid" />
                    <input type="hidden" name="idCollection" id="collid" />
                    <input type="hidden" name="type" id="type" />
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalTitle">Conferma eliminazione</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>L'eliminazione di questo elemento sarà permanente. Proseguire?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                            <input class="btn btn-danger" type="submit" value="Elimina" />
                        </div>
                    </div>
                </form>
            </div>
        </div>`;
        document.getElementById("currentElemsTable").innerHTML += tableHeadHtml + rowsHtml;

        document.querySelectorAll('td').forEach (td => {
            td.addEventListener('click', function(e) {
                if (e.target.matches('a[data-contentid]')) {
                    e.preventDefault();
                    const contentID = e.target.dataset.contentid;
                    const pageID = e.target.dataset.pageid;
                    const collectionID = e.target.dataset.collectionid;
                    const type = e.target.dataset.type;
                    document.getElementById("contentid").value = contentID;
                    document.getElementById("pageid").value = pageID;
                    document.getElementById("collid").value = collectionID;
                    document.getElementById("type").value = type;
                    const modal = new bootstrap.Modal(document.getElementById("confirmElimination"));
                    modal.show();
                }
            });
        });
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