<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'searchPlayers';

// 1. Έλεγχος πρόσβασης
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: '. AREF_LOGIN .'?lr');
    die();
}

// 2. Σύνδεση με τη βάση μέσω της κεντρικής συνάρτησης
$conn = connectDB();

$searchErr = '';
$player_details = '';

if(isset($_POST['save']))
{
    if(!empty($_POST['search']))
    {
        $search = $_POST['search'];
        
        // 3. ΑΣΦΑΛΕΙΑ: Χρήση Prepared Statements αντί για απευθείας μεταβλητή στο string
        // Αυτό αποτρέπει την υποκλοπή δεδομένων μέσω SQL Injection
        $stmt = $conn->prepare("SELECT * FROM player WHERE name_en LIKE :search OR surname_en LIKE :search");
        $searchTerm = "%$search%";
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        
        $player_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else
    {
        $searchErr = "Παρακαλώ εισάγετε όνομα ή επώνυμο για αναζήτηση.";
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
            .main-container {
                background: rgba(0, 0, 0, 0.7);
                padding: 30px;
                border-radius: 15px;
                margin-top: 50px;
            }
            .table { color: white; }
        </style>
        <script src="../js/bootstrap.bundle.min.js"></script>
    </head>

    <body class="d-flex flex-column h-100">
        <header>
            <?php require_once ADMIN_NAVIGATION ?>
        </header>
        
        <main>
            <div class="container main-container shadow">
                <h1 class="mt-3">Αναζήτηση Παικτών</h1>
                <p class="lead">Αναζητήστε παίκτες με βάση το όνομα ή το επώνυμό τους (στα Αγγλικά).</p>
                
                <form class="form-horizontal mt-4" action="#" method="post">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <label class="form-label" for="search"><b>Όνομα ή Επώνυμο:</b></label>
                            <input type="text" class="form-control" name="search" id="search" placeholder="π.χ. Giannis">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="save" class="btn btn-success w-100">Αναζήτηση</button>
                        </div>
                    </div>
                    <?php if($searchErr): ?>
                        <div class="text-danger mt-2">* <?php echo $searchErr;?></div>
                    <?php endif; ?>
                </form>

                <hr class="mt-5 mb-4">
                
                <h3>Αποτελέσματα Αναζήτησης</h3>
                <div class="table-responsive mt-3">          
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Player Name</th>
                                <th>Player Surname</th>
                                <th>Position</th>
                                <th>Team ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(!$player_details) {
                                echo '<tr><td colspan="5" class="text-center">Δεν βρέθηκαν αποτελέσματα</td></tr>';
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
        
        <?php require_once MAIN_FOOTER ?>
    </body>
</html>