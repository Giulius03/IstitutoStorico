const noTypeRadio = document.getElementById("no");
const archivePageRadio = document.getElementById("archivio");
const resourceCollectorRadio = document.getElementById("raccolta");
const archivePageInfo = document.getElementById("archivePageInfo");
const resourceCollectorInfo = document.getElementById("resourceCollectorInfo");

noTypeRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "d-none";
    resourceCollectorInfo.className = "border-top mb-4 d-none";
});

archivePageRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "";
    resourceCollectorInfo.className = "border-top mb-4 d-none";
});

resourceCollectorRadio.addEventListener('change', function(event) {
    archivePageInfo.className = "d-none";
    resourceCollectorInfo.className = "border-top mb-4";
});