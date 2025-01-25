let selectedMethodologies = [];
let selectedMethodologyIndex = -1;
function onClickMethodology(methodologyIndex) {
    selectedMethodologyIndex = methodologyIndex;

    if (containMethodologyInArray(methodologies[methodologyIndex].id, selectedMethodologies)) {
        selectedMethodologies = selectedMethodologies.filter(function (methodology) {
            return methodology.id != methodologies[methodologyIndex].id;
        });
        changeMethodologyClassToNotSelected(selectedMethodologyIndex);
        document.querySelector('#filteredMethodologies').value = JSON.stringify(selectedMethodologies);

    }
    else {
        console.log("no contain" + methodologyIndex + " " + methodologies[methodologyIndex].id);
        document.querySelector('#methodology-name').innerHTML = methodologies[methodologyIndex].name;
        document.querySelector('#methodology-description').innerHTML = methodologies[methodologyIndex].description;
        $('#modal-methodology-info').modal('show');

    }
    console.log(selectedMethodologies);
}

function addMethodologyToSelected() {
    selectedMethodologies.push(methodologies[selectedMethodologyIndex]);
    changeMethodologyClassToSelected(selectedMethodologyIndex);
    document.querySelector('#filteredMethodologies').value = JSON.stringify(selectedMethodologies);
    $('#modal-methodology-info').modal('hide');
}

function setFilteredMethodologies(filteredMethodologies){
    let oldFilteredMethodologies = JSON.parse(filteredMethodologies);
    if(oldFilteredMethodologies != null){
        selectedMethodologies = oldFilteredMethodologies;
        for(i = 0;i< methodologies.length; i++){
            for(j = 0; j < selectedMethodologies.length; j++){
                if(methodologies[i].id == selectedMethodologies[j].id){
                    changeMethodologyClassToSelected(i);
                    break;
                }
            }
        }
        document.querySelector('#filteredMethodologies').value = JSON.stringify(selectedMethodologies);
    }
}

function containMethodologyInArray(id, methodologyArray ){
    console.log(methodologyArray);
    let contain = false;
    methodologyArray.forEach(methodology=>{
        if(methodology.id == id){
            contain = true;
            return;
        }
    });
    return contain;
}

function changeMethodologyClassToSelected(index){
    document.querySelector('#methodology-' + index).classList.remove('badge-secondary');
    document.querySelector('#methodology-' + index).classList.add('badge-success');
}

function changeMethodologyClassToNotSelected(index){
    document.querySelector('#methodology-' + index).classList.remove('badge-success');
    document.querySelector('#methodology-' + index).classList.add('badge-secondary');
}

function clearSelectedMethodologies(){
    console.log(methodologies);
    for(i=0; i < methodologies.length;i++){
        changeMethodologyClassToNotSelected(i);
    }
    selectedMethodologies = [];
    document.querySelector('#filteredMethodologies').value = null;
}

