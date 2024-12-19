document.getElementById('toggleButton').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('expanded');
});

function showDetails(elementId) {
    const details = {
        element1: 'Szczegóły elementu 1: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        element2: 'Szczegóły elementu 2: Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        element3: 'Szczegóły elementu 3: Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.'
    };

    document.getElementById('elementDetails').innerHTML = `
        <h2>Szczegóły elementu</h2>
        <p>${details[elementId]}</p>
    `;
}