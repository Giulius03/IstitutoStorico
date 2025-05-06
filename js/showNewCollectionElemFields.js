let numOfElems = 0;

document.getElementById("btnAddCollectionElem").addEventListener('click', function(event) {
    showNewCollectionElemFields();
});

async function showNewCollectionElemFields() {
    const itemsContainer = document.getElementById("collectionElemForms");
    itemsContainer.insertAdjacentHTML('afterbegin', `
    <fieldset class="mb-3 pt-1 border-top">
        <legend>Nuovo Elemento di Raccolta</legend>
        <label>Seleziona il tipo di elemento:</label>
        <div class="mt-1" id="radioContainer${numOfElems}">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="elemType${numOfElems}" value="bibliografia" id="bibliografia${numOfElems}" />
                <label class="form-check-label" for="bibliografia${numOfElems}">Elemento di bibliografia</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="elemType${numOfElems}" value="cronologia" id="cronologia${numOfElems}" />
                <label class="form-check-label" for="cronologia${numOfElems}">Elemento di cronologia</label>
            </div>
            <div class="form-check mt-1">
                <input class="form-check-input" type="radio" name="elemType${numOfElems}" value="emeroteca" id="emeroteca${numOfElems}" />
                <label class="form-check-label" for="emeroteca${numOfElems}">Elemento di emeroteca</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="elemType${numOfElems}" value="fototeca" id="fototeca${numOfElems}" />
                <label class="form-check-label" for="fototeca${numOfElems}">Elemento di fototeca</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="elemType${numOfElems}" value="rete" id="rete${numOfElems}" />
                <label class="form-check-label" for="rete${numOfElems}">Risorsa in rete</label>
            </div>
        </div>
        <div id="specificFieldsForm${numOfElems}">

        </div>
    </fieldset>`);
    document.querySelector('#radioContainer'+numOfElems).addEventListener('change', function(event) {
        console.log(numOfElems);
        switch (event.target.value) {
            case "bibliografia":
                showBibliographyElemFields(numOfElems-1);
                break;
            case "cronologia":
                showCronologyElemFields(numOfElems-1);
                break;
            default:
                break;
        }
    });
    numOfElems++;
}

function showBibliographyElemFields(index) {
    document.getElementById("specificFieldsForm"+index).innerHTML = `
    <div class="form-floating my-3">
        <input name="citazione${index}" type="text" class="form-control" id="citazione${index}" placeholder="citazione" required />
        <label for="citazione${index}">Citazione</label>
    </div>
    <div class="form-floating my-3">
        <input name="HREF${index}" type="text" class="form-control" id="HREF${index}" placeholder="HREF" />
        <label for="HREF${index}">HREF</label>
    </div>
    <div class="form-floating my-3">
        <input name="DOI${index}" type="text" class="form-control" id="DOI${index}" placeholder="DOI" />
        <label for="DOI${index}">DOI</label>
    </div>`;
}

function showCronologyElemFields(index) {
    document.getElementById("specificFieldsForm"+index).innerHTML = `
    <div class="form-floating my-3">
        <input name="data${index}" type="date" class="form-control" id="data${index}" placeholder="data" required />
        <label for="data${index}">Data</label>
    </div>
    <div class="form-floating my-3">
        <input name="localita${index}" type="text" class="form-control" id="localita${index}" placeholder="localita" />
        <label for="localita${index}">Località</label>
    </div>
    <div class="form-floating my-3">
        <input name="descrizione${index}" type="text" class="form-control" id="descrizione${index}" placeholder="descrizione" />
        <label for="descrizione${index}">Descrizione</label>
    </div>`;
}