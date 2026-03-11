<?php
session_start();
require_once '../../resources/config.php';

$currPage = 'displayLeague';

// 1. Έλεγχος αν ο χρήστης είναι συνδεδεμένος (Χρήση 'logged_in' για ομοιομορφία)
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . AREF_LOGIN . '?lr');
    die();
}

$err_msg = '';
$imageURL= "../img/brand/basketball-wallpaper.jpg";

// 2. Σύνδεση με τη βάση (Η συνάρτηση connectDB() χειρίζεται αυτόματα το Unix Socket)
$dbh = connectDB();
?>
<!DOCTYPE html>
<html lang="el">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Προβολή Πρωταθλημάτων</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./css/base.css"/>
        <link rel="stylesheet" href="./css/displayLeague.css"/>
        <script src="../js/bootstrap.bundle.min.js"></script>
        <style>
            body { 
                background-image: url(<?php echo $imageURL;?>); 
                background-repeat: no-repeat;
                background-size: cover;
                color: white;
            }
            .w-50, .lead , .mt-5 { color: white; }
            .col-lg-4 { color: #66FF66; }
            /* Προσθήκη για καλύτερη ανάγνωση κειμένου */
            .round-box {
                background: rgba(0, 0, 0, 0.7);
                border: 1px solid #444;
                border-radius: 10px;
            }
        </style>
    </head>
    <body class="d-flex flex-column h-100">
    
        <header>
            <?php require_once USER_NAVIGATION ?>
        </header>

        <main class="flex-shrink-0">
        <div class="container">
            <br><br>
            <h1 class="mt-5">Προβολή Πρωταθλημάτων</h1>
            <p class="lead">Πρόγραμμα αγώνων για το επιλεγμένο πρωτάθλημα:</p>
            <br>
            
            <?php
                // Εμφάνιση μηνυμάτων σφάλματος ή επιτυχίας
                if(isset($_SESSION['db_err'])) {
                    displayErrorBanner($_SESSION['db_err']);
                    unset($_SESSION['db_err']);
                }
                elseif(isset($_GET['inv_param'])) {
                    displayWarningBanner('Κάτι δεν πήγε καλά. Προσπαθήστε ξανά αργότερα');
                }

                // Έλεγχος αν υπάρχει το ID του πρωταθλήματος (cid)
                if(isset($_GET['cid']) && is_numeric($_GET['cid'])) {
                    $championship_id = (int)$_GET['cid'];

                    try {
                        // 1. Εύρεση αριθμού αγωνιστικών (rounds)
                        $sql = 'SELECT COUNT(id) AS num_rounds FROM round WHERE championship_id = :cid;';
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindParam(':cid', $championship_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $res_rounds = $stmt->fetch(PDO::FETCH_ASSOC);
                        $num_rounds = (int) $res_rounds['num_rounds'];

                        if($num_rounds > 0) {
                            // 2. Ανάκτηση όλων των αγώνων με JOIN για τα ονόματα των ομάδων
                            $sql_games = 'SELECT g.round_id, h_team.name_gr AS home_team, a_team.name_gr AS away_team
                                          FROM game g
                                          JOIN team h_team ON h_team.id = g.home_team_id
                                          JOIN team a_team ON a_team.id = g.away_team_id
                                          WHERE g.championship_id = :cid
                                          ORDER BY g.round_id;';

                            $stmt_games = $dbh->prepare($sql_games);
                            $stmt_games->bindParam(':cid', $championship_id, PDO::PARAM_INT);
                            $stmt_games->execute();
                            $results = $stmt_games->fetchAll(PDO::FETCH_ASSOC);

                            $matches_per_round = count($results) / $num_rounds;

                            echo '<div class="row align-self-center mb-5">';

                            for($i = 0; $i < $num_rounds; ++$i) {
                                echo '<div class="col-lg-4 text-center">' . "\n";
                                echo '  <div class="round-box p-3 m-3">' . "\n";
                                echo '      <h5 class="text-white">Αγωνιστική ' . ($i + 1) . '</h5>' . "\n";
                                echo '      <hr class="bg-light">' . "\n";

                                for($j = 0; $j < $matches_per_round; ++$j) {
                                    $index = $j + ($i * $matches_per_round);
                                    if(isset($results[$index])) {
                                        echo '<span> ' . htmlspecialchars($results[$index]['home_team']) .
                                             ' - ' . htmlspecialchars($results[$index]['away_team']) . "</span><br>\n";
                                    }
                                }
                                echo '  </div>' . "\n";
                                echo '</div>' . "\n";
                            }
                            echo '</div>';
                        } else {
                            displayErrorBanner('Δεν βρέθηκαν αγωνιστικές για αυτό το πρωτάθλημα.');
                        }
                    } catch(PDOException $ex) {
                        echo '<div class="alert alert-danger">Σφάλμα βάσης: ' . $ex->getMessage() . '</div>';
                    }
                } else {
                    displayWarningBanner('Δεν επιλέχθηκε έγκυρο πρωτάθλημα.');
                }
            ?>
        </div>
            
        <div class="d-flex justify-content-center mt-auto">
            <a href="<?= AREF_DIR_USER ?>" class="btn btn-primary mb-5 me-3" role="button">Αρχική</a>
            <a href="<?= AREF_USER_AVAILABLE_LEAGUES ?>" class="btn btn-success mb-5" role="button">Διαθέσιμα Πρωταθλήματα</a>
        </div>
        </main>

        <?php require_once USER_FOOTER ?>
    </body>
</html>