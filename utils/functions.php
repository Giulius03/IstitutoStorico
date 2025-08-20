<?php
/**
 * Verifica che tutte le variabili passate tramite metodo POST siano settate.
 * @return boolean True se tutte le variabili sono settate, False altrimenti.
 */
function checkIsSet(array $vars): bool {
    foreach ($vars as $var) {
        if (!isset($_POST[$var])) {
            return false;
        }
    }
    return true;
}

/**
 * Verifica se l'admin è loggato.
 * @return boolean True se l'admin è loggato, False altrimenti.
 */
function isAdminLoggedIn(): bool {
    return isset($_SESSION["email"]);
}
?>