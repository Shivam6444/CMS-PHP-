<?php 



	//CODE TO CREATE A USER AFTER ADMINISTRATOR SUBMITS THE FORM.

	if(isset($_POST['create_user'])) {
		/*
		 * Retrieve all the form values using the $_POST superglobal.
		 */
		$user_firstname = sanitize($_POST['user_firstname']);
		$user_lastname = sanitize($_POST['user_lastname']);
		$user_address = sanitize($_POST['user_address']);
		$user_phone = sanitize($_POST['user_phone']);
		$user_email = sanitize($_POST['user_email']);
		$user_role = sanitize($_POST['user_role']);
		$user_date = $_POST['date'];

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


		$sql = "INSERT INTO users(user_firstname, user_lastname, user_email, user_address, user_phone, user_role, user_image, user_date) ";
		$sql .= "VALUES('$user_firstname','$user_lastname','$user_email','$user_address','$user_phone',$user_role,'$user_image', '$user_date')";

		$submit_user_result = $conn->query($sql);

		if (!$submit_user_result) {
			die ("Error creating user.<br>" . $conn->error . "<br>");
		}

		//You can use the insert_id parameter of the connection object to 
		// retrieve the last ID inserted to the DB table.
		// |-- So, if you have enabled an ID as an auto-incrementing primary key,
		// |--  and want to use this ID after the insert for another query,
		// |--  you can access this value through the $conn->insert_id; parameter.
		// |-- More information here: https://www.w3schools.com/php/php_mysql_insert_lastid.asp
		// |-- Alternatively, you can submit another "SELECT" query to the previously
		// |--  updated table to retrieve the ID, which is a bit longer.

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

		header("Location: ../view_users.php");
		die();

	}




	//CODE TO UPDATE THE USER AFTER ADMINISTRATOR SUBMITS THE FORM.

	if(isset($_POST['update_user'])) {
		/*
		 * Retrieve all the form values using the $_POST superglobal.
		 */
		$user_firstname = sanitize($_POST['user_firstname']);
		$user_lastname = sanitize($_POST['user_lastname']);
		$user_address = sanitize($_POST['user_address']);
		$user_phone = sanitize($_POST['user_phone']);
		$user_email = sanitize($_POST['user_email']);

		$user_image = $_FILES['user_image']['name'];
		$user_image_temp = $_FILES['user_image']['tmp_name'];
		$user_image_filesize = $_FILES['user_image']['size'];


		$sql = "SELECT user_image FROM users WHERE user_id = {$_GET['u_id']}";
		$check_if_image_exists = $conn->query($sql);

		if (!$check_if_image_exists) {
			die ("<p>Sorry. Your request could not be completed. You can see the detailed error report below:</p>" . $conn->error);
		}

		while ($row = $check_if_image_exists->fetch_assoc()) {
			$image_name_check = $row['user_image'];
		}

		if (($image_name_check == "" && $user_image != "") || 
			($image_name_check != "" && $user_image != "" && $image_name_check != $user_image)) {

			$post_image_temp = $_FILES['user_image']['tmp_name'];
			$post_image_filesize = $_FILES['user_image']['size'];

			$target_file = "../images/" . $user_image;

			$post_image_filetype = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

			$goodToUpload = true;

			if ($post_image_filetype != 'jpg' && $post_image_filetype != 'png') {
				$goodToUpload = false;
			}

			if ($post_image_filesize < TWO_MEGA_BYTES && $goodToUpload === true) {
				//Upload the image.
				move_uploaded_file($post_image_temp, $target_file);
			}
		}
		else {
			$user_image = $image_name_check;
		}



		$sql = "UPDATE users SET ";
		$sql .= "user_firstname = '{$user_firstname}', ";
		$sql .= "user_lastname = '{$user_lastname}', ";
		$sql .= "user_email = '{$user_email}', ";
		$sql .= "user_address = '{$user_address}', ";
		$sql .= "user_phone = '{$user_phone}', ";
		$sql .= "user_image = '{$user_image}' ";
		$sql .= "WHERE user_id = {$_GET['u_id']}";

		$update_post_result = $conn->query($sql);

		if (!$update_post_result) {
			die ("Error updating user.<br>" . $conn->error . "<br>");
		}



		// Remember to check if the password is the same and has not changed.
		// You need to check if the password is the same because otherwise,
		//  you're simply submitting another update query unnecessarily.

		$user_password = sanitize($_POST['password']);

		// Check if the password begins with pattern such as: $2y$10$...
		//  If it does, it means that the password is hashed.
		//  If not, it means that the password has been updated and it needs to be hashed 
		//  and submitted to the login table.

		$preg_match_result = preg_match("/^\\$\d\w\\$\d{1,2}\\$/", $user_password);

    	if (!$preg_match_result) {

	    	$hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

	 		$sql = "UPDATE login SET ";
			$sql .= "password = '{$hashed_password}' ";
			$sql .= "WHERE user_id = '{$_GET['u_id']}'";

			$update_password_result = $conn->query($sql);

			if (!$update_password_result) {
				die ("Error updating user password.<br>" . $conn->error . "<br>");
			}
		}


		header("Location: view_users.php");
		die();
	}

?>