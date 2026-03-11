<footer class="site-footer">
<style>
.site-footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: green;
  color: white;
  text-align: center;
}
.text {
	color: white;
}
</style>
	<div class="container">
		<span class="text">Μηχανική Λογισμικού, Σύστημα Διαχείρισης Στατιστικών Πρωταθλημάτων Μπάσκετ.</span><br><strong>
    <?php 
    if(isset($_SESSION['username'])) {
        echo 'Συνδεδεμένος χρήστης : ' . htmlspecialchars($_SESSION['username']); 
    } else {
        echo 'Μη συνδεδεμένος χρήστης';
    }
    ?>
</strong>
	</div>
</footer>