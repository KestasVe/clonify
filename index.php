<?php 
	include('src/includedFiles.php'); 
?>

<h1 class="content-heading">You Might Also Like</h1>
<div class="grid-view-container">
	<?php
		$albumQuery = mysqli_query($con, 'SELECT * from albums ORDER BY RAND() LIMIT 10');
		while ($row = mysqli_fetch_array($albumQuery)) {
			echo "<div class='grid-view-item'>
					<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
						<img src='" . $row['artworkPath'] . "'>
						<div class='grid-view-info'>"
							. $row['title'] . 
						"</div>
					</span>
				</div>";
		}
	?>
</div>

					