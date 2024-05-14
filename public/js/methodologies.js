let professorMethodologies = [];
let professorMethodologiesIndex = -1;
function renderProfessorMethodologies() {
    let html = "";
    professorMethodologies.forEach(function (element, index) {
        html +=
            "<strong class='badge  badge-primary mr-2' style='cursor:pointer;' data-toggle='modal' data-target='#methodology-professor-view" + "' onclick='onClickMethodology(" + index + ")'>" +
            element.methodology_name +
            "</strong>";
    });

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

    if (userIdProfessor == null || userIdProfessor == professorMethodologies[professorMethodologiesIndex].professor_methodology_id) {
        updateProfessorMethodologyDescription(event, professorMethodologiesIndex);
    }
}

function updateMethodologyDescription(event, professorMethodologiesIndex) {
    let newMethodologyDescription = document.querySelector('#methodology-description').value;
    let feedbackAlertDiv = document.querySelector('#feedback-methodology');
    let feedbackMessage = document.querySelector('#feedback-methodology-message');

    $.ajax({
        url: '/metodologias/update/' + professorMethodologies[professorMethodologiesIndex].methodology_id,
        method: 'PUT',
        dataType: 'json',
        data: {
            '_token': token,
            'name': professorMethodologies[professorMethodologiesIndex].methodology_name,
            'description': newMethodologyDescription
        },
        success: function (data) {
            feedbackMessage.innerHTML = "Atualizado com sucesso!"
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-success');
            feedbackAlertDiv.classList.remove('alert-danger');
            professorMethodologies[professorMethodologiesIndex].methodology_description = newMethodologyDescription;

        },
        error: function (xhr, status, error) {
            feedbackMessage.innerHTML = "Erro ao atualizar.";
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-danger');
            feedbackAlertDiv.classList.remove('alert-success');
        }

    });
}

function updateProfessorMethodologyDescription(event, professorMethodologiesIndex) {
    let newMethodologyDescription = document.querySelector('#methodology-use-description').value;
    let feedbackAlertDiv = document
        .querySelector('#feedback-professor-methodology');
    let feedbackMessage = document
        .querySelector('#feedback-professor-methodology-message');

    $.ajax({
        url: '/metodologias/professor/update/' + professorMethodologies[professorMethodologiesIndex].id,
        method: 'PUT',
        dataType: 'json',
        data: {
            '_token': token,
            'description': newMethodologyDescription
        },
        success: function (data) {
            feedbackMessage.innerHTML = "Atualizado com sucesso!"
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-success');
            feedbackAlertDiv.classList.remove('alert-danger');
            professorMethodologies[professorMethodologiesIndex].methodology_use_description = newMethodologyDescription;

        },
        error: function (xhr, status, error) {
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
    let feedBackDeleteMethodology = document.querySelector('#feedback-delete-methodology');
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
    methodologyName.innerHTML = professorMethodology.methodology_name;
    methodologyDescription.value = professorMethodology.methodology_description;
    methodologyUseDescription.value = professorMethodology.methodology_use_description;
    if (userIdProfessor == null || (userIdProfessor == professorMethodology.methodology_owner)) {
        methodologyDescription.readOnly = false;
        methodologyDescription.classList.remove('text-secondary');
        methodologyDescription.classList.add('text-primary');
        btnDeleteMethodology.classList.remove('d-none');

    } else {
        methodologyDescription.readOnly = true;
        methodologyDescription.classList.remove('text-primary');
        methodologyDescription.classList.add('text-secondary');
        btnDeleteMethodology.classList.add('d-none');
    }

    if (userIdProfessor == null || professorId == userIdProfessor) {
        methodologyUseDescription.readOnly = false;
        methodologyUseDescription.classList.remove('text-secondary');
        methodologyUseDescription.classList.add('text-primary');
        btnRemoveMethodology.classList.remove('d-none');
    } else {
        methodologyUseDescription.readOnly = true;
        methodologyUseDescription.classList.remove('text-primary');
        methodologyUseDescription.classList.add('text-secondary');
        btnRemoveMethodology.classList.add('d-none');
    }

}

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

function addSelectedMethodologies() {
    let methodologiesToSave = [];
    methodologiesToChoose.forEach(function (methodology) {
        if (methodology.markedToSave) {
            methodology.discipline_code = disciplineCode;
            methodology.professor_methodology_id = professorId;
            methodologiesToSave.push(methodology);
            delete methodology.markedToSave;
            delete methodology.markedToAdd;
        }
    });
    $.ajax({
        url: '/disciplinas/metodologias/adicionar/',
        method: 'post',
        data: {
            '_token': token,
            'discipline_id': disciplineId,
            'methodologies_array': methodologiesToSave
        },
        success: function (data) {
            $('#modal-cadastro-metodologia').modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
           
            let feedbackElement = document.querySelector('#feedback-add-methodology');
            feedbackElement.classList.remove('text-success');
            feedbackElement.classList.add('text-danger');
            feedbackElement.classList.remove('d-none');
            let errorJSON = JSON.parse(xhr.responseText);
            console.log(errorJSON);
            if (errorJSON) {
                feedbackElement.innerHTML = errorJSON.error;
            }
            else {
                feedbackElement.innerHTML = 'Não foi possível adicionar as metodologias.';
            }

        }
    });
}

function deleteMethodology() {
    idMethodology = professorMethodologies[professorMethodologiesIndex].methodology_id;
    $.ajax({
        url: '/metodologias/delete/' + idMethodology,
        method: 'delete',
        data: {
            '_token': token,
        },
        success: function (data) {
            $('#methodology-professor-view').modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            errorJSON = JSON.parse(xhr.responseText);
            if (errorJSON) {
                document.querySelector('#feedback-delete-methodology-message').innerHTML = errorJSON.error;
            }
            document.querySelector('#feedback-delete-methodology').classList.remove('alert-success');
            document.querySelector('#feedback-delete-methodology').classList.add('alert-danger');
            document.querySelector('#feedback-delete-methodology').classList.remove('d-none');


        }
    });
}

function removeProfessorMethodology() {
    let idProfessorMethodology = professorMethodologies[professorMethodologiesIndex].id;
    $.ajax({
        url: '/disciplinas/metodologias/remove/' + disciplineId + '/' + idProfessorMethodology,
        method: 'delete',
        data: {
            '_token': token,
        },
        success: function (data) {
            $('#methodology-professor-view').modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            errorJSON = JSON.parse(xhr.responseText);
            if (errorJSON) {
                document.querySelector('#feedback-delete-methodology-message').innerHTML = errorJSON.error;
            }
            document.querySelector('#feedback-delete-methodology').classList.remove('alert-success');
            document.querySelector('#feedback-delete-methodology').classList.add('alert-danger');
            document.querySelector('#feedback-delete-methodology').classList.remove('d-none');
        }
    });
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
    $.ajax({
        url: '/metodologias/store/',
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
            openModalAddMethodologies();
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
                console.log(jsonError);
                feedbackRegisterMethodology.innerHTML = jsonError.error;
            }

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

