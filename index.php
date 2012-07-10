<?php 
	session_start();
	if(isset($_SESSION['sessionID'])) {
		header('Location: import.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Fusionforge Task Importer!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">FusionForge Task Importer</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="import.php">Import</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

	<!-- Main content starts here -->
    <div class="container">
      <div id="flash">
        <?php
            if(isset($_SESSION['flashMessage'] && $_SESSION['flashMessage'] != "")) {
                echo $_SESSION['flashMessage'];
            }
        ?>
      </div>
      <h1>Fusionforge Task Importer</h1>
      <p>Use this tool to quickly bulk import tasks</p>
      <?php
      	if(isset($_SESSION['sessionId']) && $_SESSION['sessionId'] != "") {
      	
      ?>
      <h3>Miscellaneuos Tools to Help You</h3>
      <ul>
      	<li><a href="myprojects.php" target="_blank">My Projects</a></li>
      </ul>
      <!-- Content if logged in -->
      <?php 
      	} else {
      ?>
      <!-- the form that uploads the file.  username & password necessary to login to the forge -->
      <form class="well" name="importer" enctype="multipart/form-data" action="login.php" method="post">
          <label>Username</label>
          <input type="text" name="username" class="span3" placeholder="Enter your username" size="10" maxlength="25">
          <label>Password</label>
          <input type="password" name="password" class="span3" placeholder="Enter your password" size="10" maxlength="25">
		  <label></label>
		  <input type="submit" name="submit" value="Login" class="btn">
      </form>
	  <?php
      	} 
	  ?>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-alert.js"></script>
    <script src="js/bootstrap-modal.js"></script>
    <script src="js/bootstrap-dropdown.js"></script>
    <script src="js/bootstrap-scrollspy.js"></script>
    <script src="js/bootstrap-tab.js"></script>
    <script src="js/bootstrap-tooltip.js"></script>
    <script src="js/bootstrap-popover.js"></script>
    <script src="js/bootstrap-button.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-carousel.js"></script>
    <script src="js/bootstrap-typeahead.js"></script>

  </body>
</html>
