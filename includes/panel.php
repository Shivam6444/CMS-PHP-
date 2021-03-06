<?php
	/*
	 * @file: panel.php
	 * @author: Raghav V. Sampangi
	 * @year: 2018
	 * @desc: INFX 2670 (Winter 2018): This is part of the solution for the CMS assignment series (A1-A7).
	 * @attribution: This template is named "Jumbotron".
	 * 				 It was downloaded from the Bootstrap examples website: http://getbootstrap.com/docs/4.0/examples/jumbotron/
	 */
?>

<div class="card">
	<div class="card-header">Search This CMS</div>
	<div class="card-body">
        <form action="search_results.php" method="post">
            <div class="input-group">
                <input name="search_keywords" type="text" class="form-control">
            </div>
            <br>
			<div class="radio">
				<label><input type="radio" name="search_type" value="tags" checked="checked"> Tags</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="search_type" value="categories"> Categories</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="search_type" value="authors"> Authors</label>
			</div>
            <br>
            <div class="input-group">
                <input type="submit" name="search" value="Search" class="btn btn-primary">
            </div>
        </form>
	</div>
</div>

<div class="card space-top-bottom">
	<div class="card-header">Login to your account</div>
	<div class="card-body">
	<?php 
	/*
		UPDATE: This panel will now display the login form only for the following two cases:
		(a) Default -- there has not been an attempt to login by the user.
		(b) Error in login -- user has supplied the website with a wrong username or password (error printed below form).

		The script to process login is in header.php and functions.php

		If there is no error in login, the CMS will log the user into the system and print a "welcome" message instead
		of the form.
	*/

		if (isset($_SESSION['loggedIn'])) {
			$user_firstname = $_SESSION['firstname'];
			echo "<p class='text-primary'>Welcome, $user_firstname!</p>";

			if (isset($_SESSION['last_access'])) {
				echo "<p>You last accessed this site on: " . $_SESSION['last_access'] . "</p>";
			}
			else {
				echo "<p>This is the first time you've logged in!</p>";
			}

			//This check displays a message if a subscriber attempts to use
			// absolute URL paths to access a resource (e.g. admin/categories.php), which
			// they cannot access through the navigation menu.
			if (isset($_GET['unauthAccess']) && isset($_GET['subAccess'])) {
				echo "<p class='small text-danger space-top-bottom'><em>** You do not have access to this resource.</p>";
			}

		}
		else {
			include "includes/loginform.php";

			if (isset($_GET['loginError'])) {
				echo "<p class='small text-danger space-top-bottom'><em>** Invalid username or password!</em></p>";
			}
			elseif (isset($_GET['unauthAccess'])) {
				echo "<p class='small text-danger space-top-bottom'><em>** You need to be logged in to access this page.</p>";
			}
		}
	?>
	</div>
</div>
<div class="card space-top-bottom">
	<div class="card-header">Login Information- FOR TESTING PURPOSES</div>
	<div class="card-body">
		<p class="text-secondary small">Admin: <br>username:rey<br>password: r3y<br>Author:<br>username: luke <br>password: luke<br>Subscriber:<br>username: sub<br>password: subi<br></p>
	</div>
</div>
<div class="card space-top-bottom">
	<div class="card-header">Report Issues</div>
	<div class="card-body">
		<p class="text-secondary small">Is something on this website not working? <a href="report.php">Click here to report issues</a>.</p>
	</div>
</div>