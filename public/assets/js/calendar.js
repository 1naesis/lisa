function calendaruser(){
    const selectDate = {
        'selectDay': 0,
        'selectMonth': 0,
        'selectYear': 0
    }

    function calendarUserTimetable(id, year, month) {
        selectDate.selectDay = 0;
        selectDate.selectMonth = month+1;
        selectDate.selectYear = Number(year);
        var Dlast = new Date(year,month+1,0).getDate(),
            D = new Date(year,month,Dlast),
            DNlast = new Date(D.getFullYear(),D.getMonth(),Dlast).getDay(),
            DNfirst = new Date(D.getFullYear(),D.getMonth(),1).getDay(),
            calendar = '<tr class="week-user-timetable">',
            month=["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
        if (DNfirst != 0) {
            for(var  i = 1; i < DNfirst; i++) calendar += '<td>';
        }else{
            for(var  i = 0; i < 6; i++) calendar += '<td>';
        }
        for(var  i = 1; i <= Dlast; i++) {
            if (i == new Date().getDate() && D.getFullYear() == new Date().getFullYear() && D.getMonth() == new Date().getMonth()) {
                calendar += '<td class="today cell-user-timetable"><div>' + i + '</div>';
                selectDate.selectDay = i;
            }else{
                calendar += '<td class="cell-user-timetable"><div>' + i + '</div>';
            }
            if (new Date(D.getFullYear(),D.getMonth(),i).getDay() == 0) {
                calendar += '<tr class="week-user-timetable">';
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

    function loadTableOneDay(){
        // console.log(selectDate.selectDay + '-' + selectDate.selectMonth + '-' + selectDate.selectYear);
        if(selectDate.selectDay){
            let date = selectDate.selectDay + '-' + selectDate.selectMonth + '-' + selectDate.selectYear;
            let userId = document.getElementById('userId').value;
            let fd = new FormData();
            fd.append('user_id', userId);
            fd.append('date', date);
            let xhr = new XMLHttpRequest();
            xhr.open('POST', '/lk/modal/calendar-user-timetable');
            xhr.onreadystatechange = function (){
                if(xhr.readyState === 4 && xhr.status === 200){
                    try {
                        let response = JSON.parse(xhr.responseText);
                        if (response.code == 200) {
                            document.querySelector('.days-wrapper').innerHTML = response.response;
                            listenCell();
                        } else {
                            console.log(response);
                        }
                    } catch (e) {
                        console.log(xhr.responseText);
                        console.log("Незвестная ошибка");
                    }
                }
            };
            xhr.send(fd);
        }
    }

    function listenCell(){
        let cells = document.querySelectorAll('.table-day-cell');
        cells.forEach(c => {
            c.addEventListener('click', cell => {
                let fd = new FormData();
                fd.append('date', cell.target.getAttribute('data-date'));
                fd.append('time', cell.target.getAttribute('data-time'));
                fd.append('user_id', document.getElementById('userId').value);
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '/lk/modal/edit-one-hour');
                xhr.onreadystatechange = function (){
                    if(xhr.readyState === 4 && xhr.status === 200){
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.code == 200) {
                                switch (response.response) {
                                    case 1:
                                        cell.target.querySelector('div').classList.remove("empty-day");
                                        cell.target.querySelector('div').classList.add("working-day");
                                        break;
                                    case 2:
                                        cell.target.querySelector('div').classList.remove("working-day");
                                        cell.target.querySelector('div').classList.add("empty-day");
                                        break;
                                }
                            } else {
                            }
                        } catch (e) {
                            console.log("Незвестная ошибка");
                        }
                    }
                }
                xhr.send(fd);
            })
        })
    }

    function eventClick(){
        const cellTimetable = document.querySelectorAll('.cell-user-timetable');
        cellTimetable.forEach(el => {
            el.addEventListener('click', function (){
                cellTimetable.forEach(cell => {
                    if(cell === el){
                        cell.classList.add("selected-cell")
                    }else{
                        cell.closest('.week-user-timetable').classList.remove("active-week-user");
                        cell.classList.remove("selected-cell");
                    }
                })
                selectDate.selectDay = Number(el.querySelector('div').innerText);
                changeWeek();
                loadTableOneDay();
            })
        })
    }

    function changeWeek(){
        let findWeek = false;
        if(document.querySelector('#calendar-user-timetable .selected-cell')){
            findWeek = document.querySelector('#calendar-user-timetable .selected-cell')
        }else if(document.querySelector('#calendar-user-timetable .today')) {
            findWeek = document.querySelector('#calendar-user-timetable .today')
        }
        if(findWeek){
            const nowWeek = findWeek.closest('.week-user-timetable');
            nowWeek.classList.add('active-week-user');
        }
    }

    if(document.getElementById('calendar-user-timetable')){
        calendarUserTimetable("calendar-user-timetable", new Date().getFullYear(), new Date().getMonth());
        changeWeek();
        loadTableOneDay();

        document.querySelector('#calendar-user-timetable thead tr:nth-child(1) td:nth-child(1)').onclick = function() {
            calendarUserTimetable(
                "calendar-user-timetable",
                document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.year,
                parseFloat(document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.month)-1
            );
        }

        document.querySelector('#calendar-user-timetable thead tr:nth-child(1) td:nth-child(3)').onclick = function() {
            calendarUserTimetable(
                "calendar-user-timetable",
                document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.year,
                parseFloat(document.querySelector('#calendar-user-timetable thead td:nth-child(2)').dataset.month)+1
            );
        }
    }
}
calendaruser();