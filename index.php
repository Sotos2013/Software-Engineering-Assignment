<?php
session_start();
require_once './resources/config.php';

// 1. Αν είναι Admin, στείλτον στο admin dashboard
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: ' . AREF_DIR_ADMIN);
    exit;
} 
// 2. Αν είναι απλός χρήστης, στείλτον στο user dashboard
elseif(isset($_SESSION["log_in"]) && $_SESSION["log_in"] === true) {
    header('Location: ' . AREF_DIR_USER);
    exit;
}
// 3. Αν ΔΕΝ είναι συνδεδεμένος, στείλτον ΜΟΝΟ στο login
else {
    // Χρησιμοποιούμε το πλήρες URL για να μην μπερδευτεί ο Apache
    header('Location: https://users.iee.ihu.gr/~iee2019187/Software-Engineering-Assignment/public_html/login/');
    exit;
}
?>