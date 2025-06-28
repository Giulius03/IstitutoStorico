async function getMenuItemsByFather(father) {
    const url = 'utils/getters/getPrimaryMenu.php?id=' + father;
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        return json;
    } catch (error) {
        console.log(error.message);
    }
}

function fillChildrenList(listID, children) {
    let current = ``;
    children.forEach(async child => {
        const href = child['linkPage'] != null ? `href='page.php?id=${child['linkPage']}'` : "";
        current = `
        <li class="px-4 py-1">
            <a type="button" ${href} class="text-dark text-decoration-none dropdown-toggle" id="item${child['ID']}">${child['name']}</a>
            <ul class="m-0 p-0 list-unstyled fs-3 fw-semibold d-none" id="children${child['ID']}">
            </ul>
        </li>`;
        document.getElementById(listID).insertAdjacentHTML('beforeend', current);
        const gen = await getMenuItemsByFather(child['ID']);
        if (gen.length > 0) {
            fillChildrenList('children'+child['ID'], gen);
            document.getElementById('item'+child['ID']).addEventListener('click', (event) => {
                document.getElementById('children'+child['ID']).classList.toggle('d-none');
            });
        } else {
            document.getElementById('item'+child['ID']).classList.toggle('dropdown-toggle');
        }
    });
}

function fillMainMenu(items) {
    let current = ``;
    if (items.length > 0) {
        items.forEach(async item => {
            current = `
            <li class="p-1">
                <a type="button" class="text-dark text-decoration-none dropdown-toggle" id="item${item['ID']}">${item['name']}</a>
                <ul class="m-0 p-0 list-unstyled fs-3 fw-semibold d-none" id="children${item['ID']}">
                </ul>
            </li>`;
            document.getElementById('mainMenuItems').insertAdjacentHTML('beforeend', current);
            const children = await getMenuItemsByFather(item['ID']);
            if (children.length > 0) {
                fillChildrenList('children'+item['ID'], children);
                document.getElementById('item'+item['ID']).addEventListener('click', (event) => {
                    document.getElementById('children'+item['ID']).classList.toggle('d-none');
                });     
            } else {
                document.getElementById('item'+item['ID']).classList.toggle('dropdown-toggle');
            }
        });
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
        fillMainMenu(json);
    } catch (error) {
        console.log(error.message);
    }
}

getPrimaryMenu()