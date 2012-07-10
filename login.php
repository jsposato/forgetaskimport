<?php
include_once "functions.php";
	if(isset($_POST['submit'])) {
		
		// get credentials to login
		$user 				= htmlspecialchars($_POST['username']);
		$password 			= htmlspecialchars($_POST['password']);
		//$user 				= 'jsposato';
		//$password 			= 'codiesassy';
		
		// setup the soap client
		$forge_soap_url = "https://forge.ctrip.ufl.edu:443/soap/index.php?wsdl";
		$soapClient = new SoapClient($forge_soap_url);
		
		// check to see if we can login
		$sessionKey = doLogin($soapClient,$user,$password);
		if($sessionKey != null) {
			$_SESSION['sessionId'] = $sessionKey;
			header("Location:import.php");
		} else {
			header("Location:index.php");
		}
	}
?>