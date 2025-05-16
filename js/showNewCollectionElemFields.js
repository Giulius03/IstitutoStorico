let numOfElems = 0;
let lastTypeSelected = "";

document.querySelector("#elemTypeList").addEventListener('change', function(event) {
    if (document.getElementById("btnAddCollectionElem").disabled === true) {
        lastTypeSelected = event.target.value;
        document.getElementById("btnAddCollectionElem").disabled = false;
    }
});

document.getElementById("btnAddCollectionElem").addEventListener('click', function() {
    showNewCollectionElemFields();
});

function showNewCollectionElemFields() {
    const elemsContainer = document.getElementById("collectionElemsForms");
    const currentType = document.querySelector('input[name="elemType"]:checked').value;
    if (currentType !== lastTypeSelected) {
        elemsContainer.innerHTML = ``;
        lastTypeSelected = currentType;
        numOfElems = 0;
    }
    elemsContainer.insertAdjacentHTML('afterbegin', `
    <div class="mb-3 pt-1 border-top">
        <legend></legend>
        <div id="specificFieldsForm${numOfElems}">

        </div>
    </div>`);
    const legend = document.querySelector("legend");
    switch (currentType) {
        case "bibliografia":
            showBibliographyElemFields(numOfElems);
            legend.textContent = "Nuovo Elemento di Bibliografia";
            break;
        case "cronologia":
            showCronologyElemFields(numOfElems);
            legend.textContent = "Nuovo Elemento di Cronologia";
            break;
        case "emeroteca":
            showNewsPaperLibraryElemFields(numOfElems);
            legend.textContent = "Nuovo Elemento di Emeroteca";
            break;
        case "fototeca":
            showNewPhotoLibraryElemFields(numOfElems);
            legend.textContent = "Nuovo Elemento di Fototeca";
            break;
        case "rete":
            showNewNetworkResourceFields(numOfElems);
            legend.textContent = "Nuova Risorsa in Rete";
            break;
    }
    numOfElems++;
    document.getElementById("numElems").value = numOfElems;
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
        <input name="localita${index}" type="text" class="form-control" id="localita${index}" placeholder="localita" required />
        <label for="localita${index}">Località</label>
    </div>
    <div class="form-floating my-3">
        <input name="descrizione${index}" type="text" class="form-control" id="descrizione${index}" placeholder="descrizione" required />
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