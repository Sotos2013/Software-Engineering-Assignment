<?php
define('DIR_BASE',          dirname( dirname( __FILE__ ) ) . '/');
define('DIR_HTML',          DIR_BASE . 'public_html/');
define('DIR_ADMIN',         DIR_HTML . 'admin/');
define('DIR_USER',         DIR_HTML . 'user/');
define('DIR_IMG',           DIR_HTML . 'img/');
define('DIR_JS',            DIR_HTML . 'js/');
define('DIR_CSS',           DIR_HTML . 'css/');
define('BOOTSTRAP_CSS',     DIR_CSS . 'bootstrap.min.css');
define('DIR_LOGIN',         DIR_HTML . 'login/');
define('DIR_RESOURCES',     DIR_BASE . 'resources/');
define('DIR_RES_IMAGES',    DIR_RESOURCES . 'images/');
define('DIR_PLAYER_IMAGES', DIR_RES_IMAGES . 'players/');
define('DIR_TEAM_IMAGES',   DIR_RES_IMAGES . 'teams/');
define('ADMIN_NAVIGATION',  DIR_RESOURCES . 'admin_navbar.php');
define('USER_NAVIGATION',  DIR_RESOURCES . 'user_navbar.php');
define('MAIN_FOOTER',       DIR_RESOURCES . 'main_footer.php');
define('USER_FOOTER',       DIR_RESOURCES . 'user_footer.php');
define('AREF_BASE',         findBasePathToDir($_SERVER['PHP_SELF'], 'public_html'));
define('AREF_HTML',         AREF_BASE . 'public_html/');
define('AREF_LOGIN',        AREF_HTML . 'login/');
define('AREF_LOGOUT',       AREF_LOGIN . 'logout.php');
define('AREF_REGISTER',       AREF_LOGIN . 'registration.php');
define('AREF_DIR_IMG',      AREF_HTML . 'img/');
define('AREF_DIR_ADMIN',            	AREF_HTML . 'admin/');
define('AREF_DIR_USER',            	AREF_HTML . 'user/');
define('AREF_ADMIN_AVAILABLE_LEAGUES',  AREF_DIR_ADMIN . 'availableLeagues.php');
define('AREF_USER_AVAILABLE_LEAGUES',  AREF_DIR_USER . 'availableLeagues.php');
define('AREF_ADMIN_CREATE_LEAGUE',  	AREF_DIR_ADMIN . 'createLeague.php');
define('AREF_ADMIN_CREATE_PLAYER',  	AREF_DIR_ADMIN . 'createPlayer.php');
define('AREF_ADMIN_CREATE_TEAM',    	AREF_DIR_ADMIN . 'createTeam.php');
define('AREF_ADMIN_DISPLAY_LEAGUE', 	AREF_DIR_ADMIN . 'displayLeague.php');
define('AREF_USER_DISPLAY_LEAGUE', 	AREF_DIR_USER . 'displayLeague.php');
define('AREF_DELETE_ACCOUNT',       AREF_DIR_USER . 'deleteAccount.php');
define('AREF_ADMIN_DELETE_LEAGUE', 	AREF_DIR_ADMIN . 'deleteLeague.php');
define('AREF_ADMIN_DRAW_LEAGUE',    	AREF_DIR_ADMIN . 'drawLeague.php');
define('AREF_ADMIN_LOADING_LEAGUE', 	AREF_DIR_ADMIN . 'loadingLeague.php');
define('AREF_ADMIN_SEARCH_PLAYERS', 	AREF_DIR_ADMIN . 'searchPlayers.php');
define('AREF_ADMIN_SEARCH_TEAM', 	AREF_DIR_ADMIN . 'searchTeam.php');
define('AREF_USER_SEARCH_PLAYERS', 	AREF_DIR_USER . 'searchPlayers.php');
define('AREF_USER_SEARCH_TEAM', 	AREF_DIR_USER . 'searchTeam.php');
function connectDB() {
    $host = 'localhost';
    
    // ΣΤΟΝ SERVER ΤΟΥ ΤΜΗΜΑΤΟΣ: Το όνομα της βάσης είναι συνήθως το username σου
    $db = (gethostname() == 'users.iee.ihu.gr') ? 'iee2019187' : 'basketball_db';
    
    // 1. Χρήση ΑΠΟΛΥΤΗΣ ΔΙΑΔΡΟΜΗΣ για να το βρίσκει από παντού
    // Αντικατάστησε το require_once 'db_user_pass.php'; με αυτό:
	require_once '/home/student/iee/2019/iee2019187/public_html/Software-Engineering-Assignment/public_html/login/db_user_pass.php';
    
    $user = $DB_USER;
    $pass = $DB_PASS;

    try {
        if(gethostname() == 'users.iee.ihu.gr') {
            // 2. Σύνδεση μέσω Unix Socket (Απαραίτητο για users.iee.ihu.gr)
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8;unix_socket=/home/student/iee/2019/iee2019187/mysql/run/mysql.sock";
        } else {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
        }

        $db_lnk = new PDO($dsn, $user, $pass);
        $db_lnk->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db_lnk;
        
    } catch(PDOException $e) {
        // Κατά το development, εμφάνισε το πλήρες σφάλμα για να ξέρεις τι φταίει
        die("Σφάλμα σύνδεσης στη βάση: " . $e->getMessage());
    }
}
function displayErrorBanner(string $text, string $title = 'Σφάλμα!') {
	echo '<div class="alert alert-danger fade show" role="alert">';
	if(!empty($title)) {
		echo '<strong>'. $title . '</strong><br>';
	}
	echo $text;
	echo '</div><br>';
}

