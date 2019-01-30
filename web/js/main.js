function registerProcess(registerForm) {
    let nickname = '',
    password = '',
    lastname = '',
    firstname = '',
    age = '';

}

function loginProcess(loginProcess) {
    localStorage.setItem('token', 'test');
}

function analyticsEvent(e) {
    let sourceLabel = e.target.getAttribute('data-label');
    sendEvent(sourceLabel);
}

function sendEvent(sourceLabel){
    var token = localStorage.getItem('token'),
    xhr = new XMLHttpRequest();

    xhr.open("GET", "/analytics/new?source_label=" + sourceLabel, true);
    xhr.setRequestHeader('Authorization', 'Bearer ' + token);
    xhr.send();
}

document.ready(function(){
    var registerForm = document.getElementById('register_form'),
    loginForm = document.getElementById('login_form');

    if (typeof registerForm !== null) {
        registerProcess(registerForm)
    } else if (typeof loginProcess !== null) {
        loginProcess(loginForm)
    }

    var eventElement = document.getElementsByClassName('event');
    eventElement.addEventListener("click",analyticsEvent());

    var pageLabel = document.getElementById('page_label'),
    label = pageLabel.getAttribute('data-label');
    analyticsEvent(label)
});
