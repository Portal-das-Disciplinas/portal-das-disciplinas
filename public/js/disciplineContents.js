function renderDisciplineContents(elementId){
    let html =
        "<h1>Conteúdos</h1>" +
        "<div class='card'>" +
        "<div class='card-header d-flex justify-content-between'>" +
        "<div>"+
        "<h3 class='text-primary'>Temas</h3>"+
        "<button class='btn btn-sm btn-success' data-toggle='modal' data-target='#modal-new-subject-topic'><i class= 'fas fa-solid fa-plus'></i>&nbsp;Adicionar</button>"+
        "</div>";

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
    
    

    document.querySelector(elementId).innerHTML = html;
}

$('#modal-new-subject-topic').on('show.bs.modal',function(e){
    let feedbackDiv = document.querySelector('#feedback-new-topic');
    //let feedbackMessage = document.querySelector('#feedback-new-topic-message');
    feedbackDiv.classList.add('d-none');
});

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
            feedbackMessage.innerHTML = "Um erro aconteceu";
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

renderDisciplineContents('#discipline-contents');