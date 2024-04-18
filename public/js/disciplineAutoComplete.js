var searchInput = document.getElementById('name_discipline');
var autocompleteResults = document.getElementById('autocomplete-results');

// Event listener para o campo de entrada para acionar o autocompletar
searchInput.addEventListener('input', function() {
    var query = this.value.trim();
    if (query.length > 0) {
        searchDisciplines(query);
    } else {
        autocompleteResults.innerHTML = '';
    }
});

// Função para buscar disciplinas
function searchDisciplines(query) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                displayAutocompleteResults(JSON.parse(xhr.responseText));
            } else {
                console.error('Error fetching autocomplete results:', xhr.statusText);
            }
        }
    };
    xhr.open('GET', '/autocomplete_disciplinas?query=' + query);
    xhr.send();
}

// Função para exibir os resultados do autocompletar
// Função para exibir os resultados do autocompletar
function displayAutocompleteResults(results) {
    autocompleteResults.innerHTML = ''; // Limpa os resultados antigos
    results.forEach(function(result) {
        var resultItem = document.createElement('div');
        resultItem.textContent = result.name;
        resultItem.classList.add('autocomplete-result');
        resultItem.addEventListener('click', function() {
            searchInput.value = result.name;
            autocompleteResults.innerHTML = '';
        });

        // Adiciona os ouvintes de hover
        resultItem.addEventListener('mouseenter', function() {
            resultItem.classList.add('hovered');
        });
        resultItem.addEventListener('mouseleave', function() {
            resultItem.classList.remove('hovered');
        });

        autocompleteResults.appendChild(resultItem);
    });
}

