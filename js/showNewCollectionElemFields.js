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
        const number = (event.target.name).split('elemType')[1];
        switch (event.target.value) {
            case "bibliografia":
                showBibliographyElemFields(number);
                break;
            case "cronologia":
                showCronologyElemFields(number);
                break;
            case "emeroteca":
                showNewsPaperLibraryElemFields(number);
                break;
            case "fototeca":
                showNewPhotoLibraryElemFields(number);
                break;
            case "rete":
                showNewNetworkResourceFields(number);
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

function showNewsPaperLibraryElemFields(index) {
    document.getElementById("specificFieldsForm"+index).innerHTML = `
    <div class="form-floating my-3">
        <input name="giornale${index}" type="text" class="form-control" id="giornale${index}" placeholder="giornale" required />
        <label for="giornale${index}">Testata Giornalistica</label>
    </div>
    <div class="form-floating my-3">
        <input name="titolo${index}" type="text" class="form-control" id="titolo${index}" placeholder="titolo" required />
        <label for="titolo${index}">Titolo Articolo</label>
    </div>
    <div class="form-floating my-3">
        <input name="dataPubblicazione${index}" type="date" class="form-control" id="dataPubblicazione${index}" placeholder="dataPubblicazione" required />
        <label for="dataPubblicazione${index}">Data di Pubblicazione</label>
    </div>
    <div class="form-floating my-3">
        <input name="HREF${index}" type="text" class="form-control" id="HREF${index}" placeholder="HREF" />
        <label for="HREF${index}">HREF</label>
    </div>`;
}

function showNewPhotoLibraryElemFields(index) {
    document.getElementById("specificFieldsForm"+index).innerHTML = `
    <div class="form-floating my-3">
        <input name="descrizione${index}" type="text" class="form-control" id="descrizione${index}" placeholder="descrizione" />
        <label for="descrizione${index}">Descrizione</label>
    </div>`;
}

function showNewNetworkResourceFields(index) {
    document.getElementById("specificFieldsForm"+index).innerHTML = `
    <div class="form-floating my-3">
        <input name="tipologia${index}" type="text" class="form-control" id="tipologia${index}" placeholder="tipologia" />
        <label for="tipologia${index}">Tipologia di Risorsa</label>
    </div>
    <div class="form-floating my-3">
        <input name="titolo${index}" type="text" class="form-control" id="titolo${index}" placeholder="titolo" required />
        <label for="titolo${index}">Titolo</label>
    </div>
    <div class="form-floating my-3">
        <input name="fonte${index}" type="text" class="form-control" id="fonte${index}" placeholder="fonte" />
        <label for="fonte${index}">Fonte</label>
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