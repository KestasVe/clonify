<?php

    $songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
    $resultArray = array();

    while($row = mysqli_fetch_array($songQuery)) {
        array_push($resultArray, $row['id']);
    }

    $jsonArray = json_encode($resultArray);

?>
<script>
    $(document).ready(function() {
        let newPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        updateVolumeProgressBar(audioElement.audio);

        $(".now-playing-bar-container").on("mousedown touchstart mousemove touchmove", function(e) {
            e.preventDefault();
        });

        $(".playback-bar .progress-bar").mousedown(function() {
            mouseDown = true;
        });

        $(".playback-bar .progress-bar").mousemove(function(e) {
            if(mouseDown) {
                timeFromOffset(e, this);
            }
        });

        $(".playback-bar .progress-bar").mouseup(function(e) {
            timeFromOffset(e, this);
        });

        $(".volume-bar .progress-bar").mousedown(function() {
            mouseDown = true;
        });

        $(".volume-bar .progress-bar").mousemove(function(e) {
            if(mouseDown) {
                let percentage = e.offsetX / $(this).width();
                if (percentage >= 0 && percentage <= 1) {
                audioElement.audio.volume = percentage;
            }
            }
        });

        $(".volume-bar .progress-bar").mouseup(function(e) {
            let percentage = e.offsetX / $(this).width();
            if (percentage >= 0 && percentage <= 1) {
                audioElement.audio.volume = percentage;
            }
        });

        $(document).mouseup(function() {
            mouseDown = false;
        });
    });

    function timeFromOffset(mouse, progressBar) {
        let percentage = mouse.offsetX / $(progressBar).width() * 100;
        let seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);
    }

    function nextSong() {
        if (repeat == true) {
            audioElement.setTime(0);
            playSong();
            return;
        }
        if (currentIndex == currentPlaylist.length - 1) {
            currentIndex = 0;
        } else {
            currentIndex++;
        }
        let trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];

        setTrack(trackToPlay, currentPlaylist, true);
    }

    function prevSong() {
        if (audioElement.audio.currentTime >= 3 || currentIndex == 0) {
            audioElement.setTime(0);
        } else {
            currentIndex = currentIndex - 1;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }
    }

    function setRepeat() {
        repeat = !repeat;
        let imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".control-button.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    function setMute() {
        audioElement.audio.muted = !audioElement.audio.muted;
        let imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
        $(".control-button.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function setShuffle() {
        shuffle = !shuffle;
        let imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".control-button.shuffle img").attr("src", "assets/images/icons/" + imageName);

        if (shuffle == true) {
            shuffleArray(shufflePlaylist);
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
    }

    function shuffleArray(a) {
        let j, x, i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i - 1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }



    function setTrack(trackId, newPlaylist, play) {

        if(newPlaylist != currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffleArray(shufflePlaylist);
        }

        if (shuffle == true) {
            currentIndex = shufflePlaylist.indexOf(trackId);
        } else {
            currentIndex = currentPlaylist.indexOf(trackId);
        }

        
        pauseSong();

        $.post("src/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {
            let track = JSON.parse(data);
            $(".track-info .track-name span").text(track.title);

            $.post("src/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data) {
                let artist = JSON.parse(data);
                $(".track-info .artist-name span").text(artist.name);
                $(".track-info .artist-name span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
            });

            $.post("src/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data) {
                let album = JSON.parse(data);
                $(".content .album-link img").attr("src", album.artworkPath);
                $(".content .album-link img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
                $(".track-info .track-name span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            });

            audioElement.setTrack(track);

            if(play) {
                playSong();
            }
        });
    }

    function playSong() {
        if(audioElement.audio.currentTime == 0) {
            $.post("src/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
        }
        

        $(".control-button.play").hide();
        $(".control-button.pause").show();
        audioElement.play();
    }

    function pauseSong() {
        $(".control-button.play").show();
        $(".control-button.pause").hide();
        audioElement.pause();
    }
</script>
<div class="now-playing-bar-container">
    <div class="now-playing-bar">
        <div class="now-playing-left">
            <div class="content">
                <span class="album-link">
                    <img role="link" tabindex="0" src="" class="album-art-work" alt="Album">
                </span>
                <div class="track-info">
                    <span class="track-name">
                        <span role="link" tabindex="0"></span>
                    </span>
                    <span class="artist-name">
                        <span role="link" tabindex="0"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="now-playing-center">
            <div class="content player-controls">
                <div class="buttons">
                    <button class="control-button shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>
                    <button class="control-button previous" title="Previous button" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>
                    <button class="control-button play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>
                    <button class="control-button pause" title="Pause button" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>
                    <button class="control-button next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>
                    <button class="control-button repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>
                </div>
                <div class="playback-bar">
                    <span class="progress-time current">0.00</span>
                    <div class="progress-bar">
                        <div class="progress-bar-bg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progress-time remaining">0.00</span>
                </div>
            </div>
        </div>
        <div class="now-playing-right">
            <div class="volume-bar">
                <button class="control-button volume" title="Volume button" onclick="setMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume">
                </button>
                <div class="progress-bar">
                    <div class="progress-bar-bg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>