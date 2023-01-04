<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'searchPlayers';
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] === true) {
	header('Location: '. AREF_LOGIN .'?lr');
	die();
}

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
try
{
	$conn=new PDO("mysql:host=$servername;dbname=basketball_db",$username,$password);
	$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	//echo 'connected';
}
catch(PDOException $e)
{
	echo '<br>'.$e->getMessage();
}
$searchErr = '';
$player_details='';
if(isset($_POST['save']))
{
	if(!empty($_POST['search']))
	{
		$search = $_POST['search'];
		$stmt = $conn->prepare("select * from player where name_en like '%$search%' or surname_en like '%$search%'");
		$stmt->execute();
		$player_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($employee_details);
		
	}
	else
	{
		$searchErr = "Please enter the information";
	}
   
}
?>
<!doctype html>
<html lang="el" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Αναζήτηση Παικτών</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/base.css"/>
		<link rel="stylesheet" href="./css/createLeague.css"/>
		<script src="../js/bootstrap.bundle.min.js"></script>
	</head>

	<body class="d-flex flex-column h-100">
		<header>
			<?php require_once ADMIN_NAVIGATION ?>
		</header>
		<main>
		<div class="container">
			<br><br>
			<h1 class="mt-5">Αναζήτηση Παικτών</h1>
			<p class="lead">Εισάγετε όνομα πρωταθλήματος και επιλέξτε τουλάχιστον 4 απο τις διαθέσιμες ομάδες.</p>
			<br>
			<form class="form-horizontal" action="#" method="post">
	<div class="row">
		<div class="form-group">
		    <label class="control-label col-sm-4" for="email"><b>Search Employee Information:</b>:</label>
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="search" placeholder="search here">
		    </div>
		    <div class="col-sm-2">
		      <button type="submit" name="save" class="btn btn-success btn-sm">Submit</button>
		    </div>
		</div>
		<div class="form-group">
			<span class="error" style="color:red;">* <?php echo $searchErr;?></span>
		</div>
		
	</div>
    </form>
	<br/><br/>
	<h3><u>Search Result</u></h3><br/>
	<div class="table-responsive">          
	  <table class="table">
	    <thead>
	      <tr>
	        <th>#</th>
	        <th>Player Name</th>
	        <th>Player Surname</th>
	        <th>Position Code</th>
	        <th>Team id</th>
	      </tr>
	    </thead>
	    <tbody>
	    		<?php
		    	 if(!$player_details)
		    	 {
		    		echo '<tr>No data found</tr>';
		    	 }
		    	 else{
		    	 	foreach($player_details as $key=>$value)
		    	 	{
		    	 		?>
		    	 	<tr>
		    	 		<td><?php echo $key+1;?></td>
		    	 		<td><?php echo $value['name_en'];?></td>
		    	 		<td><?php echo $value['surname_en'];?></td>
		    	 		<td><?php echo $value['player_position_code'];?></td>
		    	 		<td><?php echo $value['team_id'];?></td>
		    	 	</tr>
		    	 		
		    	 		<?php
		    	 	}
		    	 	
		    	 }
		    	?>
	    	
	     </tbody>
	  </table>
	</div>
		</div>
		</main>
		<?php require_once MAIN_FOOTER ?>
	</body>
</html>
