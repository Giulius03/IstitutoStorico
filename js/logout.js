document.querySelectorAll('[id^="logout"]').forEach(element => {
    element.addEventListener('click', (event) => {
        logout();
    })
});

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
        console.log(json);
        if (json["successful"] === true) {
            window.location.href = newLocation;
        }
    } catch (error) {
        console.log(error.message);
    }
}

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