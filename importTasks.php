<?php
	//TODO Change to tasks only (trackers/artifacts)
	ini_set("auto_detect_line_endings", "1");
	ini_set ("display_errors", "1");
	set_time_limit(120);
	error_reporting(E_ALL);

	session_start();
	if(!isset($_SESSION['sessionId'])) {
		header('Location: index.php');
	}

	// INCLUDE FILES 
	include_once 'functions.php';
	include_once 'includes/db.inc';
	
	if(isset($_POST['submit'])) {
		// setup the soap client
		$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
		$soapClient = new SoapClient($forge_soap_url);
		
		//print "isLoggedIn?: ".isLoggedIn($_SESSION['sessionId'])."<br>";
		//print "Forge API Version: ".$soapClient->version()."<br>";
	
		// Init variables
		$intGroupId 		= 0;
		$intGroupProjectId 	= 0;
		$strSummary			= "";
		$strDetails			= "";
		$intPriority		= 0;
		$intHours			= 0;
		$intStartDate		= 0;
		$intEndDate			= 0;
		$intCategoryId		= 0;
		$intPercentComplete	= 0;
		$arrAssignedTo		= 0;
		$arrDependentOn		= 0;
		$intDuration		= 0; // Not sure what this is for
		$intParentId		= 0;
		$trackerId			= 0;
		$taskId				= 0;
		$importedTasks		= array();
		
  		//echo "<pre>";
		//print_r($_FILES);
 		//echo "</pre>";

  		$file 				= getUploadFile($_FILES);
		
		// open the file for reading
		try {
			$filehandle = fopen($file,"r");			
		} catch (exception $e) {
			echo ($e->getMessage());
		}
		
		// To keep track of how many tasks we process
		$lineCount = 0;
		//echo "<h4>Before Loop: $lineCount</h4>";
		//echo "<h4>File Handle: $filehandle</h4>";
		
		// read until end of file
		while($line=fgetcsv($filehandle)) {
			//echo "<pre>";
			//print_r($line);
			//echo "</pre>";
			// skip the header line
			if("group_id" == $line[0]) {
				continue;
			}
			//echo "<h4>$lineCount</h4>";
			$lineCount++;
			
			$intGroupId 		= $line[0];
			$intGroupProjectId 	= $line[1];
			$strSummary			= $line[2];
			$strDetails			= $line[3];
			$intPriority		= $line[4];
			$intHours			= $line[5];
			$intStartDate		= strtotime($line[6]." 12:00:00");
			$intEndDate			= strtotime($line[7]." 13:00:00");
			// if there's no category, set the default of 100 ('None')
			$intCategoryId		= ("" == $line[8]) ? 100:$line[8];
			$intPercentComplete	= $line[9];
			$arrAssignedTo		= explode("|",$line[10]);
			$arrDependentOn		= explode("|",$line[11]);
			$intDuration		= $line[12]; // Not sure what this is for
			$intParentId		= $line[13];
			$trackerId			= $line[14];
 			
 			//echo $intStartDate."<br>";
 			//echo $intEndDate."<br>";
 			//echo "<pre>";
 			//print_r($line);
 			//echo "</pre>";
 	
			$taskId = $soapClient->addProjectTask($_SESSION['sessionId'],
												$intGroupId,
												$intGroupProjectId,
												$strSummary,
												$strDetails,
												$intPriority,
												$intHours,
												$intStartDate,
												$intEndDate,
												$intCategoryId,
												$intPercentComplete,
												$arrAssignedTo,
												$arrDependentOn,
												$intDuration,
												$intParentId);
			//echo "Task # ".$taskId." created<br>";
			$importedTasks[$lineCount]['taskId'] = $taskId;
			$importedTasks[$lineCount]['trackerId'] = $trackerId;
			$importedTasks[$lineCount]['groupId'] = $intGroupId;
			$importedTasks[$lineCount]['groupProjectId'] = $intGroupProjectId;
			$importedTasks[$lineCount]['summary'] = $strSummary;
			
            // Skip the tracker asoociation if the tracker id is null or 0
            if($importedTasks[$lineCount]['trackerId'] == null ||
                    $importedTasks[$lineCount]['trackerId'] == 0) {
                        
                // TODO this should be compartmentalized and called
                // Create tracker to task linkage
                $query = "INSERT INTO project_task_artifact VALUES($taskId,$trackerId)";
                if(!$db->query($query)) {
                    echo "Error creating tracker linkage from tracker $trackerId to task $taskId<br>";
                }           
            }
		}
		echo"<h4>".$lineCount." task(s) processed</h4>";
		
		// close file
		fclose($filehandle);
		
		// delete the file
		unlink($file);

	}
?>
<?php 
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
	<!-- Include navigation bar -->
	<?php include_once("includes/nav.php"); ?>

	<!-- Main content starts here -->
    <div class="container">
      <div id="flash">
        <?php
            if(isset($_SESSION['flashMessage']) && $_SESSION['flashMessage'] != "") {
                echo $_SESSION['flashMessage'];
            }
            //echo "<pre>";
            //print_r($_SESSION);
            //echo "</pre>";
        ?>
      </div>

      <h1>Fusionforge Task Importer</h1>
      <p>Use this tool to quickly bulk import tasks</p>
      <p><strong>Download a sample file to get the layout <a href="sampleImportFile.csv">here</a></strong></p>
      <?php
      	//if(isset($_SESSION['sessionId']) && $_SESSION['sessionId'] != "") {
      	
      ?>
      <!-- Content if logged in -->
      <?php 
      	//} else {
      ?>
      <!-- the form that uploads the file.  username & password necessary to login to the forge -->
      <form class="well" name="importer" enctype="multipart/form-data" action="importTasks.php" method="post">
          <label>File to Import</label>
          <input type="file" name="file" class="span3">
		  <label></label>
		  <input type="submit" name="submit" value="Import" class="btn">
      </form>
	  <?php
      	//} 
      	if(isset($importedTasks)) {
      		echo "<pre>";
			print_r($importedTasks);
			echo "</pre>";
      	}
	  ?>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
