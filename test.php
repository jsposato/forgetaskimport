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
	$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
	$soapClient = new SoapClient($forge_soap_url);
	
	$testResponse = $soapClient->getArtifacts($_SESSION['sessionId'],
								64,
								188,
								100
							);
	echo "<pre>";
	print_r($testResponse);
	echo "</pre>";
?>
