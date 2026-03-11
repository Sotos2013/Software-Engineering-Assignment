<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'searchplayers';

// 1. Έλεγχος πρόσβασης (Χρήση 'logged_in' για συνέπεια)
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: '. AREF_LOGIN .'?lr');
    die();
}

// 2. Σύνδεση με τη βάση μέσω της connectDB()
$conn = connectDB();

$searchErr = '';
$player_details = '';

if(isset($_POST['save'])) {
    if(!empty($_POST['search'])) {
        $search = $_POST['search'];
        
        // 3. ΑΣΦΑΛΕΙΑ: Prepared Statements για αποφυγή SQL Injection
        try {
            $stmt = $conn->prepare("SELECT * FROM player WHERE name_en LIKE :search OR surname_en LIKE :search");
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
            $stmt->execute();
            $player_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            $searchErr = "Σφάλμα κατά την αναζήτηση.";
        }
    } else {
        $searchErr = "Παρακαλώ εισάγετε όνομα ή επώνυμο.";
    }
}
?>
<!doctype html>
<html lang="el" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Αναζήτηση Παικτών</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./css/base.css"/>
        <style>
            body { 
                background-image: url("../img/brand/basketball-wallpaper.jpg");
                background-size: cover;
                color: white;
            }
            .search-container {
                background: rgba(0, 0, 0, 0.75);
                padding: 30px;
                border-radius: 15px;
                margin-top: 50px;
                border: 1px solid #444;
            }
            .table { color: white; }
            .table-hover tbody tr:hover { color: #66FF66; background-color: rgba(255,255,255,0.1); }
        </style>
        <script src="../js/bootstrap.bundle.min.js"></script>
    </head>

    <body class="d-flex flex-column h-100">
        <header>
            <?php require_once USER_NAVIGATION ?>
        </header>

        <main>
            <div class="container search-container shadow-lg">
                <h1 class="mt-3">Αναζήτηση Παικτών</h1>
                <p class="lead">Βρείτε πληροφορίες για τους παίκτες του πρωταθλήματος.</p>
                <br>

                <form class="form-horizontal" action="#" method="post">
                    <div class="row align-items-end">
                        <div class="col-md-8 mb-3">
                            <label class="form-label"><b>Αναζήτηση (στα Αγγλικά):</b></label>
                            <input type="text" class="form-control" name="search" placeholder="π.χ. Antetokounmpo">
                        </div>
                        <div class="col-md-4 mb-3">
                            <button type="submit" name="save" class="btn btn-success w-100">Αναζήτηση</button>
                        </div>
                    </div>
                    <?php if($searchErr): ?>
                        <div class="text-danger mt-2"><b>* <?php echo $searchErr;?></b></div>
                    <?php endif; ?>
                </form>

                <br>
                <h3>Αποτελέσματα</h3>
                <div class="table-responsive">          
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Όνομα</th>
                                <th>Επώνυμο</th>
                                <th>Θέση</th>
                                <th>ID Ομάδας</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if($player_details === '') {
                                // Αρχική κατάσταση πριν την αναζήτηση
                            } elseif(empty($player_details)) {
                                echo '<tr><td colspan="5" class="text-center text-warning">Δεν βρέθηκαν αποτελέσματα.</td></tr>';
                            } else {
                                foreach($player_details as $key => $value) {
                                    echo "<tr>
                                            <td>" . ($key + 1) . "</td>
                                            <td>" . htmlspecialchars($value['name_en']) . "</td>
                                            <td>" . htmlspecialchars($value['surname_en']) . "</td>
                                            <td>" . htmlspecialchars($value['player_position_code']) . "</td>
                                            <td>" . htmlspecialchars($value['team_id']) . "</td>
                                          </tr>";
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