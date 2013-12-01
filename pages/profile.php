<?php
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * This profile controller
 *
 * @author Kobe Sun
 *
 */

function file_upload_error_message($error_code) {
	switch ($error_code) { //handles the error created by file upload
		case UPLOAD_ERR_INI_SIZE:
			return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
		case UPLOAD_ERR_FORM_SIZE:
			return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
		case UPLOAD_ERR_PARTIAL:
			return 'The uploaded file was only partially uploaded';
		case UPLOAD_ERR_NO_FILE:
			return 'No file was uploaded';
		case UPLOAD_ERR_NO_TMP_DIR:
			return 'Missing a temporary folder';
		case UPLOAD_ERR_CANT_WRITE:
			return 'Failed to write file to disk';
		case UPLOAD_ERR_EXTENSION:
			return 'File upload stopped by extension';
		default:
			return 'Unknown upload error';
	}
}

?>

<fieldset style="width: 98%;">
	<legend>
		<b>Profile</b>
	</legend>
	<div id="form_container">

	<?php
	$user = retrieveUser(); //user information
	if (is_null($user)) { //if not a user, redirected to home page
		?>
		<script type="text/javascript">
			window.setInterval("run();", 1000);
		</script>
		<?php
		echo "You need to login in order to proceed to this page... Redirecting in <span id=sec>3</span> secs...";
		movePage(301, "index.php", 3);
	} else {
		if (empty($_POST)) {
			include_once("profile.view.php"); //retrieves user profile
		} else {
			$filename = $user->getPic();
			if (empty($_FILES["pic"])) {
				$errMsg = "Unknown upload error, possibly too big or wrong type<br />";
			} elseif ($_FILES["pic"]["error"] == UPLOAD_ERR_OK) { //file uploaded with no error
				// file must be of type gif, jpeg or pjpeg
				if (($_FILES["pic"]["type"] == "image/gif") 
				|| ($_FILES["pic"]["type"] == "image/jpeg")
				|| ($_FILES["pic"]["type"] == "image/pjpeg")) {
					$filename = trim($_POST["username"])."_".$_FILES["pic"]["name"];
					$picPath = "upload/".$filename;
					$oldFile = $user->getPic();
				} else {
					$errMsg = "File type is incorrect<br />";
				}
			} elseif ($_FILES["pic"]["error"] == UPLOAD_ERR_NO_FILE) {
			} else {
				//error code created during file upload by PHP
				$errMsg = file_upload_error_message($_FILES["pic"]["error"]) . "<br />";
			}

			$age = (trim($_POST["age"]) == "") ? "NULL" : trim($_POST["age"]); 
			$car_id = (trim($_POST["car"]) == "") ? "NULL" : CarClass::getCarID(trim($_POST["car"]));
			$new_attrs = array (
				"uid" => $session->user->getUid(),
				"username" => trim($_POST["username"]), 
				"lname" => trim($_POST["lname"]),
			  	"fname" => trim($_POST["fname"]),
				"email" => trim($_POST["email"]),
				"age" => $age,
				"description" => trim($_POST["description"]),
				"telephone" => trim($_POST["telephone"]),
				"car_id" => $car_id,
				"pic" => $filename
			);
			$user = new User($new_attrs);

			$errMsg .= checkForm();
			if($errMsg == "") {
				if ($user->updateUser($new_attrs)) {
					$session->user = $user;
					if ($cookie->check("username")) {
						$cookie->set("username", $session->user->getUsername());
					}

					if ($_FILES["pic"]["error"] == UPLOAD_ERR_OK) {
						if (file_exists("upload/".$oldFile)) {
							chmod("upload/".$oldFile, 0777); //full permission
							unlink("upload/".$oldFile);
						}
						move_uploaded_file($_FILES["pic"]["tmp_name"], $picPath);
					}

					echo '<div id="confirm_notification">Profile is successfully updated...</div><br />';
				}

			} else {
				echo '<div id="error_notification">' . $errMsg . '</div><br />';
			}

			include_once("profile.view.php"); //displays user profile
		}

	}
	?>
	</div>
</fieldset>
