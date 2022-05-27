
function burgerMenuShow(){
    const menuBurger = document.querySelector(".menuBurger")
    const fixedMenuBurgerLayout = document.querySelector(".fixedMenuBurgerLayout")
    menuBurger.addEventListener("click", function(){
        menuBurger.classList.toggle("menuBurgerActive")
        fixedMenuBurgerLayout.classList.toggle("fixedMenuBurgerLayoutActive")
    })
    const langsAll = document.querySelectorAll(".LinksLang")
    langsAll.forEach(item =>{
        item.addEventListener("click", function(){
            for(let  i = 0; i < langsAll.length; i++){
                langsAll[i].classList.remove("LinksLangActive")
            } item.classList.add("LinksLangActive")
        })
    })
}


function init(){
    burgerMenuShow()
}



window.addEventListener("load", function(){
    init()
})