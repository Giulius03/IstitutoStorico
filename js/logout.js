document.querySelectorAll('[id^="logout"]').forEach(element => {
    element.addEventListener('click', (event) => {
        logout();
    })
});

/**
 * Esegue il logout di un amministratore tramite AJAX e script PHP.
 * @param {string} url Percorso dello script PHP (dipende da quale pagina si vuole eseguire il logout).
 * @param {string} newLocation Pagina in cui si verrà reindirizzati dopo aver eseguito il logout.
 * @returns 
 */
async function logout(url = 'utils/logout.php', newLocation = 'index.php') {
    try {
        const response = await fetch(url);
        if (response.status === 404) {
            logout('../../utils/logout.php', '../../index.php');
            return;
        }
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        if (json["successful"] === true) {
            window.location.href = newLocation;
        }
    } catch (error) {
        console.log(error.message);
    }
}

/**
 * Funzione utile per sottolineare le voci del menù dell'amministratore anche se non ci si trova nella pagina admin.php
 */
function fixUnderlinedLinks() {
    const currentWindow = window.location.href;
    if (!currentWindow.includes("admin")) {
        const names = [ "Page", "Menu", "Tool", "InvItem", "Tag" ];
        const dataContents = [ "Pagine", "Menù", "Strumenti di corredo", "Articoli d'inventario", "Tag" ];
        for (let i = 0; i < names.length; i++) {
            if (currentWindow.includes(names[i])) {
                document.querySelector('#pcNavbar .admin-list a[data-content="' + dataContents[i] + '"]').style.textDecoration = "underline";
            }            
        }
    }
}

fixUnderlinedLinks();