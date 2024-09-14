let professorMethodologies = [];
let professorMethodologiesIndex = -1;
function renderProfessorMethodologies() {
    document.querySelector("#selected-professor-methodologies").value = JSON.stringify(professorMethodologies);
    console.log(professorMethodologies);
    let html = "";
    if (professorMethodologies.length > 0) {
        html+= "<small class='text-secondary'>Clique no nome das metodologias para adicionar mais detalhes</small>";
        professorMethodologies.forEach(function (element, index) {
            html +=
                "<div class='d-flex justify-content-between p-1 mb-2 bg-info' style='border-radius:5px;'>"+
                        "<span class='text-white' data-toggle='modal' data-target='#methodology-professor-view' style='cursor:pointer' onclick='onClickMethodology(" + index + ")'>" + element.methodology_name + "</span>"+
                        "<small class='text-white' style='cursor:pointer' onclick='removeProfessorMethodologyFromList(" + index + ")'>remover</small>"+
                "</div>";
        });
    } else {
        html = "Não há metodologias cadastradas.";
    }

    document.querySelector('#metodologias').innerHTML = html;
}

function getProfessorMethodologies() {
    $.ajax({
        url: '/metodologias/professor/' + professorId + '/' + disciplineId,
        method: 'GET',
        success: function (data) {
            professorMethodologies = data;
            renderProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            alert(error);
        }
    });
}

function updateMethodologyAndProfessorMethodology(event) {
    if (userIdProfessor == null || (userIdProfessor == professorMethodologies[professorMethodologiesIndex].methodology_owner)) {
        updateMethodologyDescription(event, professorMethodologiesIndex);
    }

    /*if (userIdProfessor == null || userIdProfessor == professorMethodologies[professorMethodologiesIndex].professor_methodology_id) {
        updateProfessorMethodologyDescription(event, professorMethodologiesIndex);
    }*/
}

function updateMethodologyDescription(event, professorMethodologiesIndex) {
    let methodologyName = document.querySelector('#methodology-name').value;
    let newMethodologyDescription = document.querySelector('#methodology-description').value;
    let feedbackAlertDiv = document.querySelector('#feedback-methodology');
    let feedbackMessage = document.querySelector('#feedback-methodology-message');

    $.ajax({
        url: '/metodologias/update/' + professorMethodologies[professorMethodologiesIndex].methodology_id,
        method: 'PUT',
        dataType: 'json',
        data: {
            '_token': token,
            'name': methodologyName,
            'description': newMethodologyDescription
        },
        success: function (data) {
            feedbackMessage.innerHTML = "Atualizado com sucesso!"
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-success');
            feedbackAlertDiv.classList.remove('alert-danger');
            professorMethodologies[professorMethodologiesIndex].methodology_name = methodologyName;
            professorMethodologies[professorMethodologiesIndex].methodology_description = newMethodologyDescription;
            renderProfessorMethodologies();

        },
        error: function (xhr, status, error) {
            feedbackMessage.innerHTML = JSON.parse(xhr.responseText).error;
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-danger');
            feedbackAlertDiv.classList.remove('alert-success');
        }

    });
}
/**
 * Atualiza o valor da descrição da aplicação da metodologia pelo professor e também sua propria descrição 
 * do que é a metodologia. 
 */
