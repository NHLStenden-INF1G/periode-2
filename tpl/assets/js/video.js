const media = document.querySelector('video');
const videoID = media.id;

var viewUpload = false;
var getCurrentTime = false;
var loadedVideoTime = false;

media.onloadeddata = function() {
      if(!getCurrentTime) {
            var location = null;
            var getCurrentTimexhttp = new XMLHttpRequest();
            if(window.location.pathname == '/') {
                location = '/start';
            }
            else {
                location = window.location.pathname;
            }
            getCurrentTimexhttp.open("POST", location + "/watchprogess", true);
            getCurrentTimexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            getCurrentTimexhttp.send("videoID="+videoID);

            getCurrentTimexhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(!loadedVideoTime){
                        media.currentTime = this.getResponseHeader("timestamp");
                        console.log("loaded video <"+ videoID +"> with timestamp: " +  media.currentTime);
                        loadedVideoTime = true;
                    }
                }
            };
            getCurrentTime = true;
        }
  };
      
    

window.setInterval(function(){

    if(!media.paused) {
        const currentTime = Math.floor(media.currentTime);
        const videoDuration = Math.floor(media.duration);

        const ratio = currentTime / videoDuration;
        const percentage = Math.floor(100 * ratio);

        if(percentage > 60) {
            if(!viewUpload) {
                var viewUpdatexhttp = new XMLHttpRequest();

                var location = null;

                if(window.location.pathname == '/') {
                    location = '/start';
                }
                else {
                    location = window.location.pathname;
                }

                viewUpdatexhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log("view updated for videoid: " + videoID);
                    }
                };
                viewUpdatexhttp.open("POST", location + "/viewupdate", true);
                viewUpdatexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                viewUpdatexhttp.send("videoID="+videoID);

                viewUpload = true;
            }
        }

        var progressUpdatexhttp = new XMLHttpRequest();
        var location = null;

        if(window.location.pathname == '/') {
            location = '/start';
        }
        else {
            location = window.location.pathname;
        }
        progressUpdatexhttp.open("POST", location + "/watchupdate", true);
        progressUpdatexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        progressUpdatexhttp.send("videoID="+videoID +"&timestamp="+currentTime);
    } 
}, 1000);

