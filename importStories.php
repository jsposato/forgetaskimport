<?php
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
		$intGroupArtifactId = 0;
		$intStatusId		= 0;
		$intPriority		= 0;
		$intAssignedTo		= 0;
		$strSummary			= "";
		$strDetails			= "";
		
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
			$intGroupArtifactId = $line[1];
			$strSummary			= $line[2];
			$strDetails			= $line[3];
			$intPriority		= $line[4];
			$intAssignedTo		= $line[5];
			$intStatusId		= $line[6];
			
  			//echo "<pre>";
 			//print_r($line);
 			//echo "</pre>";
 			
 			$query = "SELECT extra_field_id FROM artifact_extra_field_list WHERE alias='status' AND group_artifact_id=$intGroupArtifactId";
			$aeflItem = $db->get_row($query);
			$query = "SELECT element_id FROM artifact_extra_field_elements WHERE extra_field_id=$aeflItem->extra_field_id AND element_name='New'";
			$aefeItem = $db->get_row($query);
			
			$arrExtraFieldsData = array('extra_field_id' => $aeflItem->extra_field_id,
										'field_data' => $aefeItem->element_id);
			
			echo "<pre>";
			print_r($arrExtraFieldsData);
			echo "</pre>";
			$trackerId = $soapClient->addArtifact($_SESSION['sessionId'],
												 $intGroupId,
												 $intGroupArtifactId,
												 $intStatusId,
												 $intPriority,
												 $intAssignedTo,
												 $strSummary,
												 $strDetails,
												 $arrExtraFieldsData
												 );
			exit;
		}
		echo"<h4>".$lineCount." story(ies) processed</h4>";
		
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
      <p>Use this tool to quickly bulk import stories (trackers)</p>
      <p><strong>Download a sample file to get the layout <a href="sampleStoryImport.csv">here</a></strong></p>
      <?php
      	//if(isset($_SESSION['sessionId']) && $_SESSION['sessionId'] != "") {
      	
      ?>
      <!-- Content if logged in -->
      <?php 
      	//} else {
      ?>
      <!-- the form that uploads the file.  username & password necessary to login to the forge -->
      <form class="well" name="importer" enctype="multipart/form-data" action="importStories.php" method="post">
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
