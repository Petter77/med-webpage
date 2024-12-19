document.getElementById('toggleButton').addEventListener('click', function() {
    document.getElementById('sidebar').classList.toggle('expanded');
});



function handleClick(id) {
    
    $.ajax({
        url: 'fetch_data_skierowanie.php', // PHP file that fetches the data
        type: 'GET',
        data: { id: id }, // Send the ID as a parameter

        success: function (response) {
            console.log("Server response:", response); // Log server response
        try {
            const data = JSON.parse(response);

            if(data.error) {
        document.getElementById('referralDetails').innerHTML = `<p>${data.error}</p>`;
    } else {
        document.getElementById('referralDetails').innerHTML = `
                        <h3>Skierowanie Data: ${data.dataSkierowania}</h3>
                        <p>Opis: ${data.opis}</p>
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