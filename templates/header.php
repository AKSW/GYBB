<?php
require_once 'config/config.php';
?>

<!DOCTYPE html>
<html lang="de">
<head>
  <title>getyourbikeback</title>
  <meta charset="UTF-8" />
  <base href="<?php echo BASE_URL; ?>" />
  <?php
    if (DEBUG === true) {
  ?>
    <link rel="stylesheet" href="3rdparty/twitterBootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="3rdparty/jquery-ui/css/smoothness/jquery-ui-1.8.20.custom.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/map.css" />

    <script src="js/jquery-1.7.2.js"></script>
    <script src="3rdparty/twitterBootstrap/js/bootstrap.min.js"></script>
    <script src="3rdparty/jquery-ui/js/jquery-ui-1.8.20.custom.min.js"></script>
    <script src="js/OpenLayers-2.11.js"></script>

    <script type="text/javascript">
        $.baseURL = "<?php echo BASE_URL; ?>";
    </script>
    <script src="js/reportMap.js"></script>
    <script src="js/jquery.bikemap.js"></script>
    <script src="js/main.js"></script>

  <?php
    } else {
    // TODO the minified css does not exist yet
  ?>
    <link rel="stylesheet" href="css/main.min.css" />
  <?php
    }
  ?>

</head>

<body>
  <div class="container-fluid">
    <header id="header" class="row-fluid">
      <h1 class="span3"><a href="index.php?action=home" class="ir logo">Get your bike back</a></h1>
      <div class="span9">
        <h2>Crowdsourcing</h2>
        <h3>The semantic option to get your bike back</h3>
      </div>
    </header>
    <section id="content" class="row-fluid">
