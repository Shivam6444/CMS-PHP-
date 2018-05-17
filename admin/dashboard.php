<?php
	include "includes/header.php";
	if ($_SESSION['role'] == 2) {//IF THE USER IS SUBSCRIBER
		$url = "index.php";
		header("Location:".$url);
	}
	
	$dateym = date("Y-m");
	$day = date("d") - 1;
	$time = date("H:m:s");
	$date = $dateym."-0".$day." ".$time;
	//FOR ADMIN.
	if ($_SESSION['role'] == 0) {
		$sql = "SELECT Count(post_id) as c from posts where post_date >= '{$date}'";
		$output = $conn->query($sql);
		$outArr = $output->fetch_assoc();
		$no_of_posts = $outArr['c'];
		$sql = "SELECT COUNT(comment_id) as c from comments where comment_date >= '{$date}'";
		$output = $conn->query($sql);
		$outArr = $output->fetch_assoc();
		$no_of_comments = $outArr['c'];


		$sql = "SELECT COUNT(user_id) as c from users where user_date >= '{$date}'";
		$output = $conn->query($sql);
		$outArr = $output->fetch_assoc();
		$no_of_users = $outArr['c'];

	}
	//FOR AUTHOR
	elseif ($_SESSION['role'] == 1) {
		$name = $_SESSION['username'];
		$sql = "SELECT Count(post_id) as c from posts where post_date >= '{$date}' AND post_author = '$name'";
		
		$output = $conn->query($sql);
		$outArr = $output->fetch_assoc();
		$no_of_posts = $outArr['c'];
		$sql = "SELECT COUNT(comment_id) as c from comments where comment_date >= '{$date}' AND comment_post_id IN (Select post_id from posts where post_author = '$name')";
		
		$output = $conn->query($sql);
		$outArr = $output->fetch_assoc();
		$no_of_comments = $outArr['c'];
	}
	
?>

<div class="jumbotron">
	<div class="container">
		<h1 class="display-3">ForceCMS Admin: Dashboard</h1>
	</div>
</div>
<!-- SOURCE::https://getbootstrap.com/docs/4.0/components/card/ -->
<div class="card-group">
	<div class="card">
    	<div class="card-body">
      		<h5 class="card-title">Recent Posts</h5>
      		<p class="card-text"><a href="view_posts.php"><?php echo "$no_of_posts"; ?> post(s)</a> have been submitted in last 24 hours.</p>
	       <!--  <p class="card-text"><small class="text-muted">Last updated <?php echo "$showTime"; ?> mins ago</small></p> -->
    	</div>
  </div>

  <div class="card">

  	<div class="card-body">
    	<h5 class="card-title">Recent Comments</h5>
    	<p class="card-text"><a href="comments.php"><?php echo "$no_of_comments"; ?> comment(s)</a> have been submitted in last 24 hours.</p>
 
    </div>
  </div>
<?php
	if ($_SESSION['role'] == 0) {
		
?>
  <div class="card">
    <div class="card-body">
    	<h5 class="card-title">Recent Users</h5>
    	<p class="card-text"><a href="comments.php"><?php echo "$no_of_users"; ?> user(s)</a> have been added or registered in last 24 hours.</p>
    	<p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
    </div>
  </div>
<?php
}

?>

</div>

<?php
	include "includes/footer.php";

?>