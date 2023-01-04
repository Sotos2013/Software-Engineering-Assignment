<?php 

$host = 'localhost';
$dbName = 'basketball_db'; 
$dbUsername = 'root'; 
$dbPassword = '';  
$mysqli = mysqli_connect($host, $dbUsername, $dbPassword, $dbName);
$id = $_GET['id'];
$result = mysqli_query($mysqli, "DELETE FROM championship WHERE id=$id");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv = "refresh" content = "4; url = index.php" />
<title>Διαγραφή Πρωταθλήματος</title>
</head>
<body>
<center><p>Tο πρωτάθλημα διαγράφηκε. Γίνεται ανακατεύθυνση στην αρχική σελίδα</p></center>
</body>
</html>