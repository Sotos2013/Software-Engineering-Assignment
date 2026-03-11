<?php
session_start();
require_once '../../resources/config.php';
$currPage = 'searchTeam';

// 1. Έλεγχος πρόσβασης
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: '. AREF_LOGIN .'?lr');
    die();
}

// 2. Σύνδεση με τη βάση μέσω της κεντρικής συνάρτησης (υποστηρίζει τον server του τμήματος)
$conn = connectDB();

$searchErr = '';
$team_details = '';

if(isset($_POST['save']))
{
    if(!empty($_POST['search']))
    {
        $search = $_POST['search'];
        
        // 3. ΑΣΦΑΛΕΙΑ: Χρήση Prepared Statements με placeholders
        try {
            $stmt = $conn->prepare("SELECT * FROM team WHERE name_en LIKE :search OR short_name_en LIKE :search OR name_gr LIKE :search");
            $wildcardSearch = "%$search%";
            $stmt->bindParam(':search', $wildcardSearch);
            $stmt->execute();
            $team_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $searchErr = "Σφάλμα βάσης δεδομένων: " . $e->getMessage();
        }
    }
    else
    {
        $searchErr = "Δεν πληκτρολογήσατε τίποτα. Προσπαθήστε ξανά!";
    }
}
?>
<!doctype html>
<html lang="el" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Αναζήτηση Ομάδων</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./css/base.css"/>
        <style>
            body { 
                background-image: url("../img/brand/basketball-wallpaper.jpg");
                background-size: cover;
                color: white;
            }
            .main-container {
                background: rgba(0, 0, 0, 0.8);
                padding: 30px;
                border-radius: 15px;
                margin-top: 50px;
            }
            .table { color: white; }
            .error-text { color: #ff6666; font-weight: bold; }
        </style>
        <script src="../js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="d-flex flex-column h-100">
        <header>
            <?php require_once ADMIN_NAVIGATION ?>
        </header>
        <main>
            <div class="container main-container shadow">
                <h1 class="mt-3">Αναζήτηση Ομάδων</h1>
                <p class="lead">Αναζητήστε ομάδες βάσει ονόματος (Ελληνικά/Αγγλικά) ή σύντομου ονόματος.</p>
                
                <form class="form-horizontal mt-4" action="#" method="post">
                    <div class="row align-items-end">
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="search" placeholder="π.χ. Olympiacos ή Ολυμπιακός">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" name="save" class="btn btn-success w-100">Αναζήτηση</button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="error-text"><?php echo $searchErr;?></span>
                    </div>
                </form>

                <hr class="mt-5 mb-4">
                
                <h3>Αποτελέσματα Αναζήτησης</h3><br/>
                <div class="table-responsive">          
                  <table class="table table-hover">
                    <thead class="table-dark">
                      <tr>
                        <th>Όνομα Ομάδας</th>
                        <th>Σύντομο Όνομα</th>
                        <th>City ID</th>
                        <th>Logo</th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($team_details !== '') {
                             if(empty($team_details)) {
                                echo '<tr><td colspan="4" class="text-center error-text">Δεν βρέθηκαν ομάδες. Δοκιμάστε ξανά!</td></tr>';
                             } else {
                                foreach($team_details as $row) {
                                    ?>
                                    <tr>
                                        <td class="align-middle"><?php echo htmlspecialchars($row['name_en']);?></td>
                                        <td class="align-middle"><?php echo htmlspecialchars($row['short_name_en']);?></td>
                                        <td class="align-middle"><?php echo htmlspecialchars($row['city_id']);?></td>
                                        <td>
                                            <?php if(!empty($row['logo_path'])): ?>
                                                <img src="<?php echo htmlspecialchars($row['logo_path']); ?>" width="50" height="50" class="rounded shadow-sm bg-light">
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No Logo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                             }
                        }
                        ?>
                     </tbody>
                  </table>
                </div>
            </div>
        </main>
        <br><br>
        <?php require_once MAIN_FOOTER ?>
    </body>
</html>