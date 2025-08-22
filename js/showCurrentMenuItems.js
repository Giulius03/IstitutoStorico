const tableHeadHtml = `
<table class="table mt-3">
    <caption>Voci del menù attualmente presenti</caption>
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Nome</th>
            <th scope="col">Posizione</th>
            <th scope="col">Link alla Pagina</th>
            <th scope="col">Voce Padre</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>`;

document.addEventListener('DOMContentLoaded', function() {
    getMenuItems(document.getElementById("idMenu").value);
});

/**
 * Mostra le righe della tabella contenente le voci di un menù.
 * @param {Promise<{ ID: number, name: string, position: number, page: number|null, father: number|null }[]>} items Array di voci del menù, ognuna delle quali contiene ID, nome, posizione all'interno del menù, pagina a cui fa riferimento e ID della voce madre.
 */
function showCurrentItems(items) {
    let itemsHtml = ``;
    if (items.length === 0) {
        itemsHtml += `
        <div class="text-center pt-3" id="noItems">
            <p class="fst-italic">Al momento non sono presenti voci.</p>
        </div>`;
        document.getElementById("menuItemsForms").innerHTML = itemsHtml;
    } else {
        items.forEach(item => {
            itemsHtml += `
            <tr>
                <td class="align-middle">${item['ID']}</td>
                <td class="align-middle">${item['name']}</td>
                <td class="align-middle">${item['position']}</td>
                <td class="align-middle">${item['page'] !== null ? item['page'] : ""}</td>
                <td class="align-middle">${item['father'] !== null ? item['father'] : ""}</td>
                <td class="align-middle">
                    <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="${CONTENTS_EDITING_URL}modifyMenuItem.php?id=${item['ID']}&idMenu=${document.getElementById("idMenu").value}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-contentid="${item['ID']}" data-menuid="${document.getElementById("idMenu").value}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
                </td>
            </tr>`;
        }); 
        itemsHtml += `</tbody></table>`;
        document.getElementById("menuItemsForms").innerHTML = tableHeadHtml + itemsHtml;

        document.querySelectorAll('td').forEach (td => {
            td.addEventListener('click', function(e) {
                if (e.target.matches('a[data-contentid]')) {
                    e.preventDefault();
                    const contentID = e.target.dataset.contentid;
                    const menuID = e.target.dataset.menuid;
                    document.getElementById("contentid").value = contentID;
                    document.getElementById("menuid").value = menuID;
                }
            });
        });
    }
}

/**
 * Ricava le voci di un menù dal database, utilizzando AJAX e script PHP.
 * @param {number} menuID ID del menù di cui su vogliono ottenere le voci.
 */
async function getMenuItems(menuID) {
    let url = CONTENT_GETTERS_SCRIPT_URL + 'getMenuItems.php?id=' + menuID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showCurrentItems(json);
    } catch (error) {
        console.log(error.message);
    }
}