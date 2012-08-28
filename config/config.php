<?php

if (file_exists(dirname( __FILE__ ) . '/config.localconf.php')) {
	require_once(dirname( __FILE__ ) . '/config.localconf.php');
} else {
  require_once(dirname( __FILE__ ) . '/config.remoteconf.php');
}

?>
