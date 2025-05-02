<?php
// sessionStart.php
ini_set('session.cookie_httponly', 1);        // prevent JS access to cookies
ini_set('session.cookie_secure', 1);          // only over HTTPS
ini_set('session.use_strict_mode', 1);        // block session fixation

session_name('my_secure_session');            // optional custom name
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);              // regenerate session ID on first use
    $_SESSION['initiated'] = true;
}
?>
