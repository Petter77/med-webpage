document.getElementById('toggleButton').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('expanded');
});


function editRow(button) {
    const row = button.closest('tr'); 
    const cells = row.querySelectorAll('[contenteditable], [data-column="rola"]');
    const predefinedRoles = ['Lekarz', 'Ratownik', 'Specjalista', 'Administrator'];
    const checkbox = row.querySelector('.aktywny-checkbox'); 

    cells.forEach(cell => {
        if (cell.getAttribute('data-column') === 'rola') {

            const currentValue = cell.textContent.trim();
            const select = document.createElement('select');

            predefinedRoles.forEach(role => {
                const option = document.createElement('option');
                option.value = role;
                option.textContent = role;
                if (role === currentValue) {
                    option.selected = true;
                }
                select.appendChild(option);
            });

            cell.textContent = ''; 
            cell.appendChild(select);
        } else {
            cell.contentEditable = true;
        }
    });

    checkbox.disabled = false; 
    button.style.display = 'none'; 
    row.querySelector('.save-button').style.display = 'flex'; 
}

function saveRow(button) {
    const row = button.closest('tr'); 
    const cells = row.querySelectorAll('td');
    const data = {};


    cells.forEach(cell => {
        const column = cell.getAttribute('data-column');
        if (column === 'rola') {
            const select = cell.querySelector('select');
            if (select) {
                data[column] = select.value;
                cell.textContent = select.value; 
            }
        } else {
            data[column] = cell.textContent.trim();
            cell.contentEditable = false; 
        }
    });

    const checkbox = row.querySelector('.aktywny-checkbox');
    if (checkbox) {
        data['aktywne'] = checkbox.checked ? true : false;
    }

    fetch('editAdmin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
        .then(response => response.text())
        .then(result => {
            if (result === 'success') {
                alert('Rekord został zapisany!');
            } else {
                alert('Błąd podczas zapisywania.');
            }
        })
        .catch(error => console.error('Error:', error));

    checkbox.disabled = true;
    button.style.display = 'none';
    row.querySelector('.edit-button').style.display = 'flex';
}



function handleClick(id, rodzaj) {
    console.log("handleClick triggered with id:", id, "and rodzaj:", rodzaj);
    let url = '';
    var sessionID = sessionStorage.getItem('sessionID');
    switch (rodzaj) {
        case 'skierowanie':
            url = 'fetch_data_skierowanie.php'; 
            break;
        case 'recepta':
            url = 'fetch_data_recepty.php'; 
            break;
        case 'wynik':
            url = 'fetch_data_wyniki.php'; 
            break;
        case 'wpis':
            url = 'fetch_data_wpisy.php'; 
            break;
    }

            console.log("Making AJAX request to:", url, "with id:", id);
            $.ajax({
                url: url,  
                type: 'GET',
                data: { id: id}, 
                dataType: 'json', 

                success: function (response) {
                    console.log("Server response:", response); 
                    try {
                        if (rodzaj === 'skierowanie' && response) {
                            document.getElementById('elementDetails').innerHTML = `
                            <h3>Data skierowania</h3>
                            <p>${response.data}</p>
                            <h3>Pesel Pacjenta</h3>
                            <p>${response.pesel}</p>
                            <h3>Dane Personelu</h3>
                            <p>${response.personel_imie} ${response.personel_nazwisko} </p>
                            <h3>Skierowanie:</h3>
                            <p>${response.skierowanie}</p>`;
                            if(sessionID == response.idpersonelu){    
                                document.getElementById('elementDetails').innerHTML +=
                                `<button class="edit-button" onclick="editData(${id}, 'skierowanie')">Edytuj</button>`;
                            };
                        } else if (rodzaj === 'recepta' && response) {
                            document.getElementById('elementDetails').innerHTML = `
                            <h3>Data wystawienia recepty</h3>
                            <p>${response.recepty_datawystawienia}</p>
                            <h3>Data ważności recepty</h3>
                            <p>${response.recepty_datawaznosci}</p>
                            <h3>Pesel Pacjenta</h3>
                            <p>${response.pesel}
                            <h3>Dane Personelu</h3>
                            <p>${response.personel_imie} ${response.personel_nazwisko} </p>
                            <h3>Przypisane leki:</h3>
                            <p>${response.recepty_przypisaneleki}</p>`;
                            if(sessionID == response.idpersonelu){    
                                document.getElementById('elementDetails').innerHTML +=
                                `<button class="edit-button" onclick="editData(${id}, 'recepta')">Edytuj</button>`;
                            };
                        } else if (rodzaj === 'wynik' && response ) {
                            document.getElementById('elementDetails').innerHTML = `
                            <h3>Data wyniku</h3>
                            <p>${response.wyniki_data}</p>
                            <h3>Pesel Pacjenta</h3>
                            <p>${response.pesel}</p>
                            <h3>Dane Personelu</h3>
                            <p>${response.personel_imie} ${response.personel_nazwisko} </p>`
                            if (response.sciezka != null) {
                                const sciezka = response.sciezka;
                                const fileExtension = sciezka.split('.').pop().toLowerCase();

                                let fileContent = `<a href="https://studencki-portal-medyczny.pl/getfile.php?file=${sciezka}" target="_blank">Pobierz wynik</a>`;

                                if (["jpg", "jpeg", "png", "gif", "bmp", "webp"].includes(fileExtension)) {
                                    fileContent += `<img src="https://studencki-portal-medyczny.pl/getfile.php?file=${sciezka}" alt="Wynik" style="width: 100%; max-height: 600px; object-fit: contain;">`;
                                } else if (fileExtension === "pdf") {
                                    fileContent += `<iframe src="https://studencki-portal-medyczny.pl/getfile.php?file=${sciezka}" width="100%" height="600px" style="border: none;"></iframe>`;
                                    
                                } else {
                                    fileContent += `<p>Nieobsługiwany format pliku.</p>`;
                                }
                                console.log("xd" + fileContent);
                                document.getElementById('elementDetails').innerHTML += fileContent;
                            }
                            else {
                                let fileContent = `<p>Brak pliku na serwerze</p>`;
                                document.getElementById('elementDetails').innerHTML += fileContent;
                            }
                        } else if (rodzaj === 'wpis' && response) {
                            document.getElementById('elementDetails').innerHTML = `
                            <h3>Data wpisu</h3>
                            <p>${response.wpisy_data}</p>
                            <h3>Pesel Pacjenta</h3>
                            <p>${response.pesel}</p>
                            <h3>Dane Personelu</h3>
                            <p>${response.personel_imie} ${response.personel_nazwisko} </p>
                            <h3>Wpis:</h3>
                            <p>${response.wpis}</p>`;
                            if(sessionID == response.idpersonelu){    
                                document.getElementById('elementDetails').innerHTML +=
                                `<button class="edit-button" onclick="editData(${id}, 'wpis')">Edytuj</button>`;
                            };            
                        };
                    } catch (e) {
                        document.getElementById('elementDetails').innerHTML = `<p>Invalid response from server</p>`;
                    }
                },

                error: function (xhr, status, error) {
                    console.log("AJAX error:", error); 
                    document.getElementById('elementDetails').innerHTML = `<p>Error: ${error}</p>`;
                }
            });


}
function addUser() {
    document.getElementById('elementDetailsAdmin').innerHTML = `
        <h2>Dodaj Nowego Użytkownika</h2>
        <form id="userForm">
            <label for="imie">Imię:</label>
            <input type="text" id="imie" name="imie" maxlength="50" required >

            <label for="nazwisko">Nazwisko:</label>
            <input type="text" id="nazwisko" name="nazwisko" maxlength="50" required>

            <label for="rola">Rola:</label>
            <select id="rola" name="rola" required>
                <option value="Lekarz">Lekarz</option>
                <option value="Ratownik">Ratownik</option>
                <option value="Specjalista">Specjalista</option>
                <option value="Administrator">Administrator</option>
            </select>
            <br>
            <button type="submit" class="button">Dodaj</button>
        </form>
    `;

    document.getElementById('userForm').addEventListener('submit', function (event) {
        event.preventDefault(); 

        const formData = new FormData(this);

        fetch('insert_data_user.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Użytkownik został dodany!');
                    location.reload(); 
                } else {
                    alert('Błąd: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Wystąpił błąd podczas dodawania użytkownika.');
            });
    });
}

