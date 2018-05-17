<?php


	$db_host = "db.cs.dal.ca";
	$db_username = "smahajan";
	$db_password = "B00785121";
	$db_name = "smahajan_1";

	$conn = new mysqli ($db_host, $db_username, $db_password, $db_name);

	if ($conn->connect_error) {
		die ("Error connecting to the DB.<br>" . $db->connect_error);
	}
	/* For debug purposes only. This is otherwise not required.
	else {
		echo "Connected!";
	}
	*/
?>