let numOfItems = 0;
let pages = [];

document.getElementById("btnAddIndexItem").addEventListener('click', function(event) {
    showNewIndexItemFields();
});

async function showNewIndexItemFields() {
    const itemsContainer = document.getElementById("indexItemsForms");
    itemsContainer.insertAdjacentHTML('afterbegin', `
    <fieldset class="form-floating mb-3 pt-1 border-top">
        <legend>Nuova Voce</legend>
        <div class="form-floating my-3">
            <input name="TitoloVoce${numOfItems}" type="text" class="form-control" id="TitoloVoce${numOfItems}" placeholder="TitoloVoce" required />
            <label for="TitoloVoce${numOfItems}">Titolo Voce</label>
        </div>
        <div class="form-floating my-3">
            <input name="PosizioneVoce${numOfItems}" type="number" class="form-control" id="PosizioneVoce${numOfItems}" placeholder="PosizioneVoce" required />
            <label for="PosizioneVoce${numOfItems}">Posizione Voce</label>
        </div>
        <div class="form-floating my-3">
            <input name="AncoraDest${numOfItems}" type="text" class="form-control" id="AncoraDest${numOfItems}" placeholder="AncoraDest" value="#" required />
            <label for="AncoraDest${numOfItems}">Ancora di Destinazione (DEVE iniziare con #!)</label>
        </div>
        <div class="border my-3 rounded pt-2 ps-2">
            <label>Seleziona la pagina collegata a questa voce dell'indice (opzionale)</label>
            <ul class="mt-2 p-0 listPagesContained" style="height: 150px;" id="pagesList${numOfItems}">
            </ul>
        </div>
    </fieldset>`);
    pages.forEach(page => {
        document.getElementById("pagesList" + numOfItems).innerHTML += `
                <li class="form-check">
                    <input class="form-check-input" type="radio" name="linkToPage${numOfItems}" value="${page['idPage']}" id="${page['idPage']}${numOfItems}" />
                    <label class="form-check-label" for="${page['idPage']}${numOfItems}">${page['title']}</label>
                </li>`;
    });
    numOfItems++;
    document.getElementById("numVoci").value = numOfItems;
}

async function start() {
    const url = '../../utils/getters/getPages.php?ordBy=title';
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        pages = await response.json();
    } catch (error) {
        console.log(error.message);
    }
}

start();