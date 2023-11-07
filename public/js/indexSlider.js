// function returnValue (object, value) {
//     let p = object.slice(5);
//     $("#"+p).html(" > " + value + "%");
// };

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

