document.getElementById('toggleButton').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('expanded');
});

function showDetails(referralId) {
    const details = {
        referral1: 'Szczegóły skierowania 1: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        referral2: 'Szczegóły skierowania 2: Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        referral3: 'Szczegóły skierowania 3: Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
    };

    document.getElementById('referralDetails').innerHTML = `
        <h2>Szczegóły skierowania</h2>
        <p>${details[referralId]}</p>
    `;
}