<?php
$url='localhost';
$username='root';
$password='';
$conn=mysqli_connect($url,$username,$password,"basketball_db");
if(!$conn){
 die('Could not Connect My Sql:' .mysql_error());
}

$result = mysqli_query($conn,"SELECT * FROM championship");
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/createLeague.css">
<title>Διαγραφή Πρωταθλήματος</title>
</head>
<body>
<table>
<?php 
while($res = mysqli_fetch_array($result)) {
echo '<tr>'; 
echo "<td>".$res['name']."</td>";

 echo"<td> <a href=\"delete.php?id=$res[id]\"><button class='button' value=''>Delete</button></a></td>";
echo '</tr>'; 

}
?>
</table> 
</body>
</html>