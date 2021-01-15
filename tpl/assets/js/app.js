/* Burger Menu Controller */
const burgerMenu = () => {
    const burger = document.querySelector('.mobileBurger');
    const navbar = document.querySelector('nav');
    const nav = document.querySelector('nav > ul');
    const navSearch =  document.getElementById("liveInput");
   
    const navLinks = document.querySelectorAll('nav > ul > li.link > span, nav > ul > li > form > button > span, li.dropdown-content');
    const body = document.querySelector('body');

    burger.addEventListener('click', ()=> {

        nav.classList.toggle('active');
        navbar.classList.toggle('active');
        body.classList.toggle('active');
        navSearch.value = "";
        document.getElementById("livesearch").innerHTML="";

        document.getElementById("livesearch").style.border="0px";

        document.getElementById("liveInput").style.borderBottomLeftRadius = "20px";
        document.getElementById("liveInput").style.borderBottomRightRadius = "20px";
        
        navLinks.forEach((link, index) =>{
            if(link.style.animation) {
                link.style.animation = '';
            }
            else {
                link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
            }
        });

            burger.classList.toggle('active');

    });

}

const menuLinks = () => {
    const menuLink = document.querySelectorAll(".link");
    menuLink.forEach((link, index) => {
        link.addEventListener('click', () => {
            window.location.href = menuLink[index].dataset.link;
        });
    });
}

const thumbNails = () => {
    const elements = document.querySelectorAll(".videoThumb");
    elements.forEach((link, index) => {

        const thumbImage = document.querySelector("[data-video-thumb='"+link.dataset.video+"']");
        link.addEventListener('mouseenter', ()=> {
            thumbImage.src = "/uploads/previews/" + link.dataset.video + ".gif";
        });
        link.addEventListener('mouseleave', ()=>{
            thumbImage.src = "/uploads/thumbnails/" + link.dataset.video + ".png";
        });
    });
}

const getCookieValue = (a) => {
    var b = document.cookie.match('(^|;)\\s*' + a + '\\s*=\\s*([^;]+)');
    return b ? b.pop() : '';
}

document.addEventListener("DOMContentLoaded", function(){

    /* Canvas Controller */
    updateUI();

    window.onresize = function(event) {
        updateUI();
    };

    (function animloop(){
            requestAnimFrame(animloop);
            gradient.updateStops();
            gradient.draw();
    })(); 

    menuLinks();
    burgerMenu();
    thumbNails();
 
});