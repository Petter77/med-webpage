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
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Skierowanie:</h3>
                        <p>${response.skierowanie}</p>
                    `;
                        } else if (rodzaj === 'recepta' && response && response.recepta) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <p>${response.recepta}</p>
                    `;
                        } else if (rodzaj === 'wynik' && response && response.wynik) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Wynik:</h3>
                        <p>${response.wynik}</p>
                    `;
                        } else if (rodzaj === 'wpis' && response && response.wpis) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Wpis:</h3>
                        <p>${response.wpis}</p>
                    `;
                        }
                    } catch (e) {
                        document.getElementById('elementDetails').innerHTML = `<p>Invalid response from server</p>`;
                    }
                },

                error: function (xhr, status, error) {
                    console.log("AJAX error:", error); // Log any AJAX errors
                    document.getElementById('elementDetails').innerHTML = `<p>Error: ${error}</p>`;
                }
            });

    }



document.getElementById('addElementButton').addEventListener('click', function() {
    document.getElementById('elementDetails').innerHTML = `
        <h2>Dodaj nowe skierowanie</h2>
        <form>
            <label for="elementName">Nazwa skierowania:</label>
            <input type="text" id="elementName" name="elementName">
            <label for="elementDetails">Szczegóły:</label>
            <textarea id="elementDetails" name="elementDetails"></textarea>
            <button type="submit" class="button">Dodaj</button>
        </form>
    `;
});