function updateProfessorMethodologyDescription(event, professorMethodologiesIndex) {
    let newMethodologyDescription = document.querySelector('#methodology-use-description').value;
    let newProfessorMethodologyDescription = document.querySelector('#professor-methodology-description').value;
    let feedbackProfessorMethodologyAlertDiv = document.querySelector('#feedback-methodology');
    let feedbackProfessorMethodologyMessage = document.querySelector('#feedback-methodology-message');
    let feedbackAlertDiv = document
        .querySelector('#feedback-professor-methodology');
    let feedbackMessage = document
        .querySelector('#feedback-professor-methodology-message');
    let professorMethodology = professorMethodologies[professorMethodologiesIndex];

    $.ajax({
        url: '/metodologias/professor/update/' + professorMethodology.id,
        method: 'PUT',
        dataType: 'json',
        data: {
            '_token': token,
            'description': newMethodologyDescription,
            'professor_methodology_description': newProfessorMethodologyDescription
        },
        success: function (data) {
            if (professorMethodology.professor_description != newProfessorMethodologyDescription) {
                feedbackProfessorMethodologyMessage.innerHTML = "A sua descrição da metodologia foi atualizada"
                feedbackProfessorMethodologyAlertDiv.classList.remove('d-none');
                feedbackProfessorMethodologyAlertDiv.classList.add('alert', 'alert-success');
                feedbackProfessorMethodologyAlertDiv.classList.remove('alert-danger');
            }
            feedbackMessage.innerHTML = "Atualizado com sucesso!"
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-success');
            feedbackAlertDiv.classList.remove('alert-danger');
            professorMethodology.methodology_use_description = newMethodologyDescription;
            professorMethodology.professor_description = newProfessorMethodologyDescription;

        },
        error: function (xhr, status, error) {
            if (professorMethodology.professor_description != newProfessorMethodologyDescription) {
                feedbackProfessorMethodologyMessage.innerHTML = "Erro ao atualizar.";
                feedbackProfessorMethodologyAlertDiv.classList.remove('d-none');
                feedbackProfessorMethodologyAlertDiv.classList.add('alert', 'alert-danger');
                feedbackProfessorMethodologyAlertDiv.classList.remove('alert-success');
            }
            feedbackMessage.innerHTML = "Erro ao atualizar.";
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-danger');
            feedbackAlertDiv.classList.remove('alert-success');
        }

    });
}

function onClickMethodology(index) {
    let feedBackMethodology = document.querySelector('#feedback-methodology');
    let feedBackProfessorMethodology = document.querySelector('#feedback-professor-methodology');
    let feedBackDeleteMethodology = document.querySelector('#feedback-delete-remove-methodology');
    feedBackMethodology.classList.add("d-none");
    feedBackProfessorMethodology.classList.add("d-none");
    feedBackDeleteMethodology.classList.add("d-none");
    feedBackDeleteMethodology.classList.remove("show");
    professorMethodologiesIndex = index;
    let professorMethodology = professorMethodologies[index];
    let methodologyName = document.querySelector('#methodology-professor-view #methodology-name');
    let methodologyDescription = document.querySelector('#methodology-professor-view #methodology-description');
    let methodologyUseDescription = document.querySelector('#methodology-professor-view #methodology-use-description');
    let btnRemoveMethodology = document.querySelector('#methodology-professor-view #btn-remove-methodology');
    let btnDeleteMethodology = document.querySelector('#methodology-professor-view #btn-delete-methodology');
    let btnSaveNameAndDescription = document.querySelector('#btn-save-methodology');
    let defaultDescriptionTab = document.querySelector('#tab-default-description');
    let professorDescriptionTab = document.querySelector('#tab-professor-description');
    let professorMethodologyDescription = document.querySelector('#professor-methodology-description');
    methodologyName.value = professorMethodology.methodology_name;
    methodologyDescription.value = professorMethodology.methodology_description;
    methodologyUseDescription.value = professorMethodology.methodology_use_description;
    professorMethodologyDescription.value = professorMethodology.professor_description;
    if (userIdProfessor == null || (userIdProfessor == professorMethodology.methodology_owner)) {
        methodologyDescription.readOnly = false;
        methodologyDescription.classList.remove('text-secondary');
        methodologyDescription.classList.add('text-primary');
        btnDeleteMethodology.classList.remove('d-none');
        methodologyName.disabled = false;
        btnSaveNameAndDescription.classList.remove('d-none');

    } else {
        methodologyDescription.readOnly = true;
        methodologyDescription.classList.remove('text-primary');
        methodologyDescription.classList.add('text-secondary');
        btnDeleteMethodology.classList.add('d-none');
        methodologyName.disabled = true;
        btnSaveNameAndDescription.classList.add('d-none');
    }
    

    if (userIdProfessor == null || (professorId == userIdProfessor)) {
        methodologyUseDescription.readOnly = false;
        methodologyUseDescription.classList.remove('text-secondary');
        methodologyUseDescription.classList.add('text-primary');
        btnRemoveMethodology.classList.remove('d-none');

        professorMethodologyDescription.readOnly = false;
        professorMethodologyDescription.classList.remove('text-secondary');
        professorMethodologyDescription.classList.add('text-primary');

    } else {
        methodologyUseDescription.readOnly = true;
        methodologyUseDescription.classList.remove('text-primary');
        methodologyUseDescription.classList.add('text-secondary');
        btnRemoveMethodology.classList.add('d-none');

        professorMethodologyDescription.readOnly = true;
        professorMethodologyDescription.classList.remove('text-primary');
        professorMethodologyDescription.classList.add('text-secondary');
    }

    if (userIdProfessor != null) {
        professorDescriptionTab.classList.remove('d-none');
        professorMethodologyDescription.classList.remove('d-none');
    } else {
        professorDescriptionTab.classList.add('d-none');
        professorMethodologyDescription.classList.add('d-none');
    }

    if (userIdProfessor == null) {
        professorDescriptionTab.innerHTML = 'Descrição do professor'
    } else {
        professorDescriptionTab.innerHTML = 'Sua descrição'
    }

    if(professorMethodology.methodology_owner == professorId || userIdProfessor == null){
        professorDescriptionTab.classList.add('d-none');
    }else{
        professorDescriptionTab.classList.remove('d-none');
    }
    selectTab(1);
}

