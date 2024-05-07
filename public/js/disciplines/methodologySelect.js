let selectedMethodologies = [];
let selectedMethodologyIndex = -1;
function onClickMethodology(methodologyIndex) {
    selectedMethodologyIndex = methodologyIndex;
    if (selectedMethodologies.includes(methodologies[methodologyIndex])) {
        selectedMethodologies = selectedMethodologies.filter(function (methodology) {
            return methodology.id != methodologies[methodologyIndex].id;
        });
        document.querySelector('#methodology-' + selectedMethodologyIndex).classList.remove('badge-success');
        document.querySelector('#methodology-' + selectedMethodologyIndex).classList.add('badge-secondary');
        document.querySelector('#filteredMethodologies').value = JSON.stringify(selectedMethodologies);

    }
    else {
        document.querySelector('#methodology-name').innerHTML = methodologies[methodologyIndex].name;
        document.querySelector('#methodology-description').innerHTML = methodologies[methodologyIndex].description;
        $('#modal-methodology-info').modal('show');

    }


}

function addMethodologyToSelected() {
    selectedMethodologies.push(methodologies[selectedMethodologyIndex]);
    document.querySelector('#methodology-' + selectedMethodologyIndex).classList.remove('badge-secondary');
    document.querySelector('#methodology-' + selectedMethodologyIndex).classList.add('badge-success');
    document.querySelector('#filteredMethodologies').value = JSON.stringify(selectedMethodologies);
    $('#modal-methodology-info').modal('hide');
}

