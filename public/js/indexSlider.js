// function returnValue (object, value) {
//     let p = object.slice(5);
//     $("#"+p).html(" > " + value + "%");
// };

/**
 * Manipula a entrada de dados, atualizando elementos HTML com o valor fornecido.
 *
 * @param {string} object - Identificador do objeto HTML.
 * @param {number} value - Valor a ser exibido.
 */
function handleInput(object, value) {
    let p = object.slice(5);
    let aux = 100 - parseInt(value);

    if(value == -1){
        $("#"+p).html(" > 0%");
        $("#"+"-"+p).html(" > 0%");
    } else {
        $("#"+p).html(" > " + value + "%");
        $("#"+"-"+p).html(" > " + aux + "%");
    }
}

/**
 * Alterna a exibição de caixas de seleção com base no estado do checkbox.
 *
 * @param {HTMLInputElement} checkbox - Caixa de seleção principal.
 * @param {string} divSecondary - ID da div secundária a ser alternada.
 * @param {string} range - ID do intervalo de entrada a ser alternado.
 */
function toggleCheckboxes(checkbox, divSecondary, range) {

    const secondaryDiv = document.getElementById(divSecondary);
    const rangeInput = document.getElementById(range);
    if (checkbox.checked) {
        // Se o checkbox foi marcado
        secondaryDiv.style.display = 'none';
        rangeInput.style.display = 'block';
    } else {
        // Se o checkbox foi desmarcado
        secondaryDiv.style.display = 'block';
        rangeInput.style.display = 'none';
        handleInput(range, -1);
    }
}

