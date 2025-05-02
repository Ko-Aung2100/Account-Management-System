<?php
// secure input , output, to prevent XSS attack
$domain = "http://localhost:3000/";
function escape($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>