document.getElementById('toggleButton').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('expanded');
});



function handleClick(id) {

    $.ajax({
        url: 'fetch_data_skierowanie.php', // PHP file that fetches the data
        type: 'GET',
        data: { id: id },
        dataType: 'json', // Send the ID as a parameter

        success: function (response) {
            console.log("Server response:", response);
            try {
                // Check if response has skierowanie field
                if (response && response.skierowanie) {
                    document.getElementById('referralDetails').innerHTML = `
                    <h3>Skierowanie:</h3>
                    <p>${response.skierowanie}</p>
                `;
                } else {
                    document.getElementById('referralDetails').innerHTML = `
                    <p>No skierowanie data found.</p>
                `;
                }
            } catch (e) {
                document.getElementById('referralDetails').innerHTML = `<p>Invalid response from server</p>`;
            }
        },
        error: function (xhr, status, error) {
            console.log("AJAX error:", error); // Log any AJAX errors
            document.getElementById('referralDetails').innerHTML = `<p>Error: ${error}</p>`;
        }
    });

}