<?php 
require_once '../../resources/config.php';
$host = 'localhost';
$dbName = 'basketball_db'; 
$dbUsername = 'root'; 
$dbPassword = '';  
$mysqli = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);
$delUser = $_GET['username'];
$result = mysqli_query($mysqli, "DELETE FROM `user` WHERE `username` = $delUser");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "refresh" content = "4; url = ../login/logout.php" />
<title>Διαγραφή Λογαριασμού</title>
</head>
<body>
<center><p id="del">Ο λογαριασμός του χρήστη <strong><?php echo $delUser; ?></strong> διαγράφηκε επιτυχώς. Γίνεται ανακατεύθυνση στην αρχική σελίδα</p></center>
</body>
</html>