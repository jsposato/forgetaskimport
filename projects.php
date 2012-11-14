<?php
	ini_set ("display_errors", "1");
	error_reporting(E_ALL);

	session_start();
	if(!isset($_SESSION['sessionId'])) {
		header('Location: index.php');
	}

	// INCLUDE FILES 
	include_once 'functions.php';
	include_once 'includes/db.inc';
	
	if(isset($_SESSION['sessionID'])) {
		header('Location: index.php');
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
    
    <!-- AJAX script -->
    <script type="text/javascript">
    	function showSubProjects() {
			var projectId = $("#projects option:selected").val();
			$.ajax({ url: "getsubprojects.php",
					data: {"projectId":projectId},
					type: 'post',
					success: function(output) {
						$("#subprojects").html(output);
					}
			});	
    	}
    </script>
    <!-- END AJAX script -->
  </head>

  <body>
	<!-- Include navigation bar -->
	<?php include_once("includes/nav.php"); ?>

	<!-- Main content starts here -->
    <div class="container">
      <div id="flash">
        <?php
            if(isset($_SESSION['flashMessage']) && $_SESSION['flashMessage'] != "") {
                echo $_SESSION['flashMessage'];
            }
 
 			$projectsQuery = "SELECT group_id,group_name FROM groups ORDER BY group_name ASC";
			$projects = $db->get_results($projectsQuery,ARRAY_A);
        ?>
      </div>

      <h1>Fusionforge Task Importer</h1>
      <div>
      	<select name="projects" id="projects" onchange="showSubProjects();" class="span4">
      	<?php
      		foreach($projects as $project) {
      			echo "<option value='$project[group_id]'>$project[group_id] - $project[group_name]</option>";
      		}
      	?>
      	</select>
      </div>
	  <div id="subprojects">
	  	
	  </div>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
  </body>
</html>
