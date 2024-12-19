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

document.getElementById('addReferralButton').addEventListener('click', function() {
    document.getElementById('referralDetails').innerHTML = `
        <h2>Dodaj nowe skierowanie</h2>
        <form>
            <label for="referralName">Nazwa skierowania:</label>
            <input type="text" id="referralName" name="referralName">
            <label for="referralDetails">Szczegóły:</label>
            <textarea id="referralDetails" name="referralDetails"></textarea>
            <button type="submit" class="button">Dodaj</button>
        </form>
    `;
});