function selectTab(tab) {
    if (tab == 1) {
        let defaultDescription = document.querySelector('#methodology-description');
        let professorDescription = document.querySelector('#professor-methodology-description');
        let defaultDescriptionTab = document.querySelector('#tab-default-description');
        let professorDescriptionTab = document.querySelector('#tab-professor-description');
        defaultDescriptionTab.classList.add('active');
        defaultDescription.classList.remove('d-none');
        professorDescription.classList.add('d-none');
        professorDescriptionTab.classList.remove('active');
    } else {
        let defaultDescription = document.querySelector('#methodology-description');
        let professorDescription = document.querySelector('#professor-methodology-description');
        let defaultDescriptionTab = document.querySelector('#tab-default-description');
        let professorDescriptionTab = document.querySelector('#tab-professor-description');
        professorDescriptionTab.classList.add('active');
        defaultDescription.classList.add('d-none');
        professorDescription.classList.remove('d-none');
        defaultDescriptionTab.classList.remove('active');
    }
}


document.querySelector('#tab-default-description').addEventListener('click', function () {
    selectTab(1);
});

document.querySelector('#tab-professor-description').addEventListener('click', function () {
    selectTab(2);
});

document.querySelector('#professor-methodology-description').addEventListener('change',function(event){
    professorMethodologies[professorMethodologiesIndex].professor_description = event.target.value;
    document.querySelector('#selected-professor-methodologies').value = JSON.stringify(professorMethodologies);
    
});

document.querySelector('#methodology-use-description').addEventListener('change',function(event){
    professorMethodologies[professorMethodologiesIndex].methodology_use_description = event.target.value;
    document.querySelector('#selected-professor-methodologies').value = JSON.stringify(professorMethodologies);
});

let methodologiesToChoose = [];
function openModalAddMethodologies() {
    methodologiesToChoose = [];
    $.ajax({
        url: '/metodologias',
        method: 'GET',
        success: function (arrayData) {
            arrayData.forEach(function (element) {
                let addToArray = true;
                element.markedToSave = false;
                for (var i = 0; i < professorMethodologies.length; i++) {
                    if (element.id == professorMethodologies[i].methodology_id) {

                        addToArray = false;
                        break;
                    }
                }
                if (addToArray == true) {
                    methodologiesToChoose.push(element);
                }
            });
            renderMethodologiesToChoose();

        },
        error: function (xhr, status, error) {
            console.log('error');
        }
    });
}

