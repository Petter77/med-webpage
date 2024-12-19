document.getElementById('toggleButton').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('expanded');
});



function handleClick(id, rodzaj) {
    console.log("handleClick triggered with id:", id, "and rodzaj:", rodzaj);
    let url = '';
    switch (rodzaj) {
        case 'skierowanie':
            url = 'fetch_data_skierowanie.php';  // URL for skierowanie
            break;
        case 'recepta':
            url = 'fetch_data_recepty.php';  // URL for torodzaj
            break;
        case 'wynik':
            url = 'fetch_data_wyniki.php';  // URL for torodzaj
            break;
        case 'wpis':
            url = 'fetch_data_wpisy.php';  // URL for torodzaj
            break;
    }
            console.log("Making AJAX request to:", url, "with id:", id);  // Debugging log
            $.ajax({
                url: url,       // Use the dynamically set URL based on rodzaj
                type: 'GET',
                data: { id: id },  // Send the ID as a parameter
                dataType: 'json',   // Expecting JSON response

                success: function (response) {
                    console.log("Server response:", response);  // Log the response

                    try {
                        // Handle response based on rodzaj
                        if (rodzaj === 'skierowanie' && response && response.skierowanie) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Skierowanie:</h3>
                        <p>${response.skierowanie}</p>
                        <button onclick="editData(${id}, 'skierowanie')">Edytuj</button>
                    `;
                        } else if (rodzaj === 'recepta' && response && response.przypisaneLeki) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <p>${response.przypisaneLeki}</p>
                        <button onclick="editData(${id}, 'recepta')">Edytuj</button>
                    `;
                        } else if (rodzaj === 'wynik' && response && response.wynik) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Wynik:</h3>
                        <p>${response.wynik}</p>
                    `;
                        } else if (rodzaj === 'wpis' && response && response.wpis) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Wpis:</h3>
                        <p>${response.wpis}</p>
                        <button onclick="editData(${id}, 'wpis')">Edytuj</button>
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

function editData(id, rodzaj) {
    console.log("editData triggered with id:", id, "and rodzaj:", rodzaj);
    let url = '';
    switch (rodzaj) {
        case 'skierowanie':
            url = 'fetch_data_skierowanie.php';  // URL for skierowanie
            break;
        case 'recepta':
            url = 'fetch_data_recepty.php';  // URL for recepty
            break;
        case 'wpis':
            url = 'fetch_data_wpisy.php';  // URL for wpisy
            break;
    }
            console.log("Making AJAX request to:", url, "with id:", id);  // Debugging log
            $.ajax({
                url: url,       // Use the dynamically set URL based on rodzaj
                type: 'GET',
                data: { id: id },  // Send the ID as a parameter
                dataType: 'json',   // Expecting JSON response
                success: function (response) {
                    console.log("Server response:", response);  // Log the response

                    try {
                        // Handle response based on rodzaj
                        if (rodzaj === 'skierowanie' && response && response.skierowanie) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Skierowanie:</h3>
                        <input type="text" id="editInput" value="${response.skierowanie}">
                        <button onclick="updateData(${id}, 'skierowanie')">Zapisz</button>
                    `;
                        } else if (rodzaj === 'recepta' && response && response.przypisaneLeki) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <input type="text" id="editInput" value="${response.przypisaneLeki}">
                        <button onclick="updateData(${id}, 'recepta')">Zapisz</button>
                    `;
                        } else if (rodzaj === 'wpis' && response && response.wpis) {
                            document.getElementById('referralDetails').innerHTML = `
                        <h3>Wpis:</h3>
                        <input type="text" id="editInput" value="${response.wpis}">
                        <button onclick="updateData(${id}, 'wpis')">Zapisz</button>
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

function updateData(id, rodzaj) {
    console.log("updateData triggered with id:", id, "and rodzaj:", rodzaj);
    let newData = document.getElementById('editInput').value;
    let url = '';
    let data = { id: id };

    switch (rodzaj) {
        case 'skierowanie':
            url = 'edit_data_skierowania.php';
            data.skierowanie = newData;
            break;
        case 'recepta':
            url = 'edit_data_recepty.php';
            data.przypisaneLeki = newData;
            break;
        case 'wpis':
            url = 'edit_data_wpisu.php';
            data.wpis = newData;
            break;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (response) {
            console.log("Server response:", response);
            handleClick(id, rodzaj);
        },
        error: function (xhr, status, error) {
            console.log("AJAX error:", error);
            document.getElementById('referralDetails').innerHTML = `<p>Error: ${error}</p>`;
        }
    });
}