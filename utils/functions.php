<?php
function checkIsSet(array $vars): bool {
    foreach ($vars as $var) {
        if (!isset($_POST[$var])) {
            return false;
        }
    }
    return true;
}

function isAdminLoggedIn(): bool {
    return isset($_SESSION["email"]);
}
?>