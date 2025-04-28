<?php
function checkIsSet(array $vars): bool {
    foreach ($vars as $var) {
        if (!isset($_POST[$var])) {
            return false;
        }
    }
    return true;
}
?>