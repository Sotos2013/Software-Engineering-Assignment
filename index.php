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
    // Χρησιμοποιούμε την απόλυτη διαδρομή URL για τον server
    header('Location: /~iee2019187/Software-Engineering-Assignment/public_html/login/index.php');
    exit;
}
?>