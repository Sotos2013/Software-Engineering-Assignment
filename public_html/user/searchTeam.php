<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'searchTeam';
if(!isset($_SESSION['log_in']) || !$_SESSION['log_in'] === true) {
	header('Location: '. AREF_DIR_USER .'?searchTeam.php');
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
$team_details='';
if(isset($_POST['save']))
{
	if(!empty($_POST['search']))
	{
		$search = $_POST['search'];
		$stmt = $conn->prepare("select * from team where name_en like '%$search%' or short_name_en like '%$search%'");
		$stmt->execute();
		$team_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($employee_details);
		
	}
	else
	{
		$searchErr = "Δεν πληκτρολογήσατε τίποτα. Προσπαθήστε ξανά!";
	}
   
}
?>
<!doctype html>
<html lang="el" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Αναζήτηση Ομάδων</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/base.css"/>
		<link rel="stylesheet" href="./css/createLeague.css"/>
		<script src="../js/bootstrap.bundle.min.js"></script>
	</head>
	<body class="d-flex flex-column h-100">
		<header>
			<?php require_once USER_NAVIGATION ?>
		</header>
		<main>
		<div class="container">
			<br><br>
			<h1 class="mt-5">Αναζήτηση Ομάδων</h1>
			<br>
			<form class="form-horizontal" action="#" method="post">
	<div class="row">
		<div class="form-group">
		    <div class="col-sm-4">
		      <input type="text" class="form-control" name="search" placeholder="Αναζήτηση εδώ">
		    </div>
		    <div class="col-sm-2">
		      <button type="submit" name="save" class="btn btn-success btn-sm">Αναζήτηση</button>
		    </div>
		</div>
		<div class="form-group">
			<span class="error" style="color:red;"><?php echo $searchErr;?></span>
		</div>
		
	</div>
    </form>
	<br/><br/>
	<h3><u>Search Result</u></h3><br/>
	<div class="table-responsive">          
	  <table class="table">
	    <thead>
	      <tr>
	        <th>Team Name</th>
	        <th>Team Short Name</th>
	        <th>City Id</th>
	        <th>Logo</th>
	      </tr>
	    </thead>
	    <tbody>
	    		<?php
				if($team_details!=null){
					 if(!$team_details)
					 {
						echo '<span class="error" style="color:red;">Δεν βρέθηκαν ομάδες με τα δεδομένα που πληκτρολογήσατε. Δοκιμάστε ξανά!</span>';
					 }
		    	 else{
		    	 	foreach($team_details as $row)
		    	 	{
		    	 		?>
		    	 	<tr>
		    	 		<td><?php echo $row['name_en'];?></td>
		    	 		<td><?php echo $row['short_name_en'];?></td>
		    	 		<td><?php echo $row['city_id'];?></td>
		    	 		<td><?php echo '<img src="' . $row['logo_path'] . '" width="50" height="50">'?></td>
		    	 	</tr>
					
		    	 		
		    	 		<?php
		    	 	}
				 }
				 }
		    	?>
	    	
	     </tbody>
	  </table>
	</div>
		</div>
		</main>
		<?php require_once USER_FOOTER ?>
	</body>
</html>
