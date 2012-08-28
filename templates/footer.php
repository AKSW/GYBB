<?php
require_once('classes/user.php');
require_once('config/config.php');
?>

</section>
<?php
if (DEBUG === false) {
	// TODO minify all scripts before setting DEBUG to false
	print '<script src="js/main.min.js"></script>';
}
?>
</div> <!-- end .container -->
</body>
</html>
