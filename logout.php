<?php 
	include_once 'functions.php';
	session_start();

	// setup the soap client
	$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
	$soapClient = new SoapClient($forge_soap_url);

	// logout
	$result = doLogout($soapClient,$_SESSION['sessionId']);
	$_SESSION['flashMessage'] = $result;
	
	header('Location:index.php');
?>