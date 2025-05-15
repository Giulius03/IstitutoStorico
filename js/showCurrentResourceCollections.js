const resCollectionsTableHeadHtml = `
<table class="table mt-3">
    <caption>Raccolte di risorse attualmente presenti</caption>
    <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>`;


document.addEventListener('DOMContentLoaded', function() {
    getResourceCollections(document.getElementById("idPage").value);
});

function showResourceCollections(collections) {
    let collHtml = ``;
    collections.forEach(collection => {
        collHtml += `
        <tr>
            <td class="align-middle">${collection['nome']}</td>
            <td class="align-middle">
                <a class="btn btn-secondary px-0 py-1" href="${areButtonsNotEnabled === "false" ? "modifyResourceCollection.php?id="+collection['ID']+"&idPage="+document.getElementById("idPage").value : "#"}" role="button">Modifica</a>
            </td>
            <td class="align-middle">
                <a class="btn btn-danger px-0 py-1" href="${areButtonsNotEnabled === "false" ? "../elimination/removeResourceCollection.php?id="+collection['ID']+"&idPage="+document.getElementById("idPage").value : "#"}" role="button">Cancella</a>
            </td>
        </tr>`;
    });
    collHtml += `</tbody></table>`;
    document.getElementById("collectionsForms").innerHTML = resCollectionsTableHeadHtml + collHtml;
}

async function getResourceCollections(pageID) {
    const url = '../../utils/getters/getResourceCollections.php?id=' + pageID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        showResourceCollections(json);
    } catch (error) {
        console.log(error.message);
    }
}