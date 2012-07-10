<?php 
/**
 * @author John Sposato
 */
function isLoggedIn($sessionKey = null) {
	$returnVal = false;
	if(isset($sessionKey) && $sessionKey != null) {
		$returnVal = true;
	}
	return $returnVal;
}

/**
 * @author John Sposato
 */
function doLogin($soapObj,$user,$password) {
	try {
		$sessionKey = $soapObj->login($user,$password);
		//print "Login response: ".$sessionKey."<br>";
		return $sessionKey;		
	} catch (Exception $e) {
		echo "Exception: ".$e->getMessage();
		$sessionKey = null;
	}
}

/**
 * @author John Sposato
 */
function doLogout($soapObj,$sessionKey) {
	try {
		$result = $soapObj->logout($sessionKey);
		print "Logout response: ".$result."<br>";
	} catch (Exception $e) {
		echo "Exception on Logout: ".$e->getMessage();
	}
}

function getUploadFile($files) {
// 	print_r($files);
	// Configuration - Your Options
    $allowed_filetypes = array('.csv','.txt'); // These will be the types of file that will pass the validation.
    $max_filesize = 1024000; // Maximum filesize in BYTES (currently 1MB).
    $upload_path = './files/'; // The place the files will be uploaded to (currently a 'files' directory).
 
	$filename = $files['file']['name']; // Get the name of the file (including file extension).
	$ext = substr($filename, strpos($filename,'.'), strlen($filename)-1); // Get the extension from the filename.
//  	echo "Extension: ".$ext;
	// Check if the filetype is allowed, if not DIE and inform the user.
	if(!in_array($ext,$allowed_filetypes)) {
    	die('The file you attempted to upload is not allowed.');
	}
	
    // Now check the filesize, if it is too large then DIE and inform the user.
    if(filesize($files['file']['tmp_name']) > $max_filesize) {
    	die('The file you attempted to upload is too large.');
    }
 
    // Check if we can upload to the specified path, if not DIE and inform the user.
    if(!is_writable($upload_path)) {
    	die('You cannot upload to the specified directory, please CHMOD it to 777.');
    }
 
    // Upload the file to your specified path.
    if(move_uploaded_file($files['file']['tmp_name'],$upload_path . $filename)) {
//     	echo 'Your file upload was successful, view the file <a href="' . $upload_path . $filename . '" title="Your File">here</a>'; // It worked.
    } else {
    	echo 'There was an error during the file upload.  Please try again.'; // It failed :(.
    }
	return $upload_path.$filename;
}
?>