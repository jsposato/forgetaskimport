<?php 
	ini_set("display_errors","1");
	
	if(isset($_POST['submit'])) {
		$file 		= $_POST['importFile'];
		$user 		= htmlspecialchars($_POST['username']);
		$password 	= htmlspecialchars($_POST['password']);
		
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
	} else {
		echo "form not submitted";
	}
?>