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

let timetables = document.querySelectorAll('.input-company-field-timetable');
timetables.forEach(el => {
    el.addEventListener('click', function (e){
        if(document.querySelector('.modal-timetable')){
            document.querySelector('.modal-timetable').remove();
        }
        let timeBlock = document.createElement('div')
        let html = `
            <div class="modal-timetable-wrapper">
                <div class="modal-timetable-wrap_block">
                    <p class="modal-timetable-title">Часы</p>
                    <div class="modal-timetable-wrapper-grid modal-timetable-wrapper-grid-h" data-type="h">
                        <p>0</p><p>1</p><p>2</p><p>3</p>
                        <p>4</p><p>5</p><p>6</p><p>7</p>
                        <p>8</p><p>9</p><p>10</p><p>11</p>
                        <p>12</p><p>13</p><p>14</p><p>15</p>
                        <p>16</p><p>17</p><p>18</p><p>19</p>
                        <p>20</p><p>21</p><p>22</p><p>23</p>
                    </div>
                </div>
                <div class="modal-timetable-wrap_block">
                    <p class="modal-timetable-title">Минуты</p>
                    <div class="modal-timetable-wrapper-grid modal-timetable-wrapper-grid-m" data-type="m">
                        <p>00</p><p>05</p><p>10</p>
                        <p>15</p><p>20</p><p>25</p>
                        <p>30</p><p>35</p><p>40</p>
                        <p>45</p><p>50</p><p>55</p>
                    </div>
                </div>
            </div>
        `;
        timeBlock.innerHTML = html;
        timeBlock.classList.add("modal-timetable");
        document.querySelector('.edit-company-place').append(timeBlock);
        document.body.append(timeBlock);
        let offsetLeft = e.clientX - timeBlock.offsetWidth/2;
        let offsetTop = e.clientY - timeBlock.offsetHeight/2;
        timeBlock.style.top = String(offsetTop) + "px";
        timeBlock.style.left = String(offsetLeft) + "px";
        modelTimeTableListener(el.querySelector('input'));
    })
})
function modelTimeTableListener(input){
    let modalTimetable = document.querySelectorAll('.modal-timetable-wrapper-grid>p')
    modalTimetable.forEach(el => {
        el.addEventListener('click', function (){
            let type = el.closest('.modal-timetable-wrapper-grid').getAttribute('data-type');
            let value = el.innerHTML;
            let tmpValue = input.value.split('-');
            if(type === 'h'){
                tmpValue[0] = value;
            }else if(type === 'm'){
                tmpValue[1] = value;
            }
            input.value = tmpValue.join('-');
        })
    })
}