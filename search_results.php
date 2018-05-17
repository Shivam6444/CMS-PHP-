<?php 
	ob_start();

	

	include "includes/header.php";
?>
<main role="main">
	<!-- "The HTML <main> element represents the main content of the <body> of a document, portion of a document, or application.
		The main content area consists of content that is directly related to, or expands upon the central topic of, a document or the central functionality of an application."
		For more information: https://developer.mozilla.org/en-US/docs/Web/HTML/Element/main 
	-->

	<!-- Main jumbotron for a primary marketing message or call to action -->
	<div class="jumbotron">
	<div class="container">
		<h1 class="display-3">ForceCMS: Search</h1>
	</div>
	</div>

	<div class="container">
		<div class="row">
			<div class="col-md-9">

		<?php

			$sql = "";
			if (isset($_GET['q']) && ($_GET['q'] == "more_cat")) {
				echo "<p class='text-danger'>* Please refine your search query. There may be more than one category by this name.</p>";
			}
			elseif (isset($_GET['q']) && ($_GET['q'] == "no_cat")) {
				echo "<p class='text-danger'>No posts to show. There may be an error with the category you specified.</p>";
			}

			if (isset($_POST['search'])) {
	            $search_keywords = sanitize($_POST['search_keywords']);
	            $search_type = sanitize($_POST['search_type']);

	            if ($search_type == "tags") {
					$sql = "SELECT * FROM posts WHERE post_tags LIKE '%$search_keywords%' AND post_status = 'published'";
				}
				elseif ($search_type == "categories") {
					$sql_cat = "SELECT * FROM category WHERE cat_title LIKE '%$search_keywords%'";
					$retrieve_cat_result = $conn->query($sql_cat);

					if ($retrieve_cat_result->num_rows == 1) {
						$row = $retrieve_cat_result->fetch_assoc();
						$category_id = $row['cat_id'];

						$sql = "SELECT * FROM posts WHERE post_cat_id = $category_id AND post_status = 'published'";
					}
					elseif ($retrieve_cat_result->num_rows > 1) {
						header("Location: search_results.php?q=more_cat");
						die();
					}
					else {
						//Error
						header("Location: search_results.php?q=no_cat");
						die();
					}
				}
				elseif ($search_type == "authors") {
					$sql = "SELECT * FROM posts WHERE post_author LIKE '%$search_keywords%' AND post_status = 'published'";
				}

				$retrieve_post_result = $conn->query($sql);

				if ($retrieve_post_result->num_rows > 0) {
					while ($row = $retrieve_post_result->fetch_assoc()) {
						$post_id = $row['post_id'];
						$post_title = $row['post_title'];
						$post_author = $row['post_author'];
						$post_date = explode(" ",$row['post_date']);
						$post_image = $row['post_image'];
						$post_content = create_paragraphs_from_DBtext($row['post_content']);
						$post_status = $row['post_status'];


			?>


			<h2>
				<a href="posts.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
			</h2>
			<p class="lead">
				by <a href="#"><?php echo $post_author; ?></a>
			</p>
			<p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date[0]; ?></p>
			<hr>
				<?php 
					//Show the post image only if one has been set.
					if ($post_image != "") {
				?>
					<img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
				<?php
					}
				?>

			<hr>
			<p><?php echo $post_content; ?></p>

			<hr>



			<?php
					}	//Closing the posts while loop here.
				}
			}
			else {
				if (!isset($_GET['q'])){
					echo "<p class='text-danger'>No posts to show.</p>";
				}
			}
			?>

			</div>

			<div class="col-md-3">
				<?php 
					/* Panel containing the login form and report issues link */
					include "includes/panel.php"; 
				?>
			</div>
		</div>

		<hr>

		</div> <!-- /end main container -->

	</main>
		
<?php include "includes/footer.php"; ?>
<?php
	ob_flush();
?>