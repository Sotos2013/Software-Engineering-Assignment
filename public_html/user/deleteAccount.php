<?php
session_start();
require_once '../../resources/config.php';

// 1. Έλεγχος πρόσβασης (Χρήση 'logged_in' για συνέπεια)
if ( (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) && 
     (!isset($_SESSION['log_in']) || $_SESSION['log_in'] !== true) ) {
    header('Location: ' . AREF_LOGIN . '?lr');
    exit;
}

// 2. Σύνδεση με τη βάση μέσω της συνάρτησης στο config.php (υποστηρίζει Unix Socket)
$conn = connectDB();

// 3. Παίρνουμε το username από το SESSION
// Σημείωση: Στον κώδικά σου είχες $_SESSION['user'], 
// βεβαιώσου ότι το κλειδί είναι αυτό και όχι $_SESSION['username']
$delUser = $_SESSION['username']; 

// 4. Ανακατεύθυνση στη σελίδα διαγραφής
// Στέλνουμε το username χωρίς τα επιπλέον εισαγωγικά, καθώς η deleteA.php 
// θα χρησιμοποιήσει PDO bindParam που τα χειρίζεται αυτόματα.
header("Location: deleteA.php?username=" . urlencode($delUser));
exit();
?>