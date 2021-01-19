document.addEventListener("DOMContentLoaded", function(){
    const vader = document.querySelector(".vader");
    var audio = document.getElementsByTagName("audio")[0];

    vader.addEventListener('click', ()=> {
        audio.muted = false;
    });

    vader.addEventListener('mouseenter', ()=> {
        audio.currentTime = 0;
        audio.play();
    });
    vader.addEventListener('mouseleave', ()=>{
        audio.pause();
    });
    
});