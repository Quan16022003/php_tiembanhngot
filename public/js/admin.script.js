function logout() {
    window.location.href = "/admin/logout"
}

function themeToggle() {
    const element = document.getElementById("theme-toggle");
    if (element.classList.contains("light")) {
        element.classList.remove("light");
        element.classList.add("dark");
    } else {
        element.classList.remove("dark");
        element.classList.add("light");
    }
}

// execute function clock()
clock();

// execute function clock() every 1 second
// 1000 milliseconds = 1 second
setInterval(clock, 1000);

function clock() {

    // a crete date object
    var d = new Date();

    // get year
    var year = d.getFullYear().toString().padStart(2, '0');

    // get month - from 0 to 11
    var month = (d.getMonth() + 1).toString().padStart(2, '0');

    // get day of the month - from 1 to 31
    var day = d.getDate().toString().padStart(2, '0');

    // get day of the week - from 0 to 6
    // get name of the weekday, 0 = Sunday, 1 = Monday, 2 = Tuesday, ...
    switch (d.getDay()) {
        case 1:
            var weekDay = 'MON';
            break;
        case 2:
            var weekDay = 'TUE';
            break;
        case 3:
            var weekDay = 'WED';
            break;
        case 4:
            var weekDay = 'THU';
            break;
        case 5:
            var weekDay = 'FRI';
            break;
        case 6:
            var weekDay = 'SAT';
            break;
        case 0:
            var weekDay = 'SUN';
            break;
    }

    // add leading zero
    var hour = d.getHours().toString().padStart(2, '0');
    var min = d.getMinutes().toString().padStart(2, '0');
    var sec = d.getSeconds().toString().padStart(2, '0');

    // set content of date
    document.querySelector('.date').innerHTML = year + '-' + month + '-' + day + ' ' + weekDay;

    // set content of hour
    document.querySelector('.hour').innerHTML = hour;

    // set content of minute
    document.querySelector('.min').innerHTML = min;

    // set content of second
    document.querySelector('.sec').innerHTML = sec;
}

// when elment with class 'toggle-btn' is clcked,
document.querySelector('.toggle-btn').onclick = () => {

    // toggle class between 'light' and 'dark'
    document.querySelector('body').classList.toggle('dark');
    document.querySelector('body').classList.toggle('light');

};