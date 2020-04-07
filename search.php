<?php
include('src/includedFiles.php');

if(isset($_GET['term'])) {
    $term = htmlspecialchars(urldecode($_GET['term']));
} else {
    $term = "";
}
?>

<div class="search-container">
    <h4>Search for an artist, album or song</h4>
    <input type="text" class="search-input" value="<?php echo $term; ?>" placeholder="Start typing..." onfocus="moveCursorToEnd(this)">
</div>

<script>
    $(".search-input").focus();

    function moveCursorToEnd(el) {
        if (typeof el.selectionStart == "number") {
            el.selectionStart = el.selectionEnd = el.value.length;
        }
    }

    $(function() {
        $(".search-input").keyup(function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                let val = $(".search-input").val();
                openPage("search.php?term=" + val);
            }, 2000);
        });
    });
</script>

<?php
    if($term == "") exit();
?>

<div class="tracks-container border-bottom">
    <h2>SONGS</h2>
    <ul class="tracklist">
        <?php
            $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");

            if(mysqli_num_rows($songsQuery) == 0) {
                echo "<span class='no-results'>No songs found matching " . $term . "</span>";
            }

            $songIdArray = array();
            $i = 1;
            while($row = mysqli_fetch_array($songsQuery)) {
                if($i > 15) {
                    break;
                }
                array_push($songIdArray, $row['id']);

                $albumSong = new Song($con, $row['id']);
                $songArtist = $albumSong->getArtist();
                echo "<li class='tracklist-row'>
                        <div class='track-count'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $albumSong->getId() . "\", tempPlaylist, true)'>
                            <span class='track-number'>$i</span>
                        </div>
                        <div class='track-info'>
                            <span class='track-name'>" . $albumSong->getTitle() . "</span>
                            <span class='artist-name'>" . $songArtist->getName() . "</span>
                        </div>
                        <div class='track-options'>
                            <input class='songId' type='hidden' value='" . $albumSong->getId() . "'>
                            <img class='options' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                        </div>
                        <div class='track-duration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                    </li>";
                $i++;
            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>
<div class="artists-container border-bottom">
    <h2>ARTISTS</h2>
    <?php
        $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");
        if(mysqli_num_rows($artistsQuery) == 0) {
            echo "<span class='no-results'>No artists found matching " . $term . "</span>";
        }

        while($row = mysqli_fetch_array($artistsQuery)) {
            $artistFound = new Artist($con, $row['id']);
            echo "<div class='search-result-row'>
                    <div class='artist-name'>
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() . "\")'>
                            " . $artistFound->getName() . "
                        </span>
                    </div>
                </div>";
        }
    ?>
</div>
<div class="grid-view-container">
    <h2>ALBUMS</h2>
	<?php
        $albumQuery = mysqli_query($con, "SELECT * from albums WHERE title LIKE '$term%' LIMIT 10");
        if(mysqli_num_rows($albumQuery) == 0) {
            echo "<span class='no-results'>No albums found matching " . $term . "</span>";
        }
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
<nav class="options-menu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>