function displayWarningBanner(string $text, string $title = 'Προσοχή!') {
	echo '<div class="alert alert-warning fade show" role="alert">';
	if(!empty($title)) {
		echo '<strong>'. $title . '</strong><br>';
	}
	echo $text;
	echo '</div><br>';
}

function displaySuccessBanner(string $text, string $title = 'Επιτυχία!') {
	echo '<div class="alert alert-success fade show" role="alert">';
	if(!empty($title)) {
		echo '<strong>'. $title . '</strong><br>';
	}
	echo $text;
	echo '</div><br>';
}

function formInvalidFeedback(string $msg) {
	echo '<div class="invalid-feedback">';
	echo $msg;
	echo '</div>' . "\n";
}

function filter_data($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

function findBasePathToDir($php_self, $to) {
	$rpos = strrpos($php_self, $to);
	if($rpos === false) {
		return false;
	}
	else {
		return substr($php_self, 0, $rpos);
	}
}

function canonicalizeStrNumber(string $number_str, int $length) {
	while(strlen($number_str) < $length) {
		$number_str = '0' . $number_str;
	}

	return $number_str;
}

function uniqidReal($lenght = 13) {
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($lenght / 2));
    }
	elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($lenght / 2));
    }
	else {
        throw new Exception("No cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $lenght);
}

function fileUploadErrorMessages($code) {
	switch ($code) {
		case UPLOAD_ERR_INI_SIZE:
			$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
			break;
		case UPLOAD_ERR_FORM_SIZE:
			$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
			break;
		case UPLOAD_ERR_PARTIAL:
			$message = "The uploaded file was only partially uploaded";
			break;
		case UPLOAD_ERR_NO_FILE:
			$message = "No file was uploaded";
			break;
		case UPLOAD_ERR_NO_TMP_DIR:
			$message = "Missing a temporary folder";
			break;
		case UPLOAD_ERR_CANT_WRITE:
			$message = "Failed to write file to disk";
			break;
		case UPLOAD_ERR_EXTENSION:
			$message = "File upload stopped by extension";
			break;

		default:
			$message = "Unknown upload error";
			break;
	}
	return $message;
}
?>