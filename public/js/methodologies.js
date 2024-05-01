let professorMethodologies  = [];
function renderProfessorMethodologies(){
    let html = "";
    professorMethodologies.forEach(function(element, index){
        html+= 
        "<strong class='badge badge-pfill badge-primary mr-2' style='cursor:help;' data-toggle='modal' data-target='#methodology-"+element.id+"' onclick='onClickMethodology("+element.id+")'>"+
        element.methodology_name +
        "</strong>";
        html += 
                    "<div class='modal fade' tabindex='-1' role='dialog' id='methodology-"+element.id+"'>"+
                    "<div class='modal-dialog' role='document'>"+
                        "<div class='modal-content'>"+
                            "<div class='modal-header'>"+
                                "<h3 class='modal-title text-primary'>"+element.methodology_name+"</h3>"+
                                "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>"+
                                    "<span aria-hidden='true'>&times;</span>"+
                                "</button>"+
                            "</div>"+
                            "<div class='modal-body'>"+
                                "<div class='d-flex flex-column'>"+
                                    "<small class='text-secondary'>descrição da metodologia</small>"+
                                    "<textarea id='methodology-description-"+index + "' class='text-primary' rows='4'>"+element.methodology_description+"</textarea>"+
                                    "<span id='feedback-methodology-"+element.methodology_id + "' class='d-none mt-2' style='text-align:center'>Atualizado com sucesso</span>"+
                                    "<div class='d-flex justify-content-end my-2'>"+
                                        "<button class='btn btn-primary btn-sm' onclick=updateMethodologyDescription(event,"+ index+")>Atualizar</button>"+
                                    "</div>"+
                                "</div>"+
                                "<hr>"+
                                "<div class='d-flex flex-column'>"+
                                    "<small class='text-secondary'>Como o professor aplica a metodologia</small>"+
                                    "<textarea id='professor-methodology-description-" + index + "' class='text-primary' rows='10'>"+element.professor_description+"</textarea>"+
                                    "<span id='feedback-professor-methodology-"+element.methodology_id + "' class='d-none alert alert-danger mt-2' style='text-align:center'>Erro ao atualizar</span>"+
                                    "<div class='d-flex justify-content-end my-2'>"+
                                        "<button class='btn btn-primary btn-sm' onclick=updateProfessorMethodologyDescription(event,"+ index+")>Atualizar</button>"+
                                    "</div>"+
                                "</div>"+
                            "</div>"+
                            "<div class='modal-footer'>"+
                                "<button type='button' class='btn btn-sm btn-primary' data-dismiss='modal'>Fechar</button>"+
                            "</div>"+
                        "</div>"+
                    "</div>"+
                "</div>";
    });
    
    document.querySelector('#metodologias').innerHTML = html;
    
}

function getMethodologies(){

}

function getProfessorMethodologies(){
    $.ajax({
        url:'/metodologias/professor/' + professorId + '/' + disciplineCode,
        method:'GET',
        success : function(data){
            professorMethodologies = data;
            renderProfessorMethodologies();
        },
        error:function(data){
            console.log('error');
        }
    });
}

function saveProfessorMethodology(){
    $.ajax({
        url:'/metodologias/professor/' + professorId + '/' + disciplineCode,
        method:'GET',
        success : function(data){
            console.log(data);
        },
        error:function(data){
            console.log('error');
        }
    });
}

function updateMethodologyDescription(event, professorMethodologiesIndex){
    let newMethodologyDescription = document.querySelector('#methodology-description-' + professorMethodologiesIndex).value;
    $.ajax({
        url:'/metodologias/update/'+ professorMethodologies[professorMethodologiesIndex].methodology_id,
        method: 'PUT',
        dataType:'json',
        data:{'_token':token,
            'name': professorMethodologies[professorMethodologiesIndex].methodology_name,
            'description': newMethodologyDescription},
        success:function(data){
            let feedback = document
                .querySelector('#feedback-methodology-' + professorMethodologies[professorMethodologiesIndex].methodology_id );
            feedback.innerHTML = "Atualizado com sucesso!"
            feedback.classList.remove('d-none');
            feedback.classList.add('alert','alert-success');
            feedback.classList.remove('alert-danger');
            professorMethodologies[professorMethodologiesIndex].methodology_description = newMethodologyDescription;

        },
        error:function(e){
            let feedback = document
                .querySelector('#feedback-methodology-' + professorMethodologies[professorMethodologiesIndex].methodology_id );
            feedback.innerHTML = "Erro ao atualizar.";
            feedback.classList.remove('d-none');
            feedback.classList.add('alert','alert-danger');
            feedback.classList.remove('alert-success');
        }

    });
}

function updateProfessorMethodologyDescription(event, professorMethodologiesIndex){
    let newMethodologyDescription = document.querySelector('#professor-methodology-description-' + professorMethodologiesIndex).value;
    $.ajax({
        url:'/metodologias/professor/update/'+ professorMethodologies[professorMethodologiesIndex].id,
        method: 'PUT',
        dataType:'json',
        data:{'_token':token,
            'description': newMethodologyDescription},
        success:function(data){
            let feedback = document
                .querySelector('#feedback-professor-methodology-' + professorMethodologies[professorMethodologiesIndex].methodology_id );
            feedback.innerHTML = "Atualizado com sucesso!"
            feedback.classList.remove('d-none');
            feedback.classList.add('alert','alert-success');
            feedback.classList.remove('alert-danger');
            professorMethodologies[professorMethodologiesIndex].professor_description = newMethodologyDescription;

        },
        error:function(e){
            let feedback = document
                .querySelector('#feedback-professor-methodology-' + professorMethodologies[professorMethodologiesIndex].methodology_id );
            feedback.innerHTML = "Erro ao atualizar.";
            feedback.classList.remove('d-none');
            feedback.classList.add('alert','alert-danger');
            feedback.classList.remove('alert-success');
        }

    });
}

function onClickMethodology(id){
    let feedBackMethodology = document.querySelector('#feedback-methodology-' + id);
    let feedBackProfessorMethodology = document.querySelector('#feedback-professor-methodology-' + id);

    feedBackMethodology.classList.add("d-none");
    feedBackProfessorMethodology.classList.add("d-none");
}

