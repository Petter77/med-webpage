if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

function LoginController() {
    const peselInput = document.getElementById('pesel');
    const warningLabel = document.getElementById('warning');

    const peselValue = peselInput.value;
    const peselPattern = /^\d{11}$/;

    if (!peselPattern.test(peselValue)) {
        warningLabel.textContent = 'PESEL musi mieć dokładnie 11 cyfr.';
        warningLabel.style.color = 'red';
    } else {
        warningLabel.textContent = '';
    }
}

function validatePesel() {
    const peselInput = document.getElementById('pesel');
    peselInput.value = peselInput.value.replace(/\D/g, '');
}
function validateId() {
    const IdInput = document.getElementById('id');
    IdInput.value = IdInput.value.replace(/\D/g, '');
}





