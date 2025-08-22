let pages = [];
let startNumber = 0;
let numOfItems = 0;

document.getElementById("btnAddMenuItem").addEventListener('click', function(event) {
    showNewMenuItemFields();
});

/**
 * Mostra gli input in cui inserire i valori degli attributi di una voce di un menù.
 */
async function showNewMenuItemFields() {
    const itemsContainer = document.getElementById("menuItemsForms");
    const currentMenuItem = `
    <fieldset class="form-floating mb-3 pt-1 border-top">
        <legend>Voce Numero ${numOfItems}</legend>
        <div class="form-floating my-3">
            <input name="NomeVoce${numOfItems}" type="text" class="form-control" id="NomeVoce${numOfItems}" placeholder="NomeVoce" required />
            <label for="NomeVoce${numOfItems}">Nome Voce</label>
        </div>
        <div class="d-flex align-items-center">
            <label class="w-75" for="fatherItem${numOfItems}">Seleziona il padre tramite il suo numero di voce (opzionale)</label>
            <select class="form-select w-25" name="fatherItem${numOfItems}" id="fatherItem${numOfItems}">
                <option value="">Nessuno</option>
            </select>
        </div>
        <div class="form-floating my-3">
            <input name="PosizioneVoce${numOfItems}" type="number" class="form-control" id="PosizioneVoce${numOfItems}" placeholder="PosizioneVoce" required />
            <label for="PosizioneVoce${numOfItems}">Posizione Voce</label>
        </div>
        <div class="border my-3 rounded pt-2 ps-2">
            <label>Seleziona la pagina collegata a questa voce del menù (opzionale)</label>
            <ul class="mt-2 p-0 listPagesContained" style="height: 150px;" id="pagesList${numOfItems}">
            </ul>
        </div>
    </fieldset>`;
    if (!document.getElementById("noItems")) {
        itemsContainer.insertAdjacentHTML('afterbegin', currentMenuItem);
    } else {
        itemsContainer.innerHTML = currentMenuItem;
    }
    pages.forEach(page => {
        document.getElementById("pagesList" + numOfItems).innerHTML += `
                <li class="form-check">
                    <input class="form-check-input" type="radio" name="linkToPage${numOfItems}" value="${page['idPage']}" id="${page['idPage']}${numOfItems}" />
                    <label class="form-check-label" for="${page['idPage']}${numOfItems}">${page['title']}</label>
                </li>`;
    });
    await updateSelects();
    numOfItems++;
}

/**
 * Aggiorna i valori contenuti nella select per selezionare il padre di ogni voce. Viene chiamata ad ogni inserimento.
 */
async function updateSelects() {
    for (let i = startNumber; i <= numOfItems; i++) {
        if (i !== numOfItems) {
            document.getElementById("fatherItem"+i).insertAdjacentHTML('beforeend', `
            <option value="${numOfItems}">${numOfItems}</option>`);
        } else {
            if (document.getElementById("idMenu").value !== "") {
                const items = await getExistingItems(document.getElementById("idMenu").value);
                items.forEach(item => {
                    document.getElementById("fatherItem"+i).insertAdjacentHTML('beforeend', `
                        <option value="${item['ID']}">${item['ID']}</option>`);
                });
            }
            for (let j = startNumber; j < numOfItems; j++) {
                document.getElementById("fatherItem"+i).insertAdjacentHTML('beforeend', `
                    <option value="${j}">${j}</option>`);
            }
        }
    }
}

document.getElementById("newNoPageForm").addEventListener("submit", function(e) {
    document.getElementById("idPartenza").value = startNumber;
    document.getElementById("idFine").value = numOfItems;
});

/**
 * In caso di modifica di un menù, oltre alla possibilità di inserire nuove voci, devono essere visualizzabili anche quelle già esistenti.
 * @param {number} menuID ID del menù che si sta modificando.
 * @returns {Promise<{ ID: number, name: string, position: number, page: number|null, father: number|null }[]>} Array di voci del menù, ognuna delle quali contiene ID, nome, posizione, pagina di riferimento e ID della voce madre.
 */
async function getExistingItems(menuID) {
    let url = CONTENT_GETTERS_SCRIPT_URL + 'getMenuItems.php?id=' + menuID;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        return json;
    } catch (error) {
        console.log(error.message);
    }
}

/**
 * Ricava le pagine presenti nel database, da mostrare per creare eventuali collegamenti con le voci del menù, e l'ID da assegnare a un'eventuale nuova voce da inserire.
 */
async function start() {
    let url = CONTENT_GETTERS_SCRIPT_URL + 'getPages.php?ordBy=title';
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        pages = await response.json();
    } catch (error) {
        console.log(error.message);
    }
    url = CONTENT_GETTERS_SCRIPT_URL + 'getMenuItemsNextID.php';
    try {
        response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        startNumber = json['maxId'];
        numOfItems = startNumber;
    } catch (error) {
        console.log(error.message);
    }
}

start();