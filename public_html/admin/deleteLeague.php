<?php
session_start();
// Φορτώνουμε το αρχείο ρυθμίσεων που περιέχει τη συνάρτηση connectDB()
require_once '../../resources/config.php';

// Έλεγχος πρόσβασης (αν χρειάζεται να είναι logged in ο admin)
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . AREF_LOGIN . '?lr');
    die();
}

// Σύνδεση με τη βάση (η συνάρτηση θα επιλέξει αυτόματα localhost ή users.iee.ihu.gr)
$conn = connectDB();

try {
    // Εκτέλεση του ερωτήματος με PDO
    $stmt = $conn->query("SELECT * FROM championship");
    $championships = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Σφάλμα κατά την ανάκτηση δεδομένων: " . $e->getMessage());
}

$imageURL = "../img/brand/basketball-wallpaper.jpg";
?>
<!DOCTYPE html>
<html lang="el" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Διαχείριση Πρωταθλημάτων</title>
    
    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="./css/base.css"/>
    <link rel="stylesheet" href="./css/availableLeagues.css"/>
    
    <style>
        body { 
            background-image: url(<?php echo $imageURL; ?>); 
            background-repeat: no-repeat;
            background-size: cover;
            color: white;
        }
        .table-container {
            background: rgba(0, 0, 0, 0.7);
            padding: 30px;
            border-radius: 15px;
            margin-top: 50px;
        }
        .table { color: white; }
    </style>
</head>
<body class="d-flex flex-column h-100">

    <header>
        <?php require_once ADMIN_NAVIGATION ?>
    </header>

    <main class="container">
        <div class="table-container shadow">
            <h1 class="mb-4">Διαγραφή Πρωταθλήματος</h1>
            <p class="lead">Επιλέξτε το πρωτάθλημα που επιθυμείτε να διαγράψετε οριστικά.</p>
            
            <table class="table table-hover mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Όνομα Πρωταθλήματος</th>
                        <th class="text-center">Ενέργεια</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($championships) > 0): ?>
                        <?php foreach($championships as $row): ?>
                        <tr> 
                            <td class="align-middle"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td class="text-center">
                                <a href="delete.php?id=<?php echo $row['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Είστε σίγουροι ότι θέλετε να διαγράψετε αυτό το πρωτάθλημα;')">
                                   Διαγραφή
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2" class="text-center">Δεν βρέθηκαν πρωταθλήματα.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="mt-4">
                <a href="<?= AREF_DIR_ADMIN ?>" class="btn btn-secondary">Επιστροφή</a>
            </div>
        </div>
    </main>

    <?php require_once MAIN_FOOTER ?>
</body>
</html>