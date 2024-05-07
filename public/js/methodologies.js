let professorMethodologies = [];
function renderProfessorMethodologies() {
    let html = "";
    professorMethodologies.forEach(function (element, index) {
        html +=
            "<strong class='badge  badge-primary mr-2' style='cursor:help;' data-toggle='modal' data-target='#methodology-" + element.id + "' onclick='onClickMethodology(" + element.id + ")'>" +
            element.methodology_name +
            "</strong>";
        html +=
            "<div class='modal fade' tabindex='-1' role='dialog' id='methodology-" + element.id + "'>" +
            "<div class='modal-dialog' role='document'>" +
            "<div class='modal-content'>" +
            "<div class='modal-header'>" +
            "<h3 class='modal-title text-primary'>" + element.methodology_name + "</h3>" +
            "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>" +
            "<span aria-hidden='true'>&times;</span>" +
            "</button>" +
            "</div>" +
            "<div class='modal-body'>" +
            "<div class='d-flex flex-column'>" +
            "<div class='d-flex justify-content-end'>" +
            "<button class='btn btn-outline-danger btn-sm' onclick='deleteProfessorMethodology(" + element.id + ")'> Remover metodologia</button>";
        if (professorId == element.methodology_owner) {
            html +=
                "<button class='btn btn-danger btn-sm ml-2' onclick='deleteMethodology(" + element.methodology_id + "," + element.id + ")'>Apagar metodologia</button>";
        }
        html +=
            "</div>" +
            "<div id='feedback-delete-methodology-" + element.id + "' class='alert alert-dismissible  d-none mt-2'>" +
            "<small id='feedback-delete-methodology-message-" + element.id + "'>Não foi deletar a metodologia</small>" +
            "<button class='close' onclick=\"closeAlert('feedback-delete-methodology-" + element.id + "')\">&times</button></small></div>" +
            "<small class='text-secondary'>descrição da metodologia</small>" +
            "<textarea id='methodology-description-" + index + "' rows='4' ";
        if (professorId != element.methodology_owner) {
            html += "readonly class='text-primary' style='background-color: #F8F8F8FF; resize: none' > ";
        } else {
            html += "class='text-primary'> "
        }
        html +=
            element.methodology_description + "</textarea>" +
            "<div id='feedback-methodology-" + element.id + "' class='d-none alert  mt-2'>" +
            "<span id='feedback-methodology-message-" + element.id + "' style='text-align:center'>Erro ao atualizar</span>" +
            "<button class='close' onclick=\"closeAlert('feedback-methodology-" + element.id + "')\">&times</button>" +
            "</div>" +
            "</div>" +
            "<hr>" +
            "<div class='d-flex flex-column'>" +
            "<small class='text-secondary'>Como o professor aplica a metodologia</small>" +
            "<textarea id='professor-methodology-description-" + index + "' class='text-primary' rows='10'>" + element.professor_description + "</textarea>" +
            "<div id='feedback-professor-methodology-" + element.id + "' class='d-none alert  mt-2'>" +
            "<span id='feedback-professor-methodology-message-" + element.id + "' style='text-align:center'>Erro ao atualizar</span>" +
            "<button class='close' onclick=\"closeAlert('feedback-professor-methodology-" + element.id + "')\">&times</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "<div class='modal-footer'>" +
            "<button class='btn btn-success btn-sm' onclick='updateMethodologyAndProfessorMethodology(event," + index + ")'>Salvar</button>" +
            "<button type='button' class='btn btn-sm btn-primary' data-dismiss='modal'>Fechar</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</div>";
    });

    document.querySelector('#metodologias').innerHTML = html;

}