function renderMethodologiesToChoose() {
    let htmlNoMethodologies = "<p class='text-info'><small>Nenhuma metodologia nova encontrada</small></p>" +
        "<p class='text-info'><small>Clique em criar para adicionar uma metodologia</small></p>"
    let html = "";
    methodologiesToChoose.forEach(function (methodology, index) {
        html +=
            "<div class='d-flex mb-2'>" +
            "<input type='checkbox' class='mr-1' onchange=\"onChangeMethodologyCheckbox(event," + index + ")\">" +
            "<span class='badge badge-primary'>" + methodology.name + "</span>" +
            "</div>";
    });
    if (methodologiesToChoose.length > 0) {
        document.querySelector('#methodologiesToChoose').innerHTML = html;
    }
    else {
        document.querySelector('#methodologiesToChoose').innerHTML = htmlNoMethodologies;
    }
}

function onChangeMethodologyCheckbox(event, methodologiesToChooseIndex) {
    methodologiesToChoose[methodologiesToChooseIndex].markedToSave = event.target.checked;
    
}

let methodologiesToSave = [];
function addSelectedMethodologies() {
    methodologiesToChoose.forEach(function (methodology,index) {
        
        if (methodology.markedToSave) {
            methodology.professor_methodology_id = professorId;
            methodologiesToSave.push(methodology);
            let professorMethodology = new Object();
            professorMethodology.methodology_id = methodology.id;
            professorMethodology.methodology_name = methodology.name;
            professorMethodology.methodology_owner = methodology.professor_id;
            if(userIdProfessor != null){
                professorMethodology.professor_methodology_id = userIdProfessor;
            }else{
                professorMethodology.professor_methodology_id = document.querySelector('#professor').value;
            }
            professorMethodology.methodology_description = methodology.description;
            professorMethodology.professor_description = document.querySelector('#professor-methodology-description').value;
            professorMethodology.methodology_use_description = document.querySelector('#methodology-use-description').value;
            professorMethodology.markedToAdd = true;
            professorMethodologies.push(professorMethodology);
            delete methodology.markedToSave;
            delete methodology.markedToAdd;
        }
        
    });
    
    document.querySelector('#selected-professor-methodologies').value= JSON.stringify(professorMethodologies);
    renderProfessorMethodologies();
    $('#modal-cadastro-metodologia').modal('hide');
}

function deleteMethodology() {
    let deleteConfirm = false;
    if (userIdProfessor == null) {
        deleteConfirm = confirm("Tem certeza que deseja apagar esta metodologia?\n" +
            "Ao realizar esta operação, essa metodologia será apagada de todas as páginas que contém esta metodologia."
        );
    }
    else {
        deleteConfirm = confirm("Tem certeza que deseja apagar esta metodologia?\n" +
            "Ao realizar esta operação, as metodologias de todas as suas páginas que contém esta metodologia serão apagadas."
        );
    }

    if (!deleteConfirm) {
        return;
    }
    idMethodology = professorMethodologies[professorMethodologiesIndex].methodology_id;
    let feedback = document.querySelector('#feedback-delete-remove-methodology');
    let feedbackMessage = feedback.querySelector('small');
    let btnSaveMethodology = document.querySelector('#btn-save-methodology');
    let btnCloseModal = document.querySelector('#close-modal-save-methodology');
    feedback.classList.remove('d-none');
    feedback.classList.remove('alert-danger');
    feedback.classList.add('alert-info');
    feedbackMessage.innerHTML = "Apagando metodologia...";
    btnSaveMethodology.disabled = true;
    btnCloseModal.disabled = true;
    $.ajax({
        url: '/metodologias/delete/' + idMethodology,
        method: 'delete',
        data: {
            '_token': token,
        },
        success: function (data) {
            $('#methodology-professor-view').modal('hide');
            professorMethodologies = professorMethodologies.filter(function(element,index){
                return professorMethodologiesIndex != index
            });
            renderProfessorMethodologies();
            feedback.classList.add('d-none');
            feedback.classList.remove('alert-info');
            feedbackMessage.innerHTML = "";
            btnSaveMethodology.disabled = false;
            btnCloseModal.disabled = false;
        },
        error: function (xhr, status, error) {
            errorJSON = JSON.parse(xhr.responseText);
            if (errorJSON) {
               feedbackMessage.innerHTML = errorJSON.error;
            }else{
                feedbackMessage.innerHTML = "Erro ao deletar";
            }
            document.querySelector('#feedback-delete-remove-methodology').classList.remove('alert-success');
            document.querySelector('#feedback-delete-remove-methodology').classList.add('alert-danger');
            document.querySelector('#feedback-delete-remove-methodology').classList.remove('d-none');
            feedback.classList.remove('d-none');
            feedback.classList.remove('alert-info');
            btnSaveMethodology.disabled = false;
            btnCloseModal.disabled = false;

        }
    });
}

