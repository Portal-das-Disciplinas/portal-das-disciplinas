let productions = [];

function showModalEdit(event, id) {
    productions = [];
    renderInputFields("#form-edit-productions");
    $('#modalCreateCollaboratorProductions').modal('show');
}

function renderInputFields(element) {
    let html = "";
    if (productions.length > 0) {
        productions.forEach((production, index) => {
            html += "<div class='card p-1 mb-3' style='background-color:#E7EAF6'>" +
                "<div class='form-group'>" +
                "<label class='form-label'>Breve descrição</label>" +
                "<input class='form-control' type='text' onchange='changeBriefText(event," + index + ")' value='" + production.brief + "'>" +
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
            "<h4> Não há produções cadastradas ainda </h4>" +
            "<h4>Clique em adicionar para cadastrar uma produção</h4>"+
            "</div>"
    }
    document.querySelector(element).innerHTML = html;
}

function addField() {
    productions.push({ brief: "", details: ""});
    renderInputFields("#form-edit-productions");
    //console.log(productions);
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
    renderInputFields("#form-edit-productions");
    console.log(productions);

}



