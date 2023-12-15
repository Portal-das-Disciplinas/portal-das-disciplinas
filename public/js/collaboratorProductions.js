let productions = [];

/**
 * Mostra o modal de produção do colaborador com as informações resumidas e detalhes.
 * @param {string} brief - Descrição breve da produção.
 * @param {string} details - Detalhes da produção.
 */
function showModalCollaboratorProduction(brief, details) {
    document.querySelector("#productionBrief").innerHTML = brief;
    document.querySelector("#productionDetails").innerHTML = details;
    $("#modalCollaboratorProduction").modal("show");
}

let idCollaboratorToSave = null;
/**
 * Mostra o modal para criar produções de colaboradores.
 * @param {number} idCollaborator - O ID do colaborador para o qual criar as produções.
 */
function showModalCreateCollaboratorProductions(idCollaborator) {
    idCollaboratorToSave = idCollaborator;
    productions = [];
    renderInputFields("fields");
    $("#modalCreateCollaboratorProductions").modal("show");
}

/**
 * Renderiza os campos de entrada dinâmicos para as produções.
 * @param {string} idElement - O ID do elemento HTML onde renderizar os campos.
 */
function renderInputFields(idElement) {
    let html = "";
    if (productions.length > 0) {
        productions.forEach((production, index) => {
            html +=
                "<div class='card p-1 mb-3' style='background-color:#E7EAF6'>" +
                "<div class='form-group'>" +
                "<label class='form-label'>Breve descrição</label>" +
                "<input class='form-control' type='text' minlength='5' maxlength='84' required onchange='changeBriefText(event," +
                index +
                ")' value='" +
                production.brief +
                "'>" +
                "</div>" +
                "<div class='form-group'>" +
                "<label>Detalhes</label>" +
                "<textarea class='form-control' maxlength='256' onchange='changeDetailsText(event," +
                index +
                ")' placeholder='Opcional'>" +
                production.details +
                "</textarea>" +
                "</div>" +
                "<div>" +
                "<label class='text-danger' style='cursor:pointer' onclick='removeField(event," +
                index +
                ")'><small>remover</small></label>" +
                "</div>" +
                "</div>";
        });
    } else {
        html =
            "<div class=' d-flex flex-column justify-content-center align-items-center p-5'>" +
            "<h4>Clique em <strong class='text-primary'>adicionar campo</strong> para cadastrar uma produção</h4>" +
            "</div>";
    }
    if (productions.length == 0) {
        document.querySelector("#btnSubmitProductions").classList.add("d-none");
    } else {
        document
            .querySelector("#btnSubmitProductions")
            .classList.remove("d-none");
    }
    document.querySelector("#" + "fields").innerHTML = html;
}

/**
 * Adiciona um novo campo de produção.
 * @param {string} idElement - O ID do elemento HTML onde adicionar o campo.
 */
function addField(idElement) {
    productions.push({ brief: "", details: "" });
    renderInputFields("#fields");
}

/**
 * Atualiza o texto breve da produção.
 * @param {Event} event - O evento de mudança.
 * @param {number} index - O índice da produção.
 */
function changeBriefText(event, index) {
    productions[index].brief = event.target.value;
}

/**
 * Atualiza os detalhes da produção.
 * @param {Event} event - O evento de mudança.
 * @param {number} index - O índice da produção.
 */
function changeDetailsText(event, index) {
    productions[index].details = event.target.value;
}

/**
 * Remove um campo de produção.
 * @param {Event} event - O evento de clique.
 * @param {number} index - O índice da produção.
 */
function removeField(event, index) {
    productions = productions.filter((element, idx) => {
        return idx != index;
    });
    renderInputFields("#fields");
}

/**
 * Configura os valores do formulário antes de salvar as produções.
 */
function btnSaveProductions() {
    let formElement = document.querySelector(
        "#formCollaboratorProductionsCreate",
    );
    document.querySelector("#productionCollaboratorId").value =
        idCollaboratorToSave;
    document.querySelector("#collaboratorProductionsJSON").value =
        JSON.stringify(productions);
}

/**
 * Limpa as variáveis ​​ao fechar o modal.
 */
function btnCloseModal() {
    idCollaboratorToSave = null;
    productions = [];
}
