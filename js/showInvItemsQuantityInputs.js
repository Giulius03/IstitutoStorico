const inventoryItemsList = document.getElementById("invItemList");

/**
 * Questo listener fa in modo che, durante la creazione o modifica di una pagina d'archivio, quando si seleziona o deseleziona un articolo d'inventario, compaia una textbox in cui inserire la quantità presente nell'archivio.
 */
inventoryItemsList.addEventListener('change', function(event) {
    if (event.target.type === "checkbox") {
        const number = (event.target.name).split('articolo')[1];
        const clickedCheckBox = document.getElementById(event.target.name);
        const currentInput = document.getElementById("quantita"+number);
        if (clickedCheckBox.checked === true) {
            currentInput.className = "form-control py-1 ps-2 pe-0";
            currentInput.required = true;
        } else {
            currentInput.className = "form-control py-1 ps-2 pe-0 d-none";
            currentInput.value = "";
            currentInput.required = false;
        }
    }
});