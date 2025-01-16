document.getElementById('toggleButton').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('expanded');
});


function editRow(button) {
    const row = button.closest('tr'); // Get the table row
    const cells = row.querySelectorAll('[contenteditable], [data-column="rola"]');
    const predefinedRoles = ['Lekarz', 'Ratownik', 'Specjalista', 'Administrator'];
    const checkbox = row.querySelector('.aktywny-checkbox'); // Get the checkbox

    // Enable editing for contenteditable cells
    cells.forEach(cell => {
        if (cell.getAttribute('data-column') === 'rola') {
            // Replace the text with a dropdown for "rola"
            const currentValue = cell.textContent.trim();
            const select = document.createElement('select');

            // Add options from predefinedRoles
            predefinedRoles.forEach(role => {
                const option = document.createElement('option');
                option.value = role;
                option.textContent = role;
                if (role === currentValue) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            cell.textContent = ''; // Clear current content
            cell.appendChild(select); // Add dropdown
        } else {
            cell.contentEditable = true;
        }
    });

    // Enable the checkbox
    checkbox.disabled = false; // Enable the checkbox for editing

    // Toggle button visibility
    button.style.display = 'none'; // Hide "Edit" button
    row.querySelector('.save-button').style.display = 'inline'; // Show "Save" button
}

function saveRow(button) {
    const row = button.closest('tr'); // Get the table row
    const cells = row.querySelectorAll('[contenteditable], [data-column="rola"]');
    const data = {};
    const checkbox = row.querySelector('.aktywny-checkbox'); // Get checkbox

    // Collect data from the row
    cells.forEach(cell => {
        const column = cell.getAttribute('data-column');
        if (column === 'rola') {
            // Get selected value from the dropdown
            const select = cell.querySelector('select');
            if (select) {
                data[column] = select.value;
                cell.textContent = select.value; // Replace dropdown with the selected value
            }
        } else {
            data[column] = cell.textContent.trim();
            cell.contentEditable = false; // Disable editing
        }
    });

    // Get the value of the checkbox (true/false)
    data['aktywne'] = checkbox.checked ? true : false; // Ensure the value is correctly sent
    data['id'] = row.querySelector('[data-column="id"]').textContent.trim(); // Add ID

    console.log('Data being sent to server:', data); // Debugging: Check data being sent

    // Send the data to the server via AJAX
    fetch('editAdmin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                alert('Rekord został zapisany!');
                console.log('Database updated successfully');

                // Update checkbox status after saving
                checkbox.checked = data['aktywne']; // Dynamically update checkbox based on 'aktywne' value
            } else {
                alert('Błąd podczas zapisywania.');
                console.log('Error during saving:', result);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

    // Toggle button visibility
    button.style.display = 'none'; // Hide "Save" button
    row.querySelector('.edit-button').style.display = 'inline'; // Show "Edit" button

    // Disable the checkbox after saving
    checkbox.disabled = true; // Disable checkbox after saving
}
function handleClick(id, rodzaj) {
    console.log("handleClick triggered with id:", id, "and rodzaj:", rodzaj);
    let url = '';
    switch (rodzaj) {
        case 'skierowanie':
            url = 'fetch_data_skierowanie.php';  // URL for skierowanie
            break;
        case 'recepta':
            url = 'fetch_data_recepty.php';  // URL for recepta
            break;
        case 'wynik':
            url = 'fetch_data_wyniki.php';  // URL for wynik
            break;
        case 'wpis':
            url = 'fetch_data_wpisy.php';  // URL for wpis
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
                        <button class="edit-button" onclick="editData(${id}, 'skierowanie')">Edytuj</button>
                    `;
                } else if (rodzaj === 'recepta' && response && response.przypisaneLeki) {
                    document.getElementById('elementDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <p>${response.przypisaneLeki}</p>
                        <button class="edit-button" onclick="editData(${id}, 'recepta')">Edytuj</button>
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
                        <button class="edit-button" onclick="editData(${id}, 'wpis')">Edytuj</button>             
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
                    document.getElementById('elementDetails').innerHTML = `
                        <h3>Skierowanie:</h3>
                        <input type="text" id="editInput" value="${response.skierowanie}">
                        <div class="form-buttons">
                            <button class="save-button" onclick="updateData(${id}, 'skierowanie')">Zapisz</button>
                            <button class="cancel-button" onclick="cancelEdit(${id}, 'skierowanie')">Anuluj</button>
                        </div>
                    `;
                } else if (rodzaj === 'recepta' && response && response.przypisaneLeki) {
                    document.getElementById('elementDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <input type="text" id="editInput" value="${response.przypisaneLeki}">
                        <div class="form-buttons">
                            <button class="save-button" onclick="updateData(${id}, 'recepta')">Zapisz</button>
                            <button class="cancel-button" onclick="cancelEdit(${id}, 'recepta')">Anuluj</button>
                        </div>
                    `;
                } else if (rodzaj === 'wpis' && response && response.wpis) {
                    document.getElementById('elementDetails').innerHTML = `
                        <h3>Wpis:</h3>
                        <input type="text" id="editInput" value="${response.wpis}">
                        <div class="form-buttons">
                            <button class="save-button" onclick="updateData(${id}, 'wpis')">Zapisz</button>
                            <button class="cancel-button" onclick="cancelEdit(${id}, 'wpis')">Anuluj</button>
                        </div>
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

function cancelEdit(id, rodzaj) {
    if (confirm("Czy na pewno chcesz przerwać edycję?")) {
        handleClick(id, rodzaj);
    }
}

function updateData(id, rodzaj) {
    console.log("updateData triggered with id:", id, "and rodzaj:", rodzaj);
    if (!confirm("Czy na pewno chcesz edytować dane?")) {
        return;
    }
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
            document.getElementById('elementDetails').innerHTML = `<p>Error: ${error}</p>`;
        }
    });
}


function addFileInputListener() {
    const fileInput = document.getElementById('fileInput');
    fileInput.addEventListener('change', function() {
        const fileDateContainer = document.getElementById('fileDateContainer');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const allowedExtensions = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;
            if (!allowedExtensions.exec(file.name)) {
                alert('Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.');
                fileInput.value = ''; // Clear the file input
                fileDateContainer.innerHTML = ''; // Clear the file date container
                return;
            }
            fileDateContainer.innerHTML = `
                <label for="fileDate">Data pliku:</label>
                <input type="date" id="fileDate" name="fileDate">
            `;
        } else {
            fileDateContainer.innerHTML = '';
        }
    });
}

function checkAllergy() {
    const input = document.getElementById('allergyInput').value;
    if (input.length > 2) { // Start searching after 3 characters
        fetch(`check_allergy.php?query=${input}`)
            .then(response => response.json())
            .then(data => {
                const suggestions = document.getElementById('suggestions');
                suggestions.innerHTML = '';
                data.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = item.nazwa;
                    div.onclick = () => {
                        document.getElementById('allergyInput').value = item.nazwa;
                        document.getElementById('allergyId').value = item.id;
                        suggestions.innerHTML = '';
                    };
                    suggestions.appendChild(div);
                });
            });
    }
}

function validateAllergyForm() {
    const allergyId = document.getElementById('allergyId').value;
    if (!allergyId) {
        alert('Please select a valid allergy from the suggestions.');
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}

document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'addDescriptionButton') {
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nowy Wpis</h2>
                <form action="insert_data_wpis.php" method="post">
                    <label for="elementDetailsTextarea">Szczegóły:</label>
                    <textarea id="elementDetailsTextarea" name="DescriptionInfo"></textarea>
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
        } else if (event.target && event.target.id === 'addRecipeButton') {
            const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nową receptę</h2>
                <form action="insert_data_recepty.php" method="post">
                    <label for="elementName">Przypisywane Leki:</label>
                    <input type="text" id="elementName" name="RecipeInfo">
                    <label for="elementDetailsTextarea">Termin Recepty:</label>
                    <input type="date" id="elementDetailsTextarea" name="RecipeEndDate" value="${today}" min="${today}">
                    <label for="optionalSelect">Recepta Jednorazowa ?:</label>
                    <select id="optionalSelect" name="optionalSelect">
                        <option value="yes">Tak</option>
                        <option value="no">Nie</option>
                    </select>
                    <button type="submit" style="margin-top: 10px;" class="button">Dodaj</button>
                </form>
            `;
        } else if (event.target && event.target.id === 'addElementButton') {
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nowe skierowanie</h2>
                <form action="insert_data_skierowanie.php" method="post">
                    <label for="elementDetailsTextarea">Skierowanie:</label>
                    <textarea id="elementDetailsTextarea" name="referralDetails"></textarea>
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
        }
        else if (event.target && event.target.id === 'addPapersButton') {
            const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nowe Wyniki</h2>
                <form action="insert_data_wyniki.php" method="post" enctype="multipart/form-data">
                    <label for="elementName">Wyniki Badania:</label>
                    <textarea id="elementDetailsTextarea" name="examinationDetails"></textarea>
                    <label for="elementDetailsTextarea">Data Przeprowadzenia Wyników:</label>
                    <input type="date" id="elementDetailsTextarea" name="examinationDate" value="${today}">
                    <label for="fileInput">Załącz plik:</label>
                    <input type="file" id="fileInput" name="file" accept=".jpg,.jpeg,.png,.pdf">
                    <div id="fileDateContainer"></div>
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
            addFileInputListener();
        }
        else if(event.target && event.target.id === 'addAllergiesButton'){
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nową Alergie</h2>
                <form id="allergyForm" action="insert_data_alergie.php" method="post" onsubmit="return validateAllergyForm()">
                    <label for="allergyInput">Alergia:</label>
                    <input type="text" id="allergyInput" name="allergy" oninput="checkAllergy()">
                    <input type="hidden" id="allergyId" name="allergyId">
                    <div id="suggestions"></div>
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
        }
    });
});

