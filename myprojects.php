<?php 
	include_once 'functions.php';
	session_start();
	
	echo "<pre>";
	print_r($_SERVER);
	echo "</pre>";
	
	// setup the soap client
	$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
	$soapClient = new SoapClient($forge_soap_url);
	
	$groupNames = array("ICHP Texas WIN Project","UF-FSU HRA Study");
	$groups = $soapClient->getGroupsByName($_SESSION['sessionId'],$groupNames);
?>