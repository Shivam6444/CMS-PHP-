<?php
	/*
	 * @file: login.php
	 * @author: Raghav V. Sampangi
	 * @year: 2018
	 * @desc: INFX 2670 (Winter 2018): This is part of the solution for the CMS assignment series (A1-A7).
	 */

	session_start();

	require_once "db.php";
	require_once "functions.php";



	/* Process data submitted by login form */

	if (isset($_POST['submitLogin'])) {
		$session1 = $_SESSION['session_token'];
		$session2 = $_POST['session'];
		if ($session1 != $session2) {
			echo "$session1"."<br>"."$session2";
			header("Location: ../index.php?loginError=true");
		}

		$username = $_POST['username'];
		$password = $_POST['password'];

		$username = sanitize($username);
		$password = sanitize($password);

		if ($username != "" || $password != "") {

			$sql = "SELECT * FROM login WHERE username = '{$username}'";
			$login_result = $conn->query($sql);

			if (!$login_result) {
				die($conn->connect_error); 
			}

			if ($login_result->num_rows == 0) {
				header("Location: ../index.php?loginError=true");
			}


			while ($row = $login_result->fetch_assoc()) {
				$db_id = $row['user_id'];
				$db_username = $row['username'];
				$db_password = $row['password'];


				//Compare hashed password stored in the DB with the password submitted
				// by the user, which is hashed by the password_verify() function.
				// Store the boolean result of this comparison in the variable, $password_compare

				$password_compare = password_verify($password, $db_password);

				if ((!$password_compare) && ($password != $db_password)) {
					// In this assignment, there is a possibility that the passwords may not be hashed.
					// So, we will also need to check if the plain text password doesn't match with the password
					// stored in the DB -- only in case the hashed passwords don't match.
					// If both passwords don't match, then, there is an error.
					// This additional check must be removed for A7.

					header("Location: ../index.php?loginError=true");
				}
				else {
					/*
					 * Save previous cookie info, and set new cookie.
					 */
					date_default_timezone_set("America/Halifax"); 

					//Hash the user ID here so that we can store the cookie for multiple users.
					$cookie_enc_user_id = hash('md5', $db_id . $db_username);
					$saved_cookie_name = "cms_access_" . $cookie_enc_user_id;

					if (isset($_COOKIE[$saved_cookie_name])) {
						$_SESSION['last_access'] = $_COOKIE[$saved_cookie_name];
					}

					$cookie_name = "cms_access_" . $cookie_enc_user_id;
					$cookie_data = date("d-M-Y") . ", at " . date("h:i:sa");
					setcookie($cookie_name, $cookie_data, time() + (60*60*24*2), "/");




					$_SESSION['username'] = $db_username;

					$sql1 = "SELECT * FROM users WHERE user_id = '{$db_id}'";
					$users_query_result = $conn->query($sql1);

					while ($row1 = $users_query_result->fetch_assoc()) {
						$db_userrole = $row1['user_role'];
						$db_firstname = $row1['user_firstname'];
						$db_lastname = $row1['user_lastname'];
					}



					$_SESSION['firstname'] = $db_firstname;
					$_SESSION['lastname'] = $db_lastname;
					$_SESSION['role'] = $db_userrole;
					$_SESSION['loggedIn'] = TRUE;




					header("Location: ../index.php");
				}
			}
		}
	}
?>