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

function fillChildrenList(listIDMobile, listIDPC, children) {
    let currentForMobile = ``;
    let currentForPC = ``;
    children.forEach(async child => {
        const href = child['linkPage'] != null ? `href='page.php?id=${child['linkPage']}'` : "";
        currentForMobile = `
        <li class="px-4 py-1">
            <a type="button" ${href} class="text-dark text-decoration-none dropdown-toggle" id="item${child['ID']}">${child['name']}</a>
            <ul class="m-0 p-0 list-unstyled fs-3 fw-semibold d-none" id="children${child['ID']}">
            </ul>
        </li>`;
        document.getElementById(listIDMobile).insertAdjacentHTML('beforeend', currentForMobile);

        const gen = await getMenuItemsByFather(child['ID']);
        const dropString = href === "" ? `data-bs-toggle="dropdown" aria-expanded="false"` : ``;
        currentForPC = `
        <li class="dropdown-item dropend">
            <a class="text-dark text-decoration-none dropdown-toggle" ${href} role="button" id="itemPC${child['ID']}" ${dropString}>${child['name']}</a>
            <ul class="dropdown-menu" id="childrenPC${child['ID']}">
            </ul>
        </li>`;
        document.getElementById(listIDPC).insertAdjacentHTML('beforeend', currentForPC);

        if (gen.length > 0) {
            fillChildrenList('children'+child['ID'], 'childrenPC'+child['ID'], gen);
            document.getElementById('item'+child['ID']).addEventListener('click', (event) => {
                document.getElementById('children'+child['ID']).classList.toggle('d-none');
            });
        } else {
            document.getElementById('item'+child['ID']).classList.toggle('dropdown-toggle');
            document.getElementById('itemPC'+child['ID']).classList.toggle('dropdown-toggle');
            // document.getElementById('childrenPC'+child['ID']).style.display = 'none';
        }
    });
}

function fillMainMenu(items) {
    let currentForMobile = ``;
    let currentForPC = ``;
    if (items.length > 0) {
        items.forEach(async item => {
            currentForMobile = `
            <li class="p-1">
                <a type="button" class="text-dark text-decoration-none dropdown-toggle" id="item${item['ID']}">${item['name']}</a>
                <ul class="m-0 p-0 list-unstyled fs-3 fw-semibold d-none" id="children${item['ID']}">
                </ul>
            </li>`;
            document.getElementById('mainMenuItems').insertAdjacentHTML('beforeend', currentForMobile);

            currentForPC = `
            <div class="col pe-3">
                <div class="dropdown">
                    <a class="text-dark text-decoration-none dropdown-toggle fs-5" role="button" data-bs-toggle="dropdown" aria-expanded="false">${item['name']}</a>
                    <ul class="dropdown-menu fs-6" id="childrenPC${item['ID']}">
                    </ul>
                </div>
            </div>`;
            document.getElementById('navbarPC').insertAdjacentHTML('beforeend', currentForPC);

            const children = await getMenuItemsByFather(item['ID']);
            if (children.length > 0) {
                fillChildrenList('children'+item['ID'], 'childrenPC'+item['ID'], children);
                document.getElementById('item'+item['ID']).addEventListener('click', (event) => {
                    document.getElementById('children'+item['ID']).classList.toggle('d-none');
                });
            } else {
                document.getElementById('item'+item['ID']).classList.toggle('dropdown-toggle');
                document.getElementById('itemPC'+item['ID']).classList.toggle('dropdown-toggle');
                // document.getElementById('childrenPC'+child['ID']).style.display = 'none';
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