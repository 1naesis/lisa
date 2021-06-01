function togglePodcat(element){
    let option = element.querySelector('.option-block-podcat')
    option.classList.toggle('active-podcat');
    if(option.classList.contains('active-podcat')){
        element.querySelector('.arrow-drop').innerHTML = '&#9650;';
    }else{
        element.querySelector('.arrow-drop').innerHTML = '&#9660;';
    }
}
function menuOpenClose(obj){
    let menu = obj.closest('.header');
    menu.classList.toggle('minimal');
    let textsMenu = document.querySelectorAll('.menu-text');
    textsMenu.forEach(element => {
        element.classList.toggle('minimal');
    });
    let menuItem = document.querySelectorAll('.menu-item');
    menuItem.forEach(element => {
        element.classList.toggle('minimal');
    });
    let calendar = document.querySelector('.calendar');
    calendar.classList.toggle('minimal');
    let name = document.querySelector('.name');
    name.classList.toggle('minimal');
}
window.onload = function(){
    let clientW = window.innerWidth;
    let clientH = window.innerHeight;
    if(clientW < 770){
        menuOpenClose(document.querySelector('.open-close-menu'));
    }
}
if(document.getElementById('user_avatar')){
    document.getElementById('user_avatar').addEventListener('change', el => {
        document.querySelector('.preview-image-staff>img')
            .setAttribute('src', URL.createObjectURL(el.target.files[0]));
    })
}