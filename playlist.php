<?php include('src/includedFiles.php'); 

if (isset($_GET['id'])) {
    $playlistId = $_GET['id'];
} else {
    header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());


?>

<div class="entity-info">
    <div class="left-section">
        <div class="playlist-image">
            <img src="assets/images/icons/playlist.png">
        </div>
    </div>
    <div class="right-section">
        <h2> <?php echo $playlist->getName(); ?> </h2>
        <p>By <?php echo $playlist->getOwner(); ?> </p>
        <p><?php echo $playlist->getNumberOfSongs(); ?> songs</p>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">DELETE PLAYLIST</button>
    </div>
</div>
<div class="tracks-container">
    <ul class="tracklist">
        <?php
            $songIdArray = $playlist->getSongsIds();
            $i = 1;
            foreach ($songIdArray as $songId) {
                $playlistSong = new Song($con, $songId);
                $songArtist = $playlistSong->getArtist();
                echo "<li class='tracklist-row'>
                        <div class='track-count'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"" . $playlistSong->getId() . "\", tempPlaylist, true)'>
                            <span class='track-number'>$i</span>
                        </div>
                        <div class='track-info'>
                            <span class='track-name'>" . $playlistSong->getTitle() . "</span>
                            <span class='artist-name'>" . $songArtist->getName() . "</span>
                        </div>
                        <div class='track-options'>
                            <input class='songId' type='hidden' value='" . $playlistSong->getId() . "'>
                            <img class='options' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                        </div>
                        <div class='track-duration'>
                            <span class='duration'>" . $playlistSong->getDuration() . "</span>
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
<nav class="options-menu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">Delete from playlist</div>
</nav>