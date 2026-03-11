<?php
session_start();
require_once './resources/config.php';

if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: ./admin/');
    exit;
} 
elseif(isset($_SESSION["log_in"]) && $_SESSION["log_in"] === true) {
    header('Location: ./user/');
    exit;
}
else {
    // Στέλνουμε στο login ΜΟΝΟ αν δεν είμαστε ήδη εκεί
    header('Location: ./login/');
    exit;
}
?>