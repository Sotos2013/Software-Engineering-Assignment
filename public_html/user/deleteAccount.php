<?php
	session_start();
	require_once '../../resources/config.php';
$url='localhost';
$username='root';
$password='';
$conn=mysqli_connect($url,$username,$password,"basketball_db");
if(!$conn){
 die('Could not Connect My Sql:' .mysql_error());
}

$result = mysqli_query($conn,"SELECT * FROM user");
$conn->close();
$delUser=$_SESSION['user'];
header("Location: deleteA.php?username='$delUser'");
exit();
?>