function removeProfessorMethodology() {
    
    professorMethodologies = professorMethodologies.filter(function(element,index){
        return index != professorMethodologiesIndex;
    });
    renderProfessorMethodologies();
    $('#methodology-professor-view').modal('hide');
    
}

function removeProfessorMethodologyFromList(selectedIndex){
    professorMethodologies = professorMethodologies.filter(function(element,index){
        return index != selectedIndex;
    });
    renderProfessorMethodologies();
}

function closeAlert(idAlert) {
    document.querySelector('#' + idAlert).classList.add('d-none');
}

$('#modal-cadastro-metodologia').on('hidden.bs.modal', function (event) {
    document.querySelector('#feedback-cadastro-methodology').classList.add('d-none');
    document.querySelector('#feedback-add-methodology').classList.add('d-none');
    $('#collapse-criar-metodologia').collapse('hide');
    document.querySelector('#nome-nova-metodologia').value = "";
    document.querySelector('#descricao-nova-metodologia').value = "";

});

function btnCreateMethodology() {
    let newMethodology = {
        'name': document.querySelector('#nome-nova-metodologia').value,
        'description': document.querySelector('#descricao-nova-metodologia').value,
        'professor_id': professorId
    }
    let btnCreate = document.querySelector("#btn-create-methodology");
    let btnText = btnCreate.innerHTML;
    btnCreate.innerHTML = "Cadastrando metodologia";
    btnCreate.disabled = true;


    $.ajax({
        url: '/metodologias/store',
        method: 'post',
        data: {
            '_token': token,
            'methodology': newMethodology
        },
        success: function (data) {
            let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
            feedbackRegisterMethodology.innerHTML = 'Metodologia cadastrada';
            feedbackRegisterMethodology.classList.remove('d-none');
            feedbackRegisterMethodology.classList.remove('text-danger');
            feedbackRegisterMethodology.classList.add('text-success');
            document.querySelector('#nome-nova-metodologia').value = "";
            document.querySelector('#descricao-nova-metodologia').value = "";
            openModalAddMethodologies();
            btnCreate.disabled = false;
            btnCreate.innerHTML = btnText;
        },
        error: function (xhr, status, error) {
            let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
            feedbackRegisterMethodology.classList.remove('text-success');
            feedbackRegisterMethodology.classList.add('text-danger');
            feedbackRegisterMethodology.classList.remove('d-none');
            let jsonError = JSON.parse(xhr.responseText);
            if (jsonError) {
                feedbackRegisterMethodology.innerHTML = jsonError.error;
            } else {
                feedbackRegisterMethodology.innerHTML = jsonError.error;
            }
            btnCreate.disabled = false;
            btnCreate.innerHTML = btnText;

        }
    });
}

function clearCreateMethodologyInputs() {
    let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
    feedbackRegisterMethodology.innerHTML = '';
    feedbackRegisterMethodology.classList.add('d-none');
    document.querySelector('#nome-nova-metodologia').value = "";
    document.querySelector('#descricao-nova-metodologia').value = "";
}