function getProfessorMethodologies() {
    $.ajax({
        url: '/metodologias/professor/' + professorId + '/' + disciplineCode,
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

function updateMethodologyAndProfessorMethodology(event, professorMethodologiesIndex) {
    if (professorId == professorMethodologies[professorMethodologiesIndex].methodology_owner) {
        updateMethodologyDescription(event, professorMethodologiesIndex);
    }
    updateProfessorMethodologyDescription(event, professorMethodologiesIndex);
}

function updateMethodologyDescription(event, professorMethodologiesIndex) {
    let newMethodologyDescription = document.querySelector('#methodology-description-' + professorMethodologiesIndex).value;
    let feedbackAlertDiv = document
        .querySelector('#feedback-methodology-' + professorMethodologies[professorMethodologiesIndex].id);
    let feedbackMessage = document
        .querySelector('#feedback-methodology-message-' + professorMethodologies[professorMethodologiesIndex].id);

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
    let newMethodologyDescription = document.querySelector('#professor-methodology-description-' + professorMethodologiesIndex).value;
    let feedbackAlertDiv = document
        .querySelector('#feedback-professor-methodology-' + professorMethodologies[professorMethodologiesIndex].id);
    let feedbackMessage = document
        .querySelector('#feedback-professor-methodology-message-' + professorMethodologies[professorMethodologiesIndex].id);

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
            professorMethodologies[professorMethodologiesIndex].professor_description = newMethodologyDescription;

        },
        error: function (xhr, status, error) {
            feedbackMessage.innerHTML = "Erro ao atualizar.";
            feedbackAlertDiv.classList.remove('d-none');
            feedbackAlertDiv.classList.add('alert', 'alert-danger');
            feedbackAlertDiv.classList.remove('alert-success');
        }

    });
}

function onClickMethodology(id) {
    let feedBackMethodology = document.querySelector('#feedback-methodology-' + id);
    let feedBackProfessorMethodology = document.querySelector('#feedback-professor-methodology-' + id);
    let feedBackDeleteMethodology = document.querySelector('#feedback-delete-methodology-' + id);
    feedBackMethodology.classList.add("d-none");
    feedBackProfessorMethodology.classList.add("d-none");
    feedBackDeleteMethodology.classList.add("d-none");
    feedBackDeleteMethodology.classList.remove("show");
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
        url: '/metodologias/professor/store/mult',
        method: 'post',
        data: {
            '_token': token,
            'methodologies_array': methodologiesToSave
        },
        success: function (data) {
            $('#modal-cadastro-metodologia').modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            console.log("error");
        }
    });

}

function deleteMethodology(idMethodology, idModal) {
    $.ajax({
        url: '/metodologias/delete/' + idMethodology,
        method: 'delete',
        data: {
            '_token': token,
        },
        success: function (data) {
            $('#methodology-' + idModal).modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            errorJSON = JSON.parse(xhr.responseText);
            if (errorJSON) {
                document.querySelector('#feedback-delete-methodology-message-' + idModal).innerHTML = errorJSON.error;
            }
            document.querySelector('#feedback-delete-methodology-' + idModal).classList.remove('alert-success');
            document.querySelector('#feedback-delete-methodology-' + idModal).classList.add('alert-danger');
            document.querySelector('#feedback-delete-methodology-' + idModal).classList.remove('d-none');


        }
    });

}

function deleteProfessorMethodology(idProfessorMethodology) {
    $.ajax({
        url: '/metodologias/professor/delete/' + idProfessorMethodology,
        method: 'delete',
        data: {
            '_token': token,
        },
        success: function (data) {
            $('#methodology-' + idProfessorMethodology).modal('hide');
            getProfessorMethodologies();
        },
        error: function (xhr, status, error) {
            errorJSON = JSON.parse(xhr.responseText);
            if (errorJSON) {
                document.querySelector('#feedback-delete-methodology-message-' + idProfessorMethodology).innerHTML = errorJSON.error;
            }
            document.querySelector('#feedback-delete-methodology-' + idProfessorMethodology).classList.remove('alert-success');
            document.querySelector('#feedback-delete-methodology-' + idProfessorMethodology).classList.add('alert-danger');
            document.querySelector('#feedback-delete-methodology-' + idProfessorMethodology).classList.remove('d-none');


        }
    });
}

function closeAlert(idAlert) {
    document.querySelector('#' + idAlert).classList.add('d-none');
}

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
            //$('#modal-cadastro-metodologia').modal('hide');
            let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
            feedbackRegisterMethodology.innerHTML = 'Metodologia cadastrada';
            feedbackRegisterMethodology.classList.remove('d-none');
            feedbackRegisterMethodology.classList.remove('text-danger');
            feedbackRegisterMethodology.classList.add('text-success');
            openModalAddMethodologies();
        },
        error: function (xhr, status, error) {
            let feedbackRegisterMethodology = document.querySelector('#feedback-cadastro-methodology');
            feedbackRegisterMethodology.innerHTML = 'Erro ao cadastrar';
            feedbackRegisterMethodology.classList.remove('text-success');
            feedbackRegisterMethodology.classList.add('text-danger');
            feedbackRegisterMethodology.classList.remove('d-none');
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

