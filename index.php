<?php
session_start();

// Προσοχή στο διπλό public_html αν αυτή είναι η δομή σου
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
    // Αν το αρχείο αυτό βρίσκεται ήδη μέσα στο /public_html/, 
    // τότε το redirect πρέπει να δείχνει στον υποφάκελο login/
    header('Location: ./login/index.php'); 
    exit;
}
?>