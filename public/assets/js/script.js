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
const reloadTimetable = () => {
    let timetables = document.querySelectorAll('.input-company-field-timetable');
    timetables.forEach(el => {
        let input = el.querySelector('input');
        el.addEventListener('click', function (e){
            if(document.querySelector('.modal-timetable')){
                document.querySelector('.modal-timetable').remove();
            }
            let timeBlock = document.createElement('div')
            let [hNow, mNow] = input.value.split('-');

            let hHtml = '';
            for(let i = 0; i < 24; i ++){
                if(i == hNow){
                    hHtml += '<p class="active">'+i+'</p>';
                }else{
                    hHtml += '<p>'+i+'</p>';
                }
            }
            let mHtml = '';
            for(let i = 0; i < 60; i += 5){
                let tmp_i = i;
                if(tmp_i < 10){
                    tmp_i = '0'+ i;
                }
                if(i == mNow){
                    mHtml += '<p class="active">'+tmp_i+'</p>';
                }else{
                    mHtml += '<p>'+tmp_i+'</p>';
                }
            }

            let html = `
            <div class="modal-timetable-wrapper">
                <div class="modal-timetable-wrap_block">
                    <p class="modal-timetable-title">Часы</p>
                    <div class="modal-timetable-wrapper-grid modal-timetable-wrapper-grid-h" data-type="h">
                        ${hHtml}
                    </div>
                </div>
                <div class="modal-timetable-wrap_block">
                    <p class="modal-timetable-title">Минуты</p>
                    <div class="modal-timetable-wrapper-grid modal-timetable-wrapper-grid-m" data-type="m">
                        ${mHtml}
                    </div>
                </div>
            </div>
        `;
            timeBlock.innerHTML = html;
            timeBlock.classList.add("modal-timetable");
            document.querySelector('.edit-company-place').append(timeBlock);
            document.body.append(timeBlock);
            document.body.addEventListener('mousedown', event => {
                if(event.target !== input){
                    if(!event.target.closest('.modal-timetable')){
                        timeBlock.remove();
                    }
                }
            });
            let offsetLeft = e.clientX - timeBlock.offsetWidth/2;
            let offsetTop = e.clientY - timeBlock.offsetHeight/2;
            timeBlock.style.top = String(offsetTop) + "px";
            timeBlock.style.left = String(offsetLeft) + "px";
            modelTimeTableListener(input);
        })
    })
}

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
            modalTimetable.forEach(el_all => {
                if(el_all === el){
                    el.classList.add('active');
                }else{
                    if (el_all.classList.contains('active') && el_all.closest('.modal-timetable-wrapper-grid-'+type)){
                        el_all.classList.remove('active');
                    }
                }
            })
        })
    })
}

const resetTimetables = document.querySelectorAll('.reset-timetable-field');
resetTimetables.forEach(el => {
    el.addEventListener('click', function (){
        const parent = el.closest('.field-company-timetable')
        parent.querySelector('.input-company-field-timetable-start input').value = el.getAttribute('data-start');
        parent.querySelector('.input-company-field-timetable-end input').value = el.getAttribute('data-end');
    });
});

const addNewStaffService = () => {
    let newBlock = document.querySelector('#newStaffService .field-staff-services').cloneNode(true);
    let listServices = document.querySelector('.list-field-staff-services');
    listServices.appendChild(newBlock);
    reloadTimetable();
}
const removeNewStaffService = (obj) => {
    let objBlock = obj.parentElement.parentElement;
    let id;
    if (id = objBlock.querySelector('.staff-service-id')) {
        if (confirm("Вы действительно хотите удалить услугу из прайса?")) {
            let xhr = new XMLHttpRequest();
            let fd = new FormData();
            fd.append('id', id.value);
            xhr.open('POST', '/lk/setting/staff_services_delete');
            xhr.onreadystatechange = function (){
                if(xhr.readyState === 4 && xhr.status === 200){
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.response == true) {
                            obj.parentElement.parentElement.remove();
                        }
                    } catch (e) {
                        console.log(xhr.responseText);
                    }
                }
            };
            xhr.send(fd);
        }
    }else{
        obj.parentElement.parentElement.remove();
    }
}
const openCloseSabMenu = (id) => {
    let subMenu;
    if (subMenu = document.getElementById(id)) {
        let subMenuLi = subMenu.querySelectorAll('.sub-menu-item-title');
        if (!subMenu.classList.contains("active")) {
            subMenu.classList.add("active");
            // for (let i=0; i < subMenuLi.length; i++) {
            //     subMenuLi[i].style.display="none";
            // }
        } else {
            subMenu.classList.remove("active");
            // for (let i=0; i < subMenuLi.length; i++) {
            //     subMenuLi[i].style.display="flex";
            // }
        }
    }
}

reloadTimetable();