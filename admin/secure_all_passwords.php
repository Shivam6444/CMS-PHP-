<?php 
  

	require_once "../includes/db.php";

    $sql = "SELECT * FROM login";
    $retrieve_userlogin = $conn->query($sql);

    if (!$retrieve_userlogin) {
         die ("Error retrieving user info.<br>" . $conn->error . "<br>");
    }

    $password_array = array();
    $user_id_array = array();

    while ($row = $retrieve_userlogin->fetch_assoc()) {
        array_push($password_array, $row['password']);
        array_push($user_id_array, $row['user_id']);
    }

    $num_passwords = sizeof($password_array);

    $i = 0;

    while ($i < $num_passwords) {
    	$preg_match_result = preg_match("/^\\$\d\w\\$\d{1,2}\\$/", $password_array[$i]);

    	if (!$preg_match_result) {

	    	$hashed_password = password_hash($password_array[$i], PASSWORD_DEFAULT);

	 		$sql = "UPDATE login SET ";
			$sql .= "password = '{$hashed_password}' ";
			$sql .= "WHERE user_id = '{$user_id_array[$i]}'";

			$update_password_result = $conn->query($sql);

			if (!$update_password_result) {
				die ("Error updating user.<br>" . $conn->error . "<br>");
			}
		}

		$i++;
   }

   header("Location: profile.php?enc=1");

?>