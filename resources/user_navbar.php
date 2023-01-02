<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
<style>
.navbar-dark {
	background: green;
}
.text {
	color: white;
}
.btn-outline-danger{
	background:red;
	color:white;
}
.navbar-collapse{
	color:white;
}
.nav-item{
	color:white;
}
</style>
	<div class="container-fluid">
		<a class="navbar-brand" href="<?= AREF_DIR_USER ?>">Εφαρμογή Στατιστικών Πρωταθλημάτων Μπάσκετ</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarCollapse">
			<ul class="navbar-nav me-auto mb-2 mb-md-0">
				<li class="nav-item mt-auto mb-auto">
					<a class="nav-link <?= ($currPage === 'userDashboard') ? ' active' : '' ?>" aria-current="page" href="<?= AREF_DIR_USER ?>">Αρχική</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= ($currPage === 'availableLeagues') ? ' active' : '' ?>" href="<?= AREF_USER_AVAILABLE_LEAGUES ?>">Διαθέσιμα Πρωταθλήματα</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= ($currPage === 'searchPlayers') ? ' active' : '' ?>" href="<?= AREF_USER_SEARCH_PLAYERS ?>">Αναζήτηση Παικτών</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= ($currPage === 'searchTeam') ? ' active' : '' ?>" href="<?= AREF_USER_SEARCH_TEAM ?>">Αναζήτηση Ομάδων</a>
				</li>
			</ul>
			<form class="d-flex" action="../logout.php">
				<a class="btn btn-outline-danger" href="<?= AREF_LOGOUT . '?lo' ?>">Αποσύνδεση</a>
			</form>
		</div>
	</div>
</nav>