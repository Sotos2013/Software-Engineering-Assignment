<?php
session_start();

// Ορίζουμε το βασικό URL του project σου
$base_url = "https://users.iee.ihu.gr/~iee2019187/Software-Engineering-Assignment/public_html/";

if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: ' . $base_url . 'admin/index.php');
    exit;
} 
elseif(isset($_SESSION["log_in"]) && $_SESSION["log_in"] === true) {
    header('Location: ' . $base_url . 'user/index.php');
    exit;
}
else {
    // Στέλνουμε απευθείας στο login με πλήρες URL
    header('Location: ' . $base_url . 'login/index.php');
    exit;
}
?>