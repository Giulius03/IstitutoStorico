const indexItemsTableHeadHtml = `
<table class="table mt-3">
    <caption>Voci dell'indice della pagina attualmente presenti</caption>
    <thead>
        <tr>
            <th scope="col">Titolo</th>
            <th scope="col">Posizione</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>`;

const notesTableHeadHtml = `
<table class="table mt-3">
    <caption>Note della pagina attualmente presenti</caption>
    <thead>
        <tr>
            <th scope="col">Testo</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>`;

let areButtonsNotEnabled = true; //da mettere a false quando aggiungo i file per gli update 

document.addEventListener('DOMContentLoaded', function() {
    getIndexItems(document.getElementById("idPage").value);
    getNotes(document.getElementById("idPage").value);
    // areButtonsNotEnabled = document.getElementById("btnsDisab").value;
});

function showCurrentIndexItems(items) {
    let itemsHtml = ``;
    items.forEach(item => {
        itemsHtml += `
        <tr>
            <td class="align-middle">${item['title']}</td>
            <td class="align-middle">${item['position']}</td>
            <td class="align-middle">
                <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? "modifyIndexItem.php?id="+item['ID']+"&idPage="+document.getElementById("idMenu").value : "#"}" role="button">Modifica</a>
            </td>
            <td class="align-middle">
                <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/removeIndexItem.php?id="+item['ID']+"&idPage="+document.getElementById("idMenu").value : "#"}"role="button">Cancella</a>
            </td>
        </tr>`;
    }); 
    itemsHtml += `</tbody></table>`;
    document.getElementById("indexItemsForms").innerHTML = indexItemsTableHeadHtml + itemsHtml;
}

function showCurrentNotes(notes) {
    let notesHtml = ``;
    notes.forEach(note => {
        notesHtml += `
        <tr>
            <td class="align-middle">${note['text']}</td>
            <td class="align-middle">
                <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? "modifyNote.php?id="+note['ID']+"&idPage="+document.getElementById("idMenu").value : "#"}" role="button">Modifica</a>
            </td>
            <td class="align-middle">
                <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/removeNote.php?id="+note['ID']+"&idPage="+document.getElementById("idMenu").value : "#"}" role="button">Cancella</a>
            </td>
        </tr>`;
    }); 
    
    notesHtml += `</tbody></table>`;
    document.getElementById("notesForms").innerHTML = notesTableHeadHtml + notesHtml;
}

async function getIndexItems(pageID) {
    const url = '../../utils/getters/getIndexItems.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showCurrentIndexItems(json);
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
        showCurrentNotes(json);
    } catch (error) {
        console.log(error.message);
    }
}