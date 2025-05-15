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
let areButtonsNotEnabled = false;

document.addEventListener('DOMContentLoaded', function() {
    getMenuItems(document.getElementById("idMenu").value);
    areButtonsNotEnabled = document.getElementById("btnsDisab").value;
});

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
                    <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? "modifyMenuItem.php?id="+item['ID']+"&idMenu="+document.getElementById("idMenu").value : "#"}" role="button">Modifica</a>
                </td>
                <td class="align-middle">
                    <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/removeMenuItem.php?id="+item['ID']+"&idMenu="+document.getElementById("idMenu").value : "#"}" role="button">Cancella</a>
                </td>
            </tr>`;
        }); 
        itemsHtml += `</tbody></table>`;
        document.getElementById("menuItemsForms").innerHTML = tableHeadHtml + itemsHtml;
    }
}

async function getMenuItems(menuID) {
    let url = '../../utils/getters/getMenuItems.php?id=' + menuID;
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