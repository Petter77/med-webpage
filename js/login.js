if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}

function LoginController() {
    const peselInput = document.getElementById('pesel');
    const warningLabel = document.getElementById('warning');

    // Check if the PESEL input has exactly 11 digits
    const peselValue = peselInput.value;
    const peselPattern = /^\d{11}$/;

    if (!peselPattern.test(peselValue)) {
        // If PESEL is not valid, display an error message
        warningLabel.textContent = 'PESEL musi mieć dokładnie 11 cyfr.';
        warningLabel.style.color = 'red';
    } else {
        // Clear the warning message if PESEL is valid
        warningLabel.textContent = '';
    }
}

function validatePesel() {
    const peselInput = document.getElementById('pesel');
    // Remove any non-numeric characters
    peselInput.value = peselInput.value.replace(/\D/g, '');
}



