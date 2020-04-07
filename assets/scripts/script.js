var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click) {
    let target = $(click.target);
    if(!target.hasClass("item") && !target.hasClass("options")) {
        hideOptionsMenu();
    }
});

$(window).scroll(function() {
    hideOptionsMenu();
});

$(document).on("change", "select.playlist", function() {
    let select = $(this);
    let playlistId = select.val();
    let songId = select.prev(".songId").val();

    $.post("src/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId }).done(function(error) {
        if(error != "") {
            alert(error);
            return;
        }
        hideOptionsMenu();
        select.val("");
    })
});

function logout() {
    $.post("src/handlers/ajax/logout.php", function() {
        location.reload();
    })
}

function updateEmail(emailClass) {
    let emailValue = $("." + emailClass).val();
    $.post("src/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn }).done(function(response) {
        $("." + emailClass).nextAll(".message").text(response);
    })
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    let oldPasword = $("." + oldPasswordClass).val();
    let newPasword1 = $("." + newPasswordClass1).val();
    let newPasword2 = $("." + newPasswordClass2).val();
    $.post("src/handlers/ajax/updatePassword.php", { 
        oldPasword: oldPasword, 
        newPasword1: newPasword1,
        newPasword2: newPasword2,
        username: userLoggedIn
    }).done(function(response) {
        $("." + oldPasswordClass).nextAll(".message").text(response);
    })
}

function openPage(url) {
    if(timer != null) {
        clearTimeout(timer);
    }
    if(url.indexOf("?") == -1) {
        url = url + "?";
    }
    let encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $(".main-content").load(encodedUrl);
    $("body").scrollTop(0);
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
    let songId = $(button).prevAll(".songId").val();
    $.post("src/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId }).done(function(error) {
        if(error != "") {
            alert(error);
            return;
        }
        openPage("playlist.php?id=" + playlistId);
    });
}

function createPlaylist() {
    let popup = prompt("Please enter the name of your playlist");
    if(popup != null) {
        $.post("src/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn }).done(function(error) {
            if(error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}

function deletePlaylist(playlistId) {
    let msg = confirm("Ar you sure you want to delete this playlist?");
    if(msg) {
        $.post("src/handlers/ajax/deletePlaylist.php", { playlistId: playlistId }).done(function(error) {
            if(error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}

function hideOptionsMenu() {
    let menu = $(".options-menu");
    if(menu.css("display") != "none") {
        menu.css("display", "none");
    }
}

function showOptionsMenu(button) {
    let songId = $(button).prevAll(".songId").val();
    let menu = $(".options-menu");
    let menuWidth = menu.width();
    menu.find(".songId").val(songId);

    let scrollTop = $(window).scrollTop();
    let elementOffset = $(button).offset().top;
    let top = elementOffset - scrollTop;
    let left = $(button).position().left;
    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline" })
}

function formatTime(duration) {
    let time = Math.round(duration);
    let minutes = Math.floor(time / 60);
    let seconds = time - minutes * 60;
    let extraZero = (seconds < 10) ? "0" : "";
    
    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    $(".progress-time.current").text(formatTime(audio.currentTime));
    $(".progress-time.remaining").text(formatTime(audio.duration - audio.currentTime));

    let progress = audio.currentTime / audio.duration * 100;
    $(".playback-bar .progress").css("width", progress + "%");
}

function updateVolumeProgressBar(audio) {
    let volume = audio.volume * 100;
    $(".volume-bar .progress").css("width", volume + "%");
}

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio() {
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    this.audio.addEventListener('canplay', function() {
        let duration = formatTime(this.duration);
        $('.progress-time.remaining').text(duration);
    });

    this.audio.addEventListener('timeupdate', function() {
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    this.audio.addEventListener('ended', function() {
        nextSong();
    });

    this.audio.addEventListener('volumechange', function() {
        updateVolumeProgressBar(this);
    });

    this.setTrack = function(track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;
    }

    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }

    this.setTime = function(seconds) {
        this.audio.currentTime = seconds;
    }
}