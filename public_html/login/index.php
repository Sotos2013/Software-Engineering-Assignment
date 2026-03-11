<?php
session_start();
require_once '../../resources/config.php';

// 1. ΑΝ ΕΙΝΑΙ ΗΔΗ ΣΥΝΔΕΔΕΜΕΝΟΣ (Admin), πήγαινέ τον στο Admin Dashboard
if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header('Location: ../admin/');
    exit;
}

// 2. ΑΝ ΕΙΝΑΙ ΗΔΗ ΣΥΝΔΕΔΕΜΕΝΟΣ (User), πήγαινέ τον στο User Dashboard
if(isset($_SESSION["log_in"]) && $_SESSION["log_in"] === true) {
    header('Location: ../user/');
    exit;
}

$login_err = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if(empty($username) || empty($password)) {
        $login_err = 'Συμπληρώστε όλα τα πεδία.';
    } else {
        $link = connectDB();
        // Επιλέγουμε τον χρήστη
        $sql = 'SELECT username, password FROM user WHERE username = :username';
        
        if($stmt = $link->prepare($sql)) {
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            
            if($stmt->execute()) {
                if($stmt->rowCount() == 1) {
                    if($row = $stmt->fetch()) {
                        $db_username = $row["username"];
                        $hashed_password = $row["password"];
                        
                        // Έλεγχος κωδικού (SHA-256)
                        if(hash('sha256', $password) === $hashed_password) {
                            // Καθορισμός ρόλου
                            // Μέσα στο login/index.php
                            if($db_username === "admin") {
                                $_SESSION['logged_in'] = true;
                                $_SESSION['username'] = $db_username; // Άλλαξε το 'user' σε 'username'
                                header('Location: ../admin/');
                            } else {
                                $_SESSION['log_in'] = true;
                                $_SESSION['username'] = $db_username; // Άλλαξε το 'user' σε 'username'
                                header('Location: ../user/');
                            }
                            die();
                        } else {
                            $login_err = 'Λάθος όνομα χρήστη ή κωδικός.';
                        }
                    }
                } else {
                    $login_err = 'Λάθος όνομα χρήστη ή κωδικός.';
                }
            } else {
                $login_err = 'Σφάλμα συστήματος. Δοκιμάστε αργότερα.';
            }
            unset($stmt);
        }
    }
    unset($link);
}
?>

<!doctype html>
<html lang="el">
	<head>
		<!-- Website settings -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="ESAKE Loggin Page">
		<title>Σύνδεση</title>

		<!-- Bootstrap and other required CSS -->
		<link rel="stylesheet" href="../css/bootstrap.min.css"/>
		<link rel="stylesheet" href="./css/login.css"/>
	</head>

	<body class="text-center">
	
	<main class="form-signin">

		<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
			<img class="mb-4" src=<?php echo AREF_DIR_IMG . 'brand/esake-logo.jpg'?> alt="esake-logo" height="120">
			
			<?php
				if($_SERVER["REQUEST_METHOD"] == "GET") {
					// Check if the user was redirected - "Login Required"
					if(isset($_GET["lr"])) {
						displayErrorBanner('Πρέπει να συνδεθείτε για να έχετε πρόσβαση στο σύστημα.', '');
					}
				}

				if($login_err){
					displayErrorBanner($login_err, '');
				}
			?>

			<h1 class="h3 mb-3 fw-normal">Σύνδεση</h1>

			<div class="form-floating">
				<input type="text" class="form-control" id="username" name="username" placeholder="Username">
				<label for="username">Όνομα χρήστη</label>
			</div>

			<div class="form-floating">
				<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				<label for="password">Κωδικός</label>
			</div>

			<button class="w-100 btn btn-lg btn-primary" type="submit">Σύνδεση</button><br>
			<p>Νέος Χρήστης; Εγγραφείτε <a href="registration.php">εδώ</a>
		</form>
		<p class="mt-5 mb-3 text-muted">Μηχανική Λογισμικού, 7ο Εξάμηνο</br>&copy; Ομάδα 2, 2022-23</p>
	</main>

	</body>
</html>
