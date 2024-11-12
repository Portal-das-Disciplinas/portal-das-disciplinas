function renderDisciplineContents(elementId){
    let html =
        `<h1 data-toggle="collapse" data-target="#collapseConteudos">
            Conteúdos
            <li name="caret-icon-conteudos" class="fa fa-caret-down"></li>
        </h1> \n \
        <div class="collapse" id="collapseConteudos">
        <div class="card"> \n \
        <div class="card-header d-flex justify-content-between"> \n \
        <div> \n \
        <h3 class="text-primary">Ementa</h3> \n \
        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-new-subject-topic" onclick="addContentClickEvent(event)">
            <i class= "fas fa-solid fa-plus"></i>&nbsp;Adicionar
        </button> \n \
        </div>`;

    if (subjectTopics.length > 3) {
        html += "<a id='seeMoreTopics' class='link' data-toggle='collapse' href='#collapseTopics' role='button' " +
            " aria-expanded='false' aria-controls='collapseTopics'>" +
            "ver mais " +
            "</a>";
    }
    html+= "</div>";
    if (subjectTopics.length <= 3) {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < subjectTopics.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectTopics[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteTopic(event," + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>"
    } else {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < 3; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectTopics[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteTopic(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>";
        html+= "<div class='collapse' id='collapseTopics'>" +
            "<ul class='list-group list-group-flush'>";
        for (i = 3; i < subjectTopics.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectTopics[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteTopic(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
            "</li>";
        }
        html += "</ul>" +
            " </div>";
    }
    html += "</div>";

    html+= "<div class='card mt-2'>" +
        "<div class='card-header d-flex justify-content-between'>" +
        "<div>"+
        "<h3 class='text-primary'>Conceitos</h3>"+
        "<button class='btn btn-sm btn-success' data-toggle='modal' data-target='#modal-new-subject-concept' onclick='addContentClickEvent(event)'><i class= 'fas fa-solid fa-plus'></i>&nbsp;Adicionar</button>"+
        "</div>";

    if (subjectConcepts.length > 3) {
        html += "<a id='seeMoreConcepts' class='link' data-toggle='collapse' href='#collapseConcepts' role='button' " +
            " aria-expanded='false' aria-controls='collapseConcepts'>" +
            "ver mais " +
            "</a>";
    }
    html+= "</div>";
    if (subjectConcepts.length <= 3) {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < subjectConcepts.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectConcepts[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteConcept(event," + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>"
    } else {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < 3; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectConcepts[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteConcept(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>";
        html+= "<div class='collapse' id='collapseConcepts'>" +
            "<ul class='list-group list-group-flush'>";
        for (i = 3; i < subjectConcepts.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectConcepts[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteConcept(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
            "</li>";
        }
        html += "</ul>" +
            " </div>";
    }
    html += "</div>";

    html+= "<div class='card mt-2'>" +
        "<div class='card-header d-flex justify-content-between'>" +
        "<div>"+
        "<h3 class='text-primary'>Referências</h3>"+
        "<button class='btn btn-sm btn-success' data-toggle='modal' data-target='#modal-new-subject-reference' onclick='addContentClickEvent(event)'><i class= 'fas fa-solid fa-plus'></i>&nbsp;Adicionar</button>"+
        "</div>";

    if (subjectReferences.length > 3) {
        html += "<a id='seeMoreReferences' class='link' data-toggle='collapse' href='#collapseReferences' role='button' " +
            " aria-expanded='false' aria-controls='collapseReferences'>" +
            "ver mais " +
            "</a>";
    }
    html+= "</div>";
    if (subjectReferences.length <= 3) {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < subjectReferences.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectReferences[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteReference(event," + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>"
    } else {
        html +=
            "<ul class='list-group list-group-flush'>";
        for (i = 0; i < 3; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectReferences[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteReference(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
                "</li>";
        }
        html += "</ul>";
        html+= "<div class='collapse' id='collapseReferences'>" +
            "<ul class='list-group list-group-flush'>";
        for (i = 3; i < subjectReferences.length; i++) {
            html +=
                "<li class='list-group-item'>" +
                "<div class='d-flex flex-column'>"+
                    "<small>" + subjectReferences[i].value + "</small>" +
                    "<div class='d-flex justify-content-end'>"+
                    "<small style='cursor:pointer' class='text-danger' onclick='deleteReference(event, " + i + ")'>excluir</small>"+
                    "</div>"+
                "</div>"+
            "</li>";
        }
        html += "</ul>" +
            " </div>";
    }
    html += "</div></div>";



    document.querySelector(elementId).innerHTML = html;
}

$('#modal-new-subject-topic').on('show.bs.modal',function(e){
    let feedbackDiv = document.querySelector('#feedback-new-topic');
    feedbackDiv.classList.add('d-none');
});

$('#modal-new-subject-concept').on('show.bs.modal',function(e){
    let feedbackDiv = document.querySelector('#feedback-new-concept');
    feedbackDiv.classList.add('d-none');
});

$('#modal-new-subject-reference').on('show.bs.modal',function(e){
    let feedbackDiv = document.querySelector('#feedback-new-reference');
    feedbackDiv.classList.add('d-none');
});

function addContentClickEvent(event){
    event.preventDefault();
}

function saveTopic(event){
    let modal = document.querySelector('#modal-new-subject-topic');
    let inputTopicName = document.querySelector('#new-topic-name');
    let feedbackDiv = document.querySelector('#feedback-new-topic');
    let feedbackMessage = document.querySelector('#feedback-new-topic-message');
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/temas/salvar',
        method:'POST',
        data:{
            '_token':token,
            'topic':inputTopicName.value,
            'discipline_id':disciplineId
        },
        success:function(data){
            event.target.disabled = false;
            inputTopicName.value = "";
            subjectTopics.push(data);
            renderDisciplineContents('#discipline-contents');
            $('#modal-new-subject-topic').modal('hide');
        },
        error:function(xhr,status,error){
            event.target.disabled = false;
            inputTopicName.value = "";
            feedbackDiv.classList.remove('alert-success','d-none')
            feedbackDiv.classList.add('show','alert-danger');
            feedbackMessage.innerHTML = JSON.parse(xhr.responseText).error;
        }
    });
}

function saveConcept(event){
    let modal = document.querySelector('#modal-new-subject-concept');
    let inputConceptName = document.querySelector('#new-concept-name');
    let feedbackDiv = document.querySelector('#feedback-new-concept');
    let feedbackMessage = document.querySelector('#feedback-new-concept-message');
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/conceitos/salvar',
        method:'POST',
        data:{
            '_token':token,
            'concept':inputConceptName.value,
            'discipline_id':disciplineId
        },
        success:function(data){
            event.target.disabled = false;
            inputConceptName.value = "";
            subjectConcepts.push(data);
            renderDisciplineContents('#discipline-contents');
            $('#modal-new-subject-concept').modal('hide');
        },
        error:function(xhr,status,error){
            event.target.disabled = false;
            inputConceptName.value = "";
            feedbackDiv.classList.remove('alert-success','d-none')
            feedbackDiv.classList.add('show','alert-danger');
            feedbackMessage.innerHTML = JSON.parse(xhr.responseText).error;
        }
    });
}

function saveReference(event){
    let modal = document.querySelector('#modal-new-subject-reference');
    let inputReferenceName = document.querySelector('#new-reference-name');
    let feedbackDiv = document.querySelector('#feedback-new-reference');
    let feedbackMessage = document.querySelector('#feedback-new-reference-message');
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/referencias/salvar',
        method:'POST',
        data:{
            '_token':token,
            'reference':inputReferenceName.value,
            'discipline_id':disciplineId
        },
        success:function(data){
            event.target.disabled = false;
            inputReferenceName.value = "";
            subjectReferences.push(data);
            renderDisciplineContents('#discipline-contents');
            $('#modal-new-subject-reference').modal('hide');
        },
        error:function(xhr,status,error){
            event.target.disabled = false;
            inputReferenceName.value = "";
            feedbackDiv.classList.remove('alert-success','d-none')
            feedbackDiv.classList.add('show','alert-danger');
            feedbackMessage.innerHTML = JSON.parse(xhr.responseText).error;
        }
    });
}

function deleteTopic(event, elementIndex){
    let id = subjectTopics[elementIndex].id;
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/temas/delete/' + id,
        method:'DELETE',
        data:{
            '_token': token
        },

        success:function(data){
            event.target.disabled = false;
            subjectTopics = subjectTopics.filter(function(element){
                return element.id != id;
            });
            renderDisciplineContents('#discipline-contents');
        },

        error:function(xhr,status,error){
            event.target.disabled = false;
            alert("Um erro aconteceu");
        }
    });
}

function deleteConcept(event, elementIndex){
    let id = subjectConcepts[elementIndex].id;
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/conceitos/delete/' + id,
        method:'DELETE',
        data:{
            '_token': token
        },

        success:function(data){
            event.target.disabled = false;
            subjectConcepts = subjectConcepts.filter(function(element){
                return element.id != id;
            });
            renderDisciplineContents('#discipline-contents');
        },

        error:function(xhr,status,error){
            event.target.disabled = false;
            alert("Um erro aconteceu");
        }
    });
}

function deleteReference(event, elementIndex){
    let id = subjectReferences[elementIndex].id;
    event.target.disabled = true;
    $.ajax({
        url:'/conteudos/referencias/delete/' + id,
        method:'DELETE',
        data:{
            '_token': token
        },

        success:function(data){
            event.target.disabled = false;
            subjectReferences = subjectReferences.filter(function(element){
                return element.id != id;
            });
            renderDisciplineContents('#discipline-contents');
        },

        error:function(xhr,status,error){
            event.target.disabled = false;
            alert("Um erro aconteceu");
        }
    });
}
if(document.querySelector('#discipline-contents')){
    renderDisciplineContents('#discipline-contents');
}

$('#collapseConteudos').on('show.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-conteudos]').removeClass('fa fa-caret-down');
    $('li[name=caret-icon-conteudos]').addClass('fa fa-caret-up');
});

$('#collapseConteudos').on('hide.bs.collapse', function (event) {
    event.stopPropagation();
    $('li[name=caret-icon-conteudos]').removeClass('fa fa-caret-up');
    $('li[name=caret-icon-conteudos]').addClass('fa fa-caret-down');
});
