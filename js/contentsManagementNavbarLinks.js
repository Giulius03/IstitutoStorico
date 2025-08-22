function getContParamName(cont) {
    switch(cont) {
        case "Menù":
            return "menù";
        case "Tag":
            return "tag";
        case "Articoli d'inventario":
            return "articolo d'inventario";
        case "Strumenti di corredo":
            return "strumento di corredo";
        default:
            return "Pagine";
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.admin-list').forEach(l => {
        l.addEventListener('click', function(e) {
            if (e.target.matches('a[data-content]')) {
                e.preventDefault();
                const content = e.target.dataset.content;
                const modal = new bootstrap.Modal(document.getElementById("dontSaveModal"));
                modal.show();
                document.getElementById("btnDontSave").addEventListener('click', () => {
                    window.location.href = ADMIN_PAGE_URL + "?cont=" + getContParamName(content);
                });
            }
        })
    });
});