const media = document.querySelector('video');
const videoID = media.id;

var viewUpload = false;
var getCurrentTime = false;
var loadedVideoTime = false;



        if(!getCurrentTime) {

            var getCurrentTimexhttp = new XMLHttpRequest();

            getCurrentTimexhttp.open("POST", window.location.pathname + "/watchprogess", true);
            getCurrentTimexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            getCurrentTimexhttp.send("videoID="+videoID);

            getCurrentTimexhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if(!loadedVideoTime){
                        media.currentTime = this.getResponseHeader("timestamp");
                        loadedVideoTime = true;
                    }
                }
            };

            getCurrentTime = true;
        }
    

window.setInterval(function(){
    if(!media.paused) {
        const currentTime = Math.floor(media.currentTime);
        const videoDuration = Math.floor(media.duration);

        const ratio = currentTime / videoDuration;
        const percentage = Math.floor(100 * ratio);

        if(percentage > 60) {
            if(!viewUpload) {
                console.log("view updated for videoid: " + videoID);
                var viewUpdatexhttp = new XMLHttpRequest();

                viewUpdatexhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                       console.log(this.responseText);
                    }
                };
                viewUpdatexhttp.open("POST", window.location.pathname + "/viewupdate", true);
                viewUpdatexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                viewUpdatexhttp.send("videoID="+videoID);

                viewUpload = true;
            }
        }

        var progressUpdatexhttp = new XMLHttpRequest();

        progressUpdatexhttp.open("POST", window.location.pathname + "/watchupdate", true);
        progressUpdatexhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        progressUpdatexhttp.send("videoID="+videoID +"&timestamp="+currentTime);
    } 
}, 1000);

