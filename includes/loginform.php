<?php
	/*
	 * @file: loginform.php
	 * @author: Raghav V. Sampangi
	 * @year: 2018
	 * @desc: INFX 2670 (Winter 2018): This is part of the solution for the CMS assignment series (A1-A7).
	 */
?>
		<form method="post" action="includes/login.php">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="username" class="form-control" id="username" name="username">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control" id="password" name="password">
			</div>
			<p class="text-secondary small"><a href="">Forgot password</a></p>
			<input type="hidden" name="session" value="<?php echo($_SESSION['session_token']); ?>">
			<input type="submit" name="submitLogin" value="Login" class="btn btn-primary">

		</form>
