const inventoryItemsList = document.getElementById("invItemList");

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