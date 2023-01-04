<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'createLeague';
if(!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] === true) {
	header('Location: '. AREF_LOGIN .'?lr');
	die();
}
$warn = $err = '';
$championshipNameErr = $teamSelectionErr = '';
if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$count = 0;

	if(!isset($_POST['t']) || !is_array($_POST['t'])) {
		$teamSelectionErr = 'Παρακαλώ, επιλέξτε ομάδες για να συμμετάσχουν στο πρωτάθλημα.';
	}
	else {
		if(count($_POST['t']) % 2 != 0 || count($_POST['t']) < 4) {
			$teamSelectionErr = 'Πρέπει να επιλέξετε τουλάχιστον 4 ομάδες, και το πλήθος των ομάδων να είναι ζυγός αριθμός.';
		}
		else {
			$_SESSION['teams_in_league'] = serialize($_POST['t']);
			$count++;
		}
	}

	if(!isset($_POST['championship_name']) || empty($_POST['championship_name'])) {
		$championshipNameErr = 'Παρακαλώ συμπληρώστε το πεδίο';
	}
	else {
		$_SESSION['new_championship_name'] = $_POST['championship_name'];
		$count++;
	}
	if($count == 2) {
		header('Location: '. AREF_ADMIN_LOADING_LEAGUE);
	}
}
$imageURL= "../img/brand/basketball-wallpaper.jpg";
?>

<!doctype html>
<html lang="el" class="h-100">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>Δημιουργία Πρωταθλήματος</title>
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/base.css"/>
		<link rel="stylesheet" href="./css/createLeague.css"/>
		<script src="../js/bootstrap.bundle.min.js"></script>
	</head>
	<style>
		body { background-image: url(<?php echo $imageURL;?>); 
				background-repeat: repeat-y;
				background-size: cover;
		}
		
		.w-50, .lead , .mt-5, .pt-2 {
			color: white;
		}
	</style>
	<body class="d-flex flex-column h-100">
	<script>
		function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
	</script>
		<header>
			<?php require_once ADMIN_NAVIGATION ?>
		</header>
		<main>
		<div class="container">
			<br><br>
			<h1 class="mt-5">Δημιουργία Πρωταθλήματος</h1>
			<p class="lead">Εισάγετε όνομα πρωταθλήματος και επιλέξτε τουλάχιστον 4 απο τις διαθέσιμες ομάδες.</p>
			<br>
			<?php
				if($err) {
					displayErrorBanner($err);
				}
				if($warn) {
					displayWarningBanner($warn);
				}
				if($teamSelectionErr) {
					displayErrorBanner($teamSelectionErr);
				}

				$conn = connectDB();
				$data = $conn->query('SELECT id, name_gr, logo_path FROM team')->fetchAll();					
				if($data != null) {
					echo '<form method="POST" action="'. htmlspecialchars($_SERVER['PHP_SELF']) . '">' . "\n";
					echo '<div class="form-floating mb-3">
							<input
								type="text"
								name="championship_name"
								class="form-control ' . (($championshipNameErr) ? ' is-invalid' : '') . '"
								id="champ_name"
								placeholder="Championship Name"
								value="' . ((isset($_POST['championship_name'])) ? filter_data($_POST['championship_name']) : '') . '"
							>
							<label for="champ_name">Όνομα Πρωταθλήματος</label>' . "\n";
					if($championshipNameErr) formInvalidFeedback($championshipNameErr);
					
					echo '</div>' . "\n";
					echo '<div class="row ' . (($teamSelectionErr) ? 'border border-danger' : ''). '">' . "\n";
					
					foreach($data as $row) {
						echo '<div class="col-xl-2 mt-2 mb-2">' . "\n";
						echo '	<div class="border pb-3 m-1 text-center">' . "\n";
						echo '		<div class="custom-control custom-checkbox image-checkbox">' . "\n";
						echo '			<input type="checkbox" name="t[]" value="' . $row['id'] . '" class="custom-control-input" id=' . $row['id'] . '>' . "\n";
						echo '			<span class="lead mb-3"> '. $row['name_gr'] . '</span><br>' . "\n";
						echo '			<label class="custom-control-label" for="'. $row['id'] . '">' . "\n";
						echo '				<img src="' . $row['logo_path'] . '" alt="team-'. $row['id'] . '" class="img-fluid" />' . "\n";
						echo '			</label>' . "\n";
						echo '		</div>' . "\n";
						echo '	</div>' . "\n";
						echo '</div>' . "\n";
					}
					echo '<center>Επιλογή όλων</center>';
					echo '<input type="checkbox" onclick="toggle(this);" /><br />' . "\n";
					echo '</div>' . "\n";
					
					echo '<div class="d-flex flex-grow-1 justify-content-center align-items-center">' . "\n";
					echo '	<a href="./" class="btn btn-secondary mt-5 me-3 btn-single-line" role="button">Αρχική</a>' . "\n";
					echo '	<button type="reset" class="btn btn-danger mt-5 me-3">Εκκαθάριση Φόρμας</button>' . "\n";
					echo '	<button type="submit" class="btn btn-success mt-5 me-3">Δημιουργία Πρωταθλήματος</button>' . "\n";
					echo '</div>' . "\n";
					echo '</form>' . "\n";
				}
				else {
					displayWarningBanner('Δεν επιλέχθηκαν ομάδες.' .
						'<br/><a class="alert-link" href='. AREF_ADMIN_CREATE_TEAM .'>Δημιουργήστε</a> ομάδες και ξαναπροσπαθήστε');
				}

				$conn = null;
			?>

			<br><br>
		</div>
		</main>
		<?php require_once MAIN_FOOTER ?>
	</body>
</html>
