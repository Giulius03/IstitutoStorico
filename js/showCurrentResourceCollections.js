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
    if (collections.length === 0) {
        collHtml += `
        <div class="text-center pt-3" id="noColl">
            <p class="fst-italic">Al momento non sono presenti raccolte.</p>
        </div>`;
        document.getElementById("collectionsForms").innerHTML = collHtml;
    } else {
        collections.forEach(collection => {
        collHtml += `
        <tr>
            <td class="align-middle">${collection['nome']}</td>
            <td class="align-middle">
                <a class="btn btn-secondary px-0 py-1 text-decoration-none" href="modifyResourceCollection.php?id=${collection['ID']}&idPage=${document.getElementById("idPage").value}" role="button">Modifica</a>
            </td>
            <td class="align-middle">
                <a class="btn btn-danger px-0 py-1 text-decoration-none" href="#" role="button" data-content="collezione" data-contentid="${collection['ID']}" data-pageid="${document.getElementById("idPage").value}" data-bs-toggle="modal" data-bs-target="#confirmElimination">Cancella</a>
            </td>
        </tr>`;
        });
        collHtml += `</tbody></table>`;
        document.getElementById("collectionsForms").innerHTML = resCollectionsTableHeadHtml + collHtml;

        document.querySelectorAll('td').forEach (td => {
            td.addEventListener('click', function(e) {
                if (e.target.matches('a[data-contentid]')) {
                    e.preventDefault();
                    const contentID = e.target.dataset.contentid;
                    const pageID = e.target.dataset.pageid;
                    document.getElementById("contentid").value = contentID;
                    document.getElementById("pageid").value = pageID;
                    if (e.target.dataset.content === "collezione") {
                        document.getElementById("eliminationForm").action = "../../utils/contentRemovers/deleteResourceCollection.php";
                        document.querySelector("#confirmElimination p").textContent = "L'eliminazione di questa raccolta sarà permanente e comporterà anche la cancellazione di tutti gli elementi contenuti al suo interno. Proseguire?";
                    }
                }
            });
        });
    }
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