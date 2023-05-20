window.onload = (event) => {

    const menuContainer = document.querySelector(".menu-hamburguesa-container");
    const menuNavbar = document.querySelector(".menu-navbar");


    // opacity: 1;
    // background-color: black;
    // left: 0;
    menuContainer.addEventListener(`click` ,()=>{
        menuNavbar.style.opacity = 1;
        menuNavbar.style.backgroundColor = "black";
        menuNavbar.style.left = 0;
        
    })
    menuContainer.addEventListener(`mouseleave` ,()=>{
        menuNavbar.style.opacity = 0;
        menuNavbar.style.backgroundColor = "null";
        menuNavbar.style.left = "-100%";
        
    })

};