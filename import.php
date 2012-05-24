<?php
ini_set ("display_errors", "1");
error_reporting(E_ALL);
	// start the session managment
	session_start();

	// INCLUDE FILES 
	include_once 'functions.php';
	
	if(isset($_POST['submit'])) {
		
		// get credentials to login
		$user 				= htmlspecialchars($_POST['username']);
		$password 			= htmlspecialchars($_POST['password']);
		$user 				= 'jsposato';
		$password 			= 'codiesassy';
		
		// setup the soap client
		$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
		$soapClient = new SoapClient($forge_soap_url);
		
		// check to see if we can login
		$sessionKey = doLogin($soapClient,$user,$password);
		if($sessionKey != null) {
			$_SESSION['sessionId'] = $sessionKey;
		}
		
		//print "isLoggedIn?: ".isLoggedIn($_SESSION['sessionId'])."<br>";
		print "Forge API Version: ".$soapClient->version()."<br>";
				
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
		
		echo "<pre>";
		print_r($_FILES);
		echo "</pre>";

  		$file 				= getUploadFile($_FILES);
		
		// open the file for reading
		$filehandle = fopen($file,"r");
		
		// read until end of file
		while($line=fgetcsv($filehandle)) {
			// skip the header line
			if("group_id" == $line[0]) {
				continue;
			}

			$intGroupId 		= $line[0];
			$intGroupProjectId 	= $line[1];
			$strSummary			= $line[2];
			$strDetails			= $line[3];
			$intPriority		= $line[4];
			$intHours			= $line[5];
			$intStartDate		= strtotime($line[6]);
			$intEndDate			= strtotime($line[7]);
			// if there's not category, set the default of 100 ('None')
			$intCategoryId		= ("" == $line[8]) ? 100:$line[8];
			$intPercentComplete	= $line[9];
			$arrAssignedTo		= explode("|",$line[10]);
			$arrDependentOn		= explode("|",$line[11]);
			$intDuration		= 0; // Not sure what this is for
			$intParentId		= $line[13];
			
			echo "<pre>";
			print_r($line);
			echo "</pre>";
			
			/*try {
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
				echo "Task # ".$taskId." created<br>";
			} catch (Exception $e) {
				echo $e->getMessage();
			}*/
		}
		// close file
		fclose($filehandle);

		// logout
		doLogout($soapClient,$_SESSION['sessionId']);
	}
?>