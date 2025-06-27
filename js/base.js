async function getMenuItemsByFather(father) {
    const url = 'utils/getters/getPrimaryMenu.php?id=' + father;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        return json;
    } catch (error) {
        console.log(error.message);
    }
}

function fillDiv(divID) {
    
}

function fillMainMenu(items) {
    let menuHtml = ``;
    if (items.length > 0) {
        items.forEach(async item => {
            menuHtml += `
            <li class="p-1">
                <a type="button" class="text-dark text-decoration-none dropdown-toggle" id="item${item['ID']}">${item['name']}</a>
                <div class="d-none" id="children${item['ID']}">
                </div>
            </li>`;
            const children = await getMenuItemsByFather(item['ID']);
            if (children.length > 0) {
                document.getElementById('item'+item['ID']).addEventListener('click', (event) => {
                    const display = document.getElementById('children'+item['ID']).style.display;
                    document.getElementById('children'+item['ID']).style.display = display == 'block' ? 'none' : 'block';
                });
            }
        });
        document.getElementById('mainMenuItems').innerHTML = menuHtml;
    }
}

async function getPrimaryMenu() {
    const url = 'utils/getters/getPrimaryMenu.php';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        fillMainMenu(json);
    } catch (error) {
        console.log(error.message);
    }
}

getPrimaryMenu()