let pages = [];
let startNumber = 0;
let numOfItems = 0;
let oldItemsTaken = false;

document.getElementById("btnAddMenuItem").addEventListener('click', function(event) {
    showNewMenuItemFields();
});

async function showNewMenuItemFields() {
    const itemsContainer = document.getElementById("menuItemsForms");
    itemsContainer.insertAdjacentHTML('afterbegin', `
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
    </fieldset>`);
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
                oldItemsTaken = true;
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

async function getExistingItems(menuID) {
    let url = '../../utils/getters/getMenuItems.php?id=' + menuID;
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

async function start() {
    let url = '../../utils/getters/getPages.php?ordBy=title';
    try {
        let response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        pages = await response.json();
    } catch (error) {
        console.log(error.message);
    }
    url = '../../utils/getters/getMenuItemsNextID.php';
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