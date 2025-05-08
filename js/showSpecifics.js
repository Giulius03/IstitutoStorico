const noTypeRadio = document.getElementById("no");
const archivePageRadio = document.getElementById("archivio");
const resourceCollectorRadio = document.getElementById("raccolta");
const archivePageInfo = document.getElementById("archivePageInfo");
const resourceCollectorInfo = document.getElementById("resourceCollectorInfo");
const startChronoDateInput = document.getElementById("dataInizio");
const endChronoDateInput = document.getElementById("dataFine");
const resourceCollectionInput = document.getElementById("nomeRaccolta");
const inventoryItemsList = document.getElementById("invItemList");

function setProp(type) {
    archivePageInfo.className = (type === "no" || type === "r") ? "d-none" : "";
    resourceCollectorInfo.className = (type === "no" || type === "a") ? "border-top mb-4 d-none" : "border-top mb-4";
    startChronoDateInput.required = (type === "no" || type === "r") ? false : true;
    endChronoDateInput.required = (type === "no" || type === "r") ? false : true;
    resourceCollectionInput.required = (type === "no" || type === "a") ? false : true;
}

noTypeRadio.addEventListener('change', function(event) {
    setProp("no");
});

archivePageRadio.addEventListener('change', function(event) {
    setProp("a");
});

resourceCollectorRadio.addEventListener('change', function(event) {
    setProp("r");
});

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