let productions = [];

function showModalCollaboratorProduction(brief, details) {
   
    document.querySelector("#productionBrief").innerHTML = brief;
    document.querySelector("#productionDetails").innerHTML = details;
    $('#modalCollaboratorProduction').modal('show');
}

let idCollaboratorToSave = null;
function showModalCreateCollaboratorProductions(idCollaborator){
    idCollaboratorToSave = idCollaborator;
    productions = [];
    renderInputFields("fields");
    $('#modalCreateCollaboratorProductions').modal('show');

}

function renderInputFields(idElement) {
    let html = "";
    if (productions.length > 0) {
        productions.forEach((production, index) => {
            html += "<div class='card p-1 mb-3' style='background-color:#E7EAF6'>" +
                "<div class='form-group'>" +
                "<label class='form-label'>Breve descrição</label>" +
                "<input class='form-control' type='text' minlength='5' maxlength='84' required onchange='changeBriefText(event," + index + ")' value='" + production.brief + "'>" +
                "</div>" +
                "<div class='form-group'>" +
                "<label>Detalhes</label>" +
                "<textarea class='form-control' maxlength='256' onchange='changeDetailsText(event," + index + ")'>" + production.details + "</textarea>" +
                "</div>" +
                "<div>" +
                "<label class='text-danger' style='cursor:pointer' onclick='removeField(event," + index + ")'><small>remover</small></label>" +
                "</div>" +
                "</div>";
        });
    }
    else {
        html = "<div class=' d-flex flex-column justify-content-center align-items-center p-5'>" +
            "<h4>Clique em <strong class='text-primary'>adicionar campo</strong> para cadastrar uma produção</h4>"+
            "</div>"
    }
    if(productions.length == 0){
        document.querySelector('#btnSubmitProductions').classList.add('d-none');
    }else{
        document.querySelector('#btnSubmitProductions').classList.remove('d-none');
    }
    document.querySelector("#"+'fields').innerHTML = html;
}

function addField(idElement) {
    productions.push({ brief: "", details: ""});
    renderInputFields('#fields');
}

function changeBriefText(event, index) {
    productions[index].brief = event.target.value;
}

function changeDetailsText(event, index) {
    productions[index].details = event.target.value;
}

function removeField(event, index) {
    productions = productions.filter((element, idx)=>{
        return idx != index;
    })
    renderInputFields("#fields");
}

function btnSaveProductions(){

    let formElement = document.querySelector('#formCollaboratorProductionsCreate');
    document.querySelector('#productionCollaboratorId').value = idCollaboratorToSave;
    document.querySelector('#collaboratorProductionsJSON').value = JSON.stringify(productions);

}

function btnCloseModal(){
    idCollaboratorToSave = null;
    productions = [];

}



