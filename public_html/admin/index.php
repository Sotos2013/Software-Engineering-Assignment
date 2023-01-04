<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'adminDashboard';
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] === true) {
	header('Location: ' . AREF_LOGIN . 'lr');
	die();
}
$imageURL= "../img/brand/basketball-wallpaper.jpg";
?>
<!DOCTYPE html>
<html lang="el" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Αρχική</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/base.css"/>
		<link rel="stylesheet" href="./css/index.css"/>
		<script src="../js/bootstrap.bundle.min.js"></script>
  	</head>
	<style>
		body { background-image: url(<?php echo $imageURL;?>); 
				background-repeat: no-repeat;
				background-size: cover;
		}
		
		.mt-5 {
			color: white;
		}
	</style>
	<body class="d-flex flex-column h-100">
		<header>
			<?php require_once ADMIN_NAVIGATION ?>
		</header>
		<main>
		<div class="container">
			<br><br>
				<h1 class="mt-5">Εφαρμογή Διαχείρισης Στατιστικών Πρωταθλημάτων Μπάσκετ</h1>
			<br><center>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_CREATE_TEAM ?>" class="btn btn-primary" role="button">Δημιουργία Ομάδας</a>
			</div>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_CREATE_PLAYER ?>" class="btn btn-primary" role="button">Δημιουργία Παίκτη</a>
			</div>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_CREATE_LEAGUE ?>" class="btn btn-warning" role="button">Δημιουργία Πρωταθλήματος</a>
			</div>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_AVAILABLE_LEAGUES ?>" class="btn btn-success" role="button">Διαθέσιμα Πρωταθλήματα</a>
			</div>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_SEARCH_PLAYERS ?>" class="btn btn-success" role="button">Αναζήτηση Παικτών</a>
			</div>
			<div class="mb-5">
				<a href="<?php echo AREF_ADMIN_SEARCH_TEAM ?>" class="btn btn-success" role="button">Αναζήτηση Ομάδων</a>
			</div>
			</center>
			<br>
			<br>
		</div>
		</main>
		<?php require_once MAIN_FOOTER ?>
	</body>
</html>
