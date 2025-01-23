function selectPatient(element) {
    var patients = document.getElementsByClassName('patient');
    for (var i = 0; i < patients.length; i++) {
        patients[i].classList.remove('selected');
    }
    element.classList.add('selected');
    document.getElementById('chooseButton').disabled = false;
}

function choosePatient() {
    var selectedPatient = document.querySelector('.patient.selected');
    if (selectedPatient) {
        var pesel = selectedPatient.querySelector('p:last-child').innerText.split(': ')[1];
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "set_pesel.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                checkUserRole();
            }
        };
        xhr.send("pesel=" + encodeURIComponent(pesel));
    } else {
        alert('Please select a patient.');
    }
}

function checkUserRole() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_user_role.php", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var role = xhr.responseText;
            if (role === 'Specjalista') {
                window.location.href = 'techinical_panel.php';
            } else {
                window.location.href = 'main_Panel.php';
            }
        }
    };
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('data_urodzenia').setAttribute('max', today);
});