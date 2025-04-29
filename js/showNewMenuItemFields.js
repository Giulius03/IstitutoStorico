let numOfItems = 0;
let pages = [];

document.getElementById("btnAddMenuItem").addEventListener('click', function(event) {
    showNewMenuItemFields();
});

function showNewMenuItemFields() {
    const itemsContainer = document.getElementById("menuItemsForms");
    numOfItems++;
    itemsContainer.insertAdjacentHTML('beforeend', `
    <fieldset class="form-floating mb-3 pt-1 border-top">
        <legend>Voce Numero ${numOfItems}</legend>
        <div class="form-floating my-3">
            <input name="NomeVoce${numOfItems}" type="text" class="form-control" id="NomeVoce${numOfItems}" placeholder="NomeVoce" required />
            <label for="NomeVoce${numOfItems}">Nome Voce</label>
        </div>
        <div class="d-flex align-items-center">
            <label class="w-75" for="fatherItem${numOfItems}">Seleziona il padre tramite il suo numero di voce (opzionale)</label>
            <select class="form-select w-25" name="fatherItem${numOfItems}" id="fatherItem${numOfItems}">
                <option value="no" selected>Nessuno</option>
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
                    <input class="form-check-input" type="radio" name="linkToPage${numOfItems}" value="${page['idPage']}${numOfItems}" id="${page['idPage']}${numOfItems}" />
                    <label class="form-check-label" for="${page['idPage']}${numOfItems}">${page['title']}</label>
                </li>`;
    });
    updateSelects();
}

function updateSelects() {
    for (let i = 0; i < numOfItems; i++) {
        let currentSelect = i+1;
        if (currentSelect !== numOfItems) {
            document.getElementById("fatherItem" + currentSelect).add(new Option(numOfItems, numOfItems));
        } else {
            for (let j = 0; j < numOfItems; j++) {
                let currentVal = j+1;
                if (currentVal !== numOfItems) {
                    document.getElementById("fatherItem" + currentSelect).add(new Option(currentVal, currentVal));
                }
            }
        }
    }
}

document.getElementById("newMenuForm").addEventListener("submit", function(e) {
    document.getElementById("numeroVoci").value = numOfItems;
});

async function getPages() {
    const url = '../utils/getters/getPages.php?ordBy=title';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        pages = await response.json();
    } catch (error) {
        console.log(error.message);
    }
}

getPages();