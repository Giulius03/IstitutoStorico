document.getElementById('btnSeePw').addEventListener('click', (event) => {
    changePwProp(document.getElementById('btnSeePw'), document.getElementById('password'));
});

function changePwProp(button, input) {
    let type = input.getAttribute("type") === "password" ? "text" : "password";
    input.setAttribute("type", type);
    let clName = "input-group-text ";
    clName = button.className === clName + "bi bi-eye-fill" ? clName + "bi bi-eye-slash-fill" : clName + "bi bi-eye-fill";
    button.className = clName;
}

/**
 * Verifica il login di un amministratore tramite AJAX e script PHP, comunicando eventuali errori.
 * @param {*} event 
 */
async function login(event) {
    event.preventDefault();

    const url = BASE_URL + 'utils/checkLogin.php';
    let formData = new FormData();
    formData.append('username', document.getElementById('username').value);
    formData.append('password', document.getElementById('password').value);

    try {
        const response = await fetch(url, {
            method: "POST",                   
            body: formData
        });
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        if (json["successful"] === true) {
            window.location.href = BASE_URL + "admin.php";
        } else {
            document.getElementById("loginError").innerHTML = `<p>${json["error"]}</p>`;
        }
    } catch (error) {
        console.log(error.message);
    }
}