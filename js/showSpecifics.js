const noTypeRadio = document.getElementById("no");
const archivePageRadio = document.getElementById("archivio");
const resourceCollectorRadio = document.getElementById("raccolta");
const archivePageInfo = document.getElementById("archivePageInfo");
const resourceCollectorInfo = document.getElementById("resourceCollectorInfo");
const startChronoDateInput = document.getElementById("dataInizio");
const endChronoDateInput = document.getElementById("dataFine");
const resourceCollectionInput = document.getElementById("nomeRaccolta");

noTypeRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "d-none";
    resourceCollectorInfo.className = "border-top mb-4 d-none";
    startChronoDateInput.required = false;
    endChronoDateInput.required = false;
    resourceCollectionInput.required = false;
});

archivePageRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "";
    resourceCollectorInfo.className = "border-top mb-4 d-none";
    startChronoDateInput.required = true;
    endChronoDateInput.required = true;
    resourceCollectionInput.required = false;
});

resourceCollectorRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "d-none";
    resourceCollectorInfo.className = "border-top mb-4";
    startChronoDateInput.required = false;
    endChronoDateInput.required = false;
    resourceCollectionInput.required = true;
});