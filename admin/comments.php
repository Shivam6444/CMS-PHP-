<?php
	include 'includes/header.php';
	if($_SESSION['role'] == 2){
		$url = '../index.php';
		header("Location: ".$url);
	}
//************IF YOU WANT TO APPROVE*********************************//
	if (isset($_GET['app']) ){
		if (isset($_GET['cid'])) {
			$id = $_GET['cid'];
			$queryUp =  "UPDATE comments set comment_status = 'approved'  where comment_id = '$id'";
			if ($conn->query($queryUp) === false) {
				echo "<p style='color:red'>UNABLE TO UPDATE THE TABLE. SOMETHING IS WRONG!!!</p>";
			}
			$url = 'comments.php';
			header("Location: ". $url);
		}
	}
//************IF YOU WANT TO DELETE*********************************//
	if (isset($_GET['del'])) {
		if (isset($_GET['cid'])) {
			$id = $_GET['cid'];
			$queryDel = "DELETE from comments where comment_id = '$id'";
			if ($conn->query($queryDel) === false) {
				echo "<p style='color:red'>UNABLE TO DELETE FROM THE TABLE. SOMETHING IS WRONG!!!</p>";
			}
		}
		$url = 'comments.php';
		header("Location: ". $url);
	}
?>

		<div class="jumbotron">
			<div class="container">
				<h1 class="display-3">ForceCMS Admin: Comments Management</h1>
			</div>
		</div>
	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>ID</th>
				<th>Post Title</th>
				<th>Comment Author</th>
				<th>Comment Email</th>
				<th>Comment Content</th>
				<th>Date</th>
				<th>Status</th>
				<th>Approve/Delete</th>
			</tr>
		</thead>
		<tbody>

		<?php
			if ($_SESSION['role'] == 0) {
				$sql = "SELECT * from comments,posts where posts.post_id = comments.comment_post_id";
			}
			###################FIX THIS##############################################################
			elseif ($_SESSION['role'] == 1) {
				$author = $_SESSION['username'];
				$sql = "SELECT * from comments,posts where posts.post_id = comments.comment_post_id and posts.post_author = '$author'";
			}
			$select_all_posts = $conn->query($sql);

			while ($row = $select_all_posts->fetch_assoc()) {
		

				$comment_id = $row['comment_id'];
				$post_title = $row['post_title'];
				$comment_Author = $row['comment_author'];
				$comment_email = $row['comment_email'];
				$comment_content = $row['comment_content'];
				$date = $row['comment_date'];
				$status = $row['comment_status'];

				echo "<tr>";
				echo "<td>{$comment_id}</td>";
				echo "<td>{$post_title}</td>";
				echo "<td>{$comment_Author}</td>";
				echo "<td>{$comment_email}</td>";
				echo "<td>{$comment_content}</td>";
				echo "<td>{$date}</td>";
				echo "<td>{$status}</td>";
				echo "<td><a href='comments.php?app=1&cid={$comment_id}' class='btn btn-primary'>Approve</a>&nbsp;&nbsp;";
				echo "<a href='comments.php?del=1&cid={$comment_id}' class='btn btn-danger'>Delete</a></td>";
				echo "</tr>";
			}
		?>

		</tbody>
	</table>


<?php
include 'includes/footer.php';

?>