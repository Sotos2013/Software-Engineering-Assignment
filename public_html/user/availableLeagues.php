<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'availableLeagues';
// 1. Έλεγχος πρόσβασης (Χρήση 'logged_in' για συνέπεια)
if ( !isset($_SESSION['log_in']) || $_SESSION['log_in'] !== true ) {
    header('Location: ' . AREF_LOGIN . '?lr');
    exit;
}
$imageURL= "../img/brand/basketball-wallpaper.jpg";
?>
<!doctype html>
<html lang="el" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Διαθέσιμα Πρωταθλήματα</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/base.css"/>
		<link rel="stylesheet" href="./css/availableLeagues.css"/>
		<script src="../js/bootstrap.bundle.min.js"></script>
  	</head>
	<style>
		body { background-image: url(<?php echo $imageURL;?>); 
				background-repeat: no-repeat;
				background-size: cover;
		}
		
		.w-50, .lead , .mt-5, .pt-2 {
			color: white;
		}
	</style>
	<body class="d-flex flex-column h-100">
	
		<header>
			<?php require_once USER_NAVIGATION ?>
		</header>
		<main>
		<div class="container">
			<br><br>
			<h1 class="mt-5">Διαθέσιμα Πρωταθλήματα</h1>
			<p class="lead">Προβολή και διαγραφή των πρωταθλημάτων.</p>
			<br>	

			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th></th>
						<th class="w-50">Όνομα Πρωταθλήματος</th>
						<th class="w-50">Εργαλεία</th>
					</tr>
				</thead>
				<tbody>
				<?php
					try {
						$dbh = connectDB();

						$sql = 'SELECT * FROM championship';

						$data = $dbh->query($sql)->fetchAll();
						
						$i = 1;
						foreach($data as $row) {
				?>
					<tr>
						<th>
							<h4 class="pt-2"><?= $i++ ?></h4>
						</th>
						<td>
							<h4 class="pt-2"><?= $row['name'] ?></h4>
						</td>
						<td>
							<a href="<?= AREF_USER_DISPLAY_LEAGUE ?>?cid=<?= $row['id'] ?>" class="btn btn-success btn-enlarge me-2 mt-1 mb-1" role="button">Προβολή</a>
							<a href="<?= AREF_USER_DELETE_LEAGUE ?>?cid=<?= $row['id'] ?>" class="btn btn-danger btn-enlarge me-2 mt-1 mb-1" role="button">Διαγραφή</a>
						</td>
					</tr>
				<?php
						}
					}
					catch(PDOException $ex) {
						echo 'Failed to fetch the championships. Reason: ' . $ex->getMessage();
					}
					
				?>
				</tbody>
			</table>
			
			<div class="d-flex flex-grow-1 justify-content-center align-items-center">
				<a href="<?= AREF_DIR_USER ?>" class="btn btn-primary mt-5 mb-5" role="button">Αρχική</a>
			</div>

		</div>
		</main>

		<!-- Footer -->
		<?php require_once USER_FOOTER ?>

	</body>
	
</html>
