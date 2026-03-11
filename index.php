<?php
session_start();

// Προσωρινό check χωρίς config για να δούμε αν φταίνε οι σταθερές
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: ./admin/index.php');
    exit;
} 
else {
    // Στέλνουμε απευθείας στο login με σχετικό path
    header('Location: ./login/index.php');
    exit;
}
?>