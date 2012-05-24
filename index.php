<?php 
	if(isset($_POST['submit'])) {
		$file 		= $_POST['importFile'];
		$user 		= htmlspecialchars($_POST['username']);
		$password 	= htmlspecialchars($_POST['password']);
		
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
	}
?>