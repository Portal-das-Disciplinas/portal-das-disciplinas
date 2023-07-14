
function renderInputParticipants(parentElement){
    let element = document.querySelector(parentElement);
    let html = "";

    videoContentProducers.forEach(function(participant, index){
        html+= "<div class='form-group card p-1' style='background-color:#ECF9FF'>"+
                    "<input class='form-control mb-1' type='text' required value='"+participant.name+
                    "' placeholder='Nome do participante' onchange='onChangeName(event,"+index+")'>"+
                    "<input class='form-control' type='email' value='"+participant.email+
                    "' placeholder='E-mail do participante' onchange='onChangeEmail(event,"+index+")'>"+
                    "<div class='d-flex justify-content-end'>"+
                        "<small class='text-danger' style='cursor:pointer' onclick='deleteParticipantField("+index+")'>remover</small>"+
                    "</div>"+
                "</div>"
    });
    element.innerHTML = html;

}

function addParticipantField(){
    if(videoContentProducers.length < 6){
        videoContentProducers.push({name:"", email:""});
        renderInputParticipants('#formVideoContentProducers');
    }
    else{
        alert("Número de produtores excedido");
    }
    
}

function deleteParticipantField(index){

    videoContentProducers = videoContentProducers.filter(function(participant, idx){
        return index!=idx;
    });
    renderInputParticipants('#formVideoContentProducers');

}

function onChangeName(event, index){
    videoContentProducers[index].name= event.target.value;
}

function onChangeEmail(event, index){
    videoContentProducers[index].email = event.target.value;
    
}

function submitFormContentProducers(){
    document.querySelector('input[name=contentProducers]').value = JSON.stringify(videoContentProducers);
    document.querySelector('#formContentProducersJson').submit();
}

function openModalVideoProducers(){
    videoContentProducers = [];
    databaseVideoContentProducers.forEach(function(producer){
        videoContentProducers.push(producer);
    });
    $('#modal-video-producers').modal('show');
    renderInputParticipants('#formVideoContentProducers');
}