document.getElementById('logout').addEventListener('click', (event) => {
    logout();
});

async function logout() {
    const url = 'utils/logout.php';
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }
        const json = await response.json();
        console.log(json);
        if (json["successful"] === true) {
            window.location.href = "index.php";
        }
    } catch (error) {
        console.log(error.message);
    }
}