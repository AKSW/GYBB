<?php
require_once 'classes/user.php';
require_once 'config/config.php';
?>

</section>
<footer>
<?php
if ($user = User::getCurrentUser()) {
	print "<p>Eingeloggt als: " . $user->name . "</p>";
} else {
	print "<p>Bitte melden Sie sich an</p>";
}
?>
</footer>
<?php
if (DEBUG === false) {
  // TODO minify all scripts before setting DEBUG to false
	print '<script src="js/main.min.js"></script>';
}
?>
</div> <!-- end .container -->
</body>
</html>
