<?php
	/*
	//GOT SOME IDEA FROM: 
	//https://stackoverflow.com/questions/19605150/regex-for-password-must-contain-at-least-eight-characters-at-least-one-number-a

	 */


	include "includes/header.php"; 
		$date = date("Y-m-d h:i:s");
	//Include the code to submit user data.
?>
<?php
	/*THIS IS THE SECTION WHERE I AM CHECKING IF MY PASSWORD IS CORRECT*/
	if (isset($_POST['submit'])) {

		$pass = $_POST['password'];
		$repass = $_POST['repassword'];
		$pattern = <<<_PATTERN
/(^(?=.*[a-zA-Z])(?=.*\d))[A-Za-z\d]{8,}$/
_PATTERN;
		//GOT SOME IDEA FROM: 
		//https://stackoverflow.com/questions/19605150/regex-for-password-must-contain-at-least-eight-characters-at-least-one-number-a
		$patternAlert = <<<_DOCSTRING
<script type='text/javascript'>
	alert('Your Password Does not match the pattern.');
</script>
_DOCSTRING;
		$notMatched = <<<_DOCSTRING
<script type='text/javascript'>
	alert('Your Password Does not match with the other password.');
</script>
_DOCSTRING;
		$url = "register.php";
		$result = preg_match($pattern, $pass);
		if (!($result)) {
			echo "$patternAlert";
		}
		
		if ($pass != $repass) {
			echo "$notMatched";
		}

		if ($pass == $repass && $result) {
			//Create these entries in the login and user table..
			//$sql = "Select ";
			$user_firstname = sanitize($_POST['user_firstname']);
			$user_lastname = sanitize($_POST['user_lastname']);
			$user_email = sanitize($_POST['user_email']);
			$user_role = $_POST['user_role'];
			$username = sanitize($_POST['username']);
			$user_date = $_POST['date'];

//#############################THIS IS TAKING IMAGE AS AN INPUT###########################//

			$user_image = $_FILES['user_image']['name'];
			$user_image_temp = $_FILES['user_image']['tmp_name'];
			$user_image_filesize = $_FILES['user_image']['size'];


					if($_FILES['user_image']['name'] != "") { 
			/*
			 * This section of the code manages image uploads. As discussed in class,
			 * we check if the file is of a specified type, and within the allowed file-size.
			 *
			 * @attribution: This image upload section is based on the logic available on:
			 * https://www.w3schools.com/php/php_file_upload.asp
			 */
			$user_image = $_FILES['user_image']['name'];
			$user_image_temp = $_FILES['user_image']['tmp_name'];
			$user_image_filesize = $_FILES['user_image']['size'];

			$target_file = "../../images/" . $user_image;

			$post_image_filetype = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			$goodToUpload = true;

			if ($post_image_filetype != 'jpg' && $post_image_filetype != 'png') {
				$goodToUpload = false;
			}

			if ($user_image_filesize < TWO_MEGA_BYTES && $goodToUpload === true) {
				//Upload the image.
				move_uploaded_file($user_image_temp, $target_file);
			}
		} 
		else { 
			//Otherwise, the user has not set any post image.
			$user_image = "";
		} 
//##################################END------END#######################################################//
		$sql = "INSERT INTO users(user_firstname, user_lastname, user_email, user_address, user_phone, user_role, user_image, user_date) ";
		$sql .= "VALUES('$user_firstname','$user_lastname','$user_email',' ',' ',$user_role,'$user_image', '$user_date')";

		$submit_user_result = $conn->query($sql);

		if (!$submit_user_result) {
			die ("Error creating user.<br>" . $conn->error . "<br>");
		}

		$user_id = $conn->insert_id;

		//Get the username and password information from $_POST
		$username = sanitize($_POST['username']);
		$password = sanitize($_POST['password']);

		//Hash the password
		$password = password_hash($password, PASSWORD_DEFAULT);

		//Generate random salt value.
		$random_salt = rand();

		//Create login entry for the user.
		//Remember that there is a foreign key relationship between the user and login tables.

		$sql = "INSERT INTO login(user_id, username, password, random_salt) ";
		$sql .= "VALUES({$user_id},'{$username}','{$password}',{$random_salt})";
		$submit_userlogin_result = $conn->query($sql);

		if (!$submit_userlogin_result) {
			die ("Error creating user.<br>" . $conn->error . "<br>");
		}
		header("Location:index.php");

	}


	}

?>
	<main role="main">
	<!-- Main jumbotron for a primary marketing message or call to action -->
		<div class="jumbotron">
			<div class="container">
				<h1 class="display-3">ForceCMS Admin: Registration</h1>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<form action="<?php echo $currentFileName; ?>" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="user_firstname" class="control-label">First Name</label>
									<input type="text" class="form-control" name="user_firstname" required>
								</div>
								<div class="form-group">
									<label for="user_lastname">Last Name</label>
									<input type="text" class="form-control" name="user_lastname" required>
								</div>
								<div class="form-group">
									<label for="user_role">User Role</label>
									<select class="form-control" name="user_role" required>

										<option value='1'>Author</option>
										<option value='2'>Subscriber</option>
									</select>
								</div>
							</div>

							<div class="form-group col-lg-6">
								<label for="user_image">Profile Image</label>
								<input type="file" name="user_image">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="user_email">Email</label>
									<input type="text" class="form-control" name="user_email" required>
								</div>
					
								<div class="form-group">
									<label for="username">Username</label>
									<input type="text" class="form-control" name="username" required>
								</div>
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="form-control" name="password" required>
								</div>
								<div class="form-group">
									<label for="password">ReType Password</label>
									<input type="password" class="form-control" name="repassword" required>
								</div>
								<input type="hidden" name="date" value="<?php echo($date) ?>">
							</div>
						</div>
						<button class="btn btn-primary" type="submit" name="submit">Submit</button>
					</form>

				</div>
			</div>
		</div>
	</main>

<?php include "includes/footer.php"; ?>