function editData(id, rodzaj) {
    console.log("editData triggered with id:", id, "and rodzaj:", rodzaj);
    let url = '';
    switch (rodzaj) {
        case 'skierowanie':
            url = 'fetch_data_skierowanie.php';  
            break;
        case 'recepta':
            url = 'fetch_data_recepty.php'; 
            break;
        case 'wpis':
            url = 'fetch_data_wpisy.php';  
            break;
        case 'wynik':
            url = 'fetch_data_wyniki.php';
            break;
        default: break;
    }
            console.log("Making AJAX request to:", url, "with id:", id);  
            $.ajax({
                url: url,       
                type: 'GET',
                data: { id: id },  
                dataType: 'json',   
                success: function (response) {
                    console.log("Server response:", response);  
                    try {
                        if (rodzaj === 'skierowanie' && response && response.skierowanie) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Skierowanie:</h3>
                        <textarea id="editInput" maxlength="256">${response.skierowanie}</textarea>
                        <div class="edit-buttons">
                            <button class="save-button" onclick="updateData(${id}, 'skierowanie')">Zapisz</button>
                            <button class="cancel-button" onclick="cancelEdit(${id}, 'skierowanie')">Anuluj</button>
                        </div>
                    `;
                        } else if (rodzaj === 'recepta' && response && response.przypisaneLeki) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Recepta:</h3>
                        <textarea id="editInput" maxlength="256">${response.przypisaneLeki}</textarea>
                        <div class="edit-buttons">
                            <button class="save-button" onclick="updateData(${id}, 'recepta')">Zapisz</button>
                            <button class="cancel-button" onclick="cancelEdit(${id}, 'recepta')">Anuluj</button>
                        </div>
                    `;
                        } else if (rodzaj === 'wpis' && response && response.wpis) {
                            document.getElementById('elementDetails').innerHTML = `
                        <h3>Wpis:</h3>
                        <textarea id="editInput" maxlength="256">${response.wpis}</textarea>
                        <div class="edit-buttons">
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
                    console.log("AJAX error:", error); 
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



function checkAllergy() {
    const input = document.getElementById('allergyInput').value;
    if (input.length > 2) { 
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
        return false; 
    }
    return true;
}


document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (event) {
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
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nową receptę</h2>
                <form action="insert_data_recepty.php" method="post">
                    <label for="elementName">Przypisywane Leki:</label>
                    <input type="text" id="elementName" name="RecipeInfo" maxlength="256">
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
                    <textarea id="elementDetailsTextarea" name="referralDetails" maxlength="256"></textarea>
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
        }
        else if (event.target && event.target.id === 'addPapersButton') {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('elementDetails').innerHTML = `
                <h2>Dodaj nowe Wyniki</h2>
                <form action="insert_data_wyniki.php" method="post" enctype="multipart/form-data">
                    <label for="elementName">Wyniki Badania:</label>
                    <textarea id="elementDetailsTextarea" name="examinationDetails" maxlength="256"></textarea>
                    <label for="elementDetailsTextarea">Data Przeprowadzenia Wyników:</label>
                    <input type="date" id="elementDetailsTextarea" name="examinationDate" value="${today}" max="${today}">
                    <label for="fileInput">Załącz plik:</label>
                    <input type="file" id="fileInput" name="plik" accept=".jpg,.jpeg,.png,.pdf">
                    <button type="submit" class="button">Dodaj</button>
                </form>
            `;
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

