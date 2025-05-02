<?php
// Check if user is logged in
if (!isset($_SESSION["insession"])) {
    header("Location: login.php");
    exit;
}
/*
if(isset($_SESSION['user_id'])){
    if(!isset($_SESSION["insession"])){
        header("Location: 2fa_verify.php");
            exit;
    }
} 
*/

?>