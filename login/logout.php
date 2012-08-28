<?php

if (array_key_exists("logout", $_GET)) {
    session_start();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['oauth_provider']);
    session_destroy();
    header("location: /../index.php");
}
?>
<html>
    <head>
      	<title>get your bike back - the semantic way</title>
	<link rel="stylesheet" href="/css/menu_style.css" type="text/css" />
    </head>
    <body>
      	<ul id="menu">
		<li><a href="/../index.php" target="_self">Home</a></li>
		<li><a href="/../anzeige.php" target="_self">Anzeige aufgeben</a></li>
		<li><a href="/../map.php" target="_self">Karte</a></li>
		<li><a href="logout.php" target="_self">Logout</a></li>
	</ul>

You are logged out return to <a href="/../index.php" target="_self">Start</a>

