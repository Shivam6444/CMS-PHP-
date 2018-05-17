<?php
	

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
		<h1 class="display-3">ForceCMS: Blog Post</h1>
	</div>
	</div>

	<div class="container">
	<!-- Row of columns -->
	<div class="row">
		<div class="col-md-9">

			<?php
				if (isset($_GET['p_id'])) {
					/*
					 * If everything seems alright, retrieve the post ID and display the
					 * post here.
					 */
					$post_id = $_GET['p_id'];

					$sql = "SELECT * FROM posts WHERE post_id = $post_id";
					$retrieve_post_result = $conn->query($sql);

					if ($retrieve_post_result->num_rows > 0) {
						while ($row = $retrieve_post_result->fetch_assoc()) {
							$post_title = $row['post_title'];
							$post_author = $row['post_author'];
							$post_date = explode(" ",$row['post_date']);
							$post_image = $row['post_image'];
							$post_content = create_paragraphs_from_DBtext($row['post_content']);
				?>

				<article>
					<h2><a href=""><?php echo $post_title; ?></a></h2>

					<p class="text-secondary small space-top-bottom">
						Posted by <a href=""><?php echo $post_author; ?></a> 
						on <span class="text-primary"><?php echo $post_date[0]; ?></span> 
						at <span class="text-primary"><?php echo $post_date[1]; ?></span>
					</p>

					<?php 
						//Show the post image only if one has been set.
						if ($post_image != "") {
					?>
							<img class="col-md-6 col-no-left-padding" src="images/<?php echo $post_image; ?>" alt="">
							<hr class="space-top-bottom">
					<?php
						}
					?>

					<p><?php echo $post_content; ?></p>
				</article>
				<hr class="space-top-bottom">


				<?php
						//Include the file that will display comments and allow users to submit comments.
						include "includes/get_show_comments.php";

						}	//Closing the posts while loop here.
					}
					else {
						echo "<p>No posts to show!</p>";
					}
				}
				else {
					/*
					 * If someone tries to access posts.php without specifying a post ID,
					 * they must not be allowed access to the page. So, we redirect them
					 * to the home page.
					 */

					echo "<p>No posts to show!</p>";
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