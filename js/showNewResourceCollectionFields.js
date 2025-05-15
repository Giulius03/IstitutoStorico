let numResCollections = 0;

document.getElementById("btnAddCollection").addEventListener('click', function(event) {
    showNewResourseCollectionFields();
});

async function showNewResourseCollectionFields() {
    const itemsContainer = document.getElementById("collectionsForms");
    const currentCollection = `
    <fieldset class="form-floating mb-3 pt-1 border-top">
        <legend>Nuova Raccolta</legend>
        <div class="form-floating my-3">
            <input name="nome${numResCollections}" type="text" class="form-control" id="nome${numResCollections}" placeholder="nome" required />
            <label for="nome${numResCollections}">Nome Raccolta</label>
        </div>
        <div class="my-3">
            <label for="path${numResCollections}">Path Raccolta:</label>
            <input name="path${numResCollections}" type="file" webkitdirectory directory class="form-control mt-1" id="path${numResCollections}" placeholder="path" />
        </div>
    </fieldset>`;
    if (!document.getElementById("noColl")) {
        itemsContainer.insertAdjacentHTML('afterbegin', currentCollection);
    } else {
        itemsContainer.innerHTML = currentCollection;
    }
    numResCollections++;
    document.getElementById("numCollections").value = numResCollections;
}