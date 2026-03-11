<?php
session_start();
// Αρχικοποίηση όλων των μεταβλητών ειδοποίησης
$showAlert = false;
$showError = false; // Εδώ έλειπε ή δεν έπαιρνε τιμή
$exists = false;
// Χρησιμοποιούμε το config που ήδη έχουμε διορθώσει
require_once '../../resources/config.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $link = connectDB(); // Καλούμε τη συνάρτηση που φτιάξαμε στο config.php
    
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $hash = hash('sha256', $password);

    if(!empty($username) && !empty($password)) {
        // Έλεγχος αν υπάρχει ήδη ο χρήστης (PDO style)
        $sql = "SELECT * FROM user WHERE username = :username";
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $exists = "Το όνομα χρήστη χρησιμοποιείται ήδη.";
        } else {
            // Εισαγωγή νέου χρήστη
            $insert_sql = "INSERT INTO user (username, password, create_time) VALUES (:username, :password, current_timestamp())";
            $insert_stmt = $link->prepare($insert_sql);
            $insert_stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $insert_stmt->bindParam(':password', $hash, PDO::PARAM_STR);
            
            if($insert_stmt->execute()) {
                $showAlert = true;
            }
        }
    }
}
?>
	
<!doctype html>
	
<html lang="en">

<head>
	
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content=
		"width=device-width, initial-scale=1,
		shrink-to-fit=no">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href=
"https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
		integrity=
"sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
		crossorigin="anonymous">
</head>
	
<body>
	
<?php
	
	if($showAlert) {
	
		echo ' <div class="alert alert-success
			alert-dismissible fade show" role="alert">
	
			<strong>Success!</strong> Your account is
			now created and you can <a href="index.php">login.</a>
			<button type="button" class="close"
				data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div> ';
	}
	
	if($showError) {
	
		echo ' <div class="alert alert-danger
			alert-dismissible fade show" role="alert">
		<strong>Error!</strong> '. $showError.'
	
	<button type="button" class="close"
			data-dismiss="alert aria-label="Close">
			<span aria-hidden="true">×</span>
	</button>
	</div> ';
}
		
	if($exists) {
		echo ' <div class="alert alert-danger
			alert-dismissible fade show" role="alert">
	
		<strong>Error!</strong> '. $exists.'
		<button type="button" class="close"
			data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">×</span>
		</button>
	</div> ';
	}

?>
	
<div class="container my-4 ">
	
	<h1 class="text-center">Signup Here</h1>
	<form action="" method="post">
	
		<div class="form-group">
			<label for="username">Username</label>
		<input type="text" class="form-control" id="username"
			name="username" />	
		</div>
	
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control"
			id="password" name="password" />
		</div>
	
		<button type="submit" class="btn btn-primary">
		SignUp
		</button>
	</form>
</div>

<script src="
https://code.jquery.com/jquery-3.5.1.slim.min.js"
	integrity="
sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
	crossorigin="anonymous">
</script>
	
<script src="
https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
	integrity=
"sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
	crossorigin="anonymous">
</script>
	
<script src="
https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
	integrity=
"sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
	crossorigin="anonymous">
</script>
</body>
</html>
