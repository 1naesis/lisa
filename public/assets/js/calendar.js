function calendarUserTimetable(id, year, month) {
    var Dlast = new Date(year,month+1,0).getDate(),
        D = new Date(year,month,Dlast),
        DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
        DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
        calendar = '<tr>',
        month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
    if (DNfirst != 0) {
        for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
    }else{
        for(var  i = 0; i < 6; i++) calendar += '<td>';
    }
    for(var  i = 1; i <= Dlast; i++) {
        if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
            calendar += '<td class="today cell-user-timetable">' + i;
        }else{
            calendar += '<td class="cell-user-timetable">' + i;
        }
        if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
            calendar += '<tr>';
        }
    }
    for(var  i = DNlast; i < 7; i++) calendar += '<td>&nbsp;';
    document.querySelector('#'+id+' tbody').innerHTML = calendar;
    document.querySelector('#'+id+' thead td:nth-child(2)').innerHTML = month[D.getMonth()] +' '+ D.getFullYear();
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.month = D.getMonth();
    document.querySelector('#'+id+' thead td:nth-child(2)').dataset.year = D.getFullYear();
    if (document.querySelectorAll('#'+id+' tbody tr').length < 6) {
        document.querySelector('#'+id+' tbody').innerHTML += '<tr><td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;<td>&nbsp;';
    }
    eventClick();
}

function eventClick(){
    const cellTimetable = document.querySelectorAll('.cell-user-timetable');
    cellTimetable.forEach(el => {
        el.addEventListener('click', function (){
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/lk/modal/calendar-user-timetable/1');
            xhr.onreadystatechange = function (){
                if(xhr.readyState === 4 && xhr.status === 200){
                    try {
                        let response = JSON.parse(xhr.responseText);
                        console.log(response);
                    } catch (e) {
                        console.log("Незвестная ошибка");
                    }
                }
            };
            xhr.send();
        })
    })
}

calendarUserTimetable("calendar-user-timetable", new Date().getFullYear(), new Date().getMonth());

document.querySelector('#calendar-user-timetable thead tr:nth-child(1) td:nth-child(1)').onclick = function() {
    calendarUserTimetable("calendar-user-timetable", document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.month)-1);
}

document.querySelector('#calendar-user-timetable thead tr:nth-child(1) td:nth-child(3)').onclick = function() {
    calendarUserTimetable("calendar-user-timetable", document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.year, parseFloat(document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.month)+1);
}