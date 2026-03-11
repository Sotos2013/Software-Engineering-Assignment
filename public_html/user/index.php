<?php
session_start();
require_once '../../resources/config.php';

$currPage = 'userDashboard';

// Ενοποίηση του ελέγχου session (χρήση 'logged_in')
if(!isset($_SESSION['log_in']) || $_SESSION['log_in'] !== true) {
    header('Location: ' . AREF_LOGIN . '?lr');
    exit;
}

$imageURL= "../img/brand/basketball-wallpaper.jpg";
?>
<!DOCTYPE html>
<html lang="el" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Αρχική Σελίδα Χρήστη</title>
        <link rel="stylesheet" href="../css/bootstrap.min.css"/>
        <link rel="stylesheet" href="./css/base.css"/>
        <link rel="stylesheet" href="./css/index.css"/>
        <script src="../js/bootstrap.bundle.min.js"></script>
        <style>
            body { 
                background-image: url(<?php echo $imageURL;?>); 
                background-repeat: no-repeat;
                background-size: cover;
                height: 100vh;
            }
            
            .dashboard-container {
                background: rgba(0, 0, 0, 0.7);
                padding: 40px;
                border-radius: 20px;
                margin-top: 10%;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .btn-custom {
                width: 250px;
                padding: 15px;
                font-size: 1.1rem;
                margin-bottom: 20px;
                transition: transform 0.2s;
            }

            .btn-custom:hover {
                transform: scale(1.05);
            }

            h1 { color: white; text-shadow: 2px 2px 4px #000; }
        </style>
    </head>
    <body class="d-flex flex-column h-100 text-center">
        <header>
            <?php require_once USER_NAVIGATION ?>
        </header>

        <main class="flex-shrink-0">
            <div class="container">
                <div class="dashboard-container shadow-lg">
                    <h1 class="mb-5">Εφαρμογή Διαχείρισης Στατιστικών Πρωταθλημάτων Μπάσκετ</h1>
                    
                    <div class="d-grid gap-2 d-md-block">
                        <a href="<?php echo AREF_USER_AVAILABLE_LEAGUES ?>" class="btn btn-success btn-custom" role="button">
                            <i class="bi bi-trophy"></i> Διαθέσιμα Πρωταθλήματα
                        </a>
                        <br>
                        <a href="<?php echo AREF_USER_SEARCH_PLAYERS ?>" class="btn btn-primary btn-custom" role="button">
                            <i class="bi bi-person-search"></i> Αναζήτηση Παικτών
                        </a>
                        <br>
                        <a href="<?php echo AREF_USER_SEARCH_TEAM ?>" class="btn btn-info btn-custom text-white" role="button">
                            <i class="bi bi-shield-shaded"></i> Αναζήτηση Ομάδων
                        </a>
                    </div>
                </div>
            </div>
        </main>

        <?php require_once USER_FOOTER ?>
    </body>
</html>