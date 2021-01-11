/* Burger Menu Controller */
const burgerMenu = () => {
    const burger = document.querySelector('.mobileBurger');
    const navbar = document.querySelector('nav');
    const nav = document.querySelector('nav > ul');
    const navSearch = document.querySelector('nav > .inputSearch');

    const navLinks = document.querySelectorAll('nav > ul > li > span');
    const body = document.querySelector('body');

    burger.addEventListener('click', ()=> {
        nav.classList.toggle('active');
        navbar.classList.toggle('active');
        body.classList.toggle('active');
        navSearch.classList.toggle('active');
        
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
        const thumbImage = document.getElementById('thumb-' + link.id);

        link.addEventListener('mouseenter', ()=> {
            thumbImage.src = "/uploads/previews/" + link.id + ".gif";
        });
        link.addEventListener('mouseleave', ()=>{
            thumbImage.src = "/uploads/thumbnails/" + link.id + ".png";
        });
    });
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