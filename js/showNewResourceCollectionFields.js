let numResCollections = 0;

document.getElementById("btnAddCollection").addEventListener('click', function(event) {
    showNewResourseCollectionFields();
});

/**
 * Mostra gli input in cui inserire i valori degli attributi di una raccolta di risorse.
 */
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
            <input name="path${numResCollections}" type="file" webkitdirectory class="form-control mt-1" id="path${numResCollections}" />
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