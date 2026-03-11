<?php 
session_start();
require_once '../../resources/config.php';

// 1. Έλεγχος αν ο χρήστης είναι συνδεδεμένος
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . AREF_LOGIN);
    die();
}

// 2. Σύνδεση με τη βάση (υποστηρίζει localhost & users.iee.ihu.gr)
$conn = connectDB();
$message = "";

if(isset($_GET['username'])) {
    $delUser = $_GET['username'];
    
    // ΠΡΟΣΟΧΗ: Έλεγχος αν ο χρήστης διαγράφει τον ΕΑΥΤΟ ΤΟΥ 
    // (ή αν είσαι admin και διαγράφεις κάποιον άλλον)
    if($_SESSION['username'] == $delUser) {
        try {
            // 3. Χρήση Prepared Statement για απόλυτη ασφάλεια
            $stmt = $conn->prepare("DELETE FROM user WHERE username = :uname");
            $stmt->bindParam(':uname', $delUser, PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $message = "Ο λογαριασμός του χρήστη <strong>" . htmlspecialchars($delUser) . "</strong> διαγράφηκε επιτυχώς.";
                // Αν διέγραψε τον εαυτό του, η ανακατεύθυνση στο logout είναι σωστή
                $refreshUrl = "../login/logout.php";
            } else {
                $message = "Ο χρήστης δεν βρέθηκε.";
                $refreshUrl = "index.php";
            }
        } catch (PDOException $e) {
            $message = "Σφάλμα κατά τη διαγραφή: " . $e->getMessage();
            $refreshUrl = "index.php";
        }
    } else {
        $message = "Δεν έχετε δικαίωμα να διαγράψετε αυτόν τον χρήστη.";
        $refreshUrl = "index.php";
    }
} else {
    $message = "Δεν ορίστηκε χρήστης προς διαγραφή.";
    $refreshUrl = "index.php";
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="4; url=<?php echo $refreshUrl; ?>" />
    <title>Διαγραφή Λογαριασμού</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <style>
        body { 
            background-image: url("../img/brand/basketball-wallpaper.jpg");
            background-size: cover;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .message-box {
            background: rgba(0, 0, 0, 0.8);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            border: 1px solid #dc3545;
        }
    </style>
</head>
<body>
    <div class="message-box shadow-lg">
        <p class="lead"><?php echo $message; ?></p>
        <div class="spinner-border text-danger mt-3" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3 text-muted">Γίνεται ανακατεύθυνση...</p>
    </div>
</body>
</html>