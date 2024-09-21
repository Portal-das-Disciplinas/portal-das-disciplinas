let idLinks = 0;
let participants = [];

/**
 * Define os participantes no array.
 *
 * @param {Array} participantsArray - Array de participantes.
 */
function setParticipants(participantsArray) {
    participants = participantsArray;
    participants.forEach(function (p, index) {
        p.index = index;
        p.links.forEach(function (link, i) {
            link.index = i;
        });
    });
}

/**
 * Adiciona um novo participante ao array.
 *
 * @param {Event} event - Objeto de evento.
 */
function addParticipantField(event) {
    event.preventDefault();
    let emptyParticipant = {
        name: "",
        role: "",
        email: "",
        index: participants.length,
        links: [],
    };
    participants.push(emptyParticipant);
    renderParticipants("#participants");
}
/**
 * Remove um participante do array.
 *
 * @param {Event} event - Objeto de evento.
 */
function removeParticipantField(event) {
    event.preventDefault();
    index = event.target.id;
    participants = participants.filter(function (participant) {
        return participant.index != index;
    });

    participants.forEach(function (participant, index) {
        participant.index = index;
    });
    sendParticipantsToFormInput();
    renderParticipants("#participants");
}
/**
 * Adiciona um novo link ao participante.
 *
 * @param {Event} event - Objeto de evento.
 */
function addLinkField(event) {
    event.preventDefault();

    let link = {
        index: participants[event.target.id].links.length,
        name: "",
        url: "",
    };
    if (participants[event.target.id].links.length >= 3) {
        $("#modalLinksLimit").modal("show");
        return;
    }
    participants[event.target.id].links.push(link);
    renderParticipants("#participants");
}

/**
 * Remove um link do participante.
 *
 * @param {Event} event - Objeto de evento.
 * @param {number} indexParticipant - Índice do participante.
 * @param {number} linkIndex - Índice do link.
 */
function removeLinkField(event, indexParticipant, linkIndex) {
    event.preventDefault();
    participants[indexParticipant].links = participants[
        indexParticipant
    ].links.filter(function (link, index) {
        return link.index != linkIndex;
    });
    participants[indexParticipant].links.forEach(function (link, index) {
        link.index = index;
    });
    sendParticipantsToFormInput();
    renderParticipants("#participants");
}
/**
 * Envia os participantes para o campo de formulário.
 */
function sendParticipantsToFormInput() {
    document.querySelector("#participantsList").value =
        JSON.stringify(participants);
}

/**
 * Manipula a alteração do nome do participante.
 *
 * @param {Event} event - Objeto de evento.
 */
function onChangeParticipantName(event) {
    participants[event.target.id].name = event.target.value;
    sendParticipantsToFormInput();
}

/**
 * Manipula a alteração da função do participante.
 *
 * @param {Event} event - Objeto de evento.
 */
function onChangeParticipantRole(event) {
    participants[event.target.id].role = event.target.value;
    sendParticipantsToFormInput();
}
/**
 * Manipula a alteração do e-mail do participante.
 *
 * @param {Event} event - Objeto de evento.
 */
function onChangeParticipantEmail(event) {
    participants[event.target.id].email = event.target.value;
    sendParticipantsToFormInput();
}

/**
 * Manipula a alteração do nome do link.
 *
 * @param {Event} event - Objeto de evento.
 * @param {number} participantIndex - Índice do participante.
 * @param {number} linkIndex - Índice do link.
 */
function onChangeLinkName(event, participantIndex, linkIndex) {
    participants[participantIndex].links[linkIndex].name = event.target.value;
    sendParticipantsToFormInput();
}

/**
 * Manipula a alteração da URL do link.
 *
 * @param {Event} event - Objeto de evento.
 * @param {number} participantIndex - Índice do participante.
 * @param {number} linkIndex - Índice do link.
 */
function onChangeLinkUrl(event, participantIndex, linkIndex) {
    participants[participantIndex].links[linkIndex].url = event.target.value;
    sendParticipantsToFormInput();
}

/**
 * Renderiza os participantes na interface.
 *
 * @param {string} idElement - ID do elemento HTML.
 */
function renderParticipants(idElement) {
    let html = "";
    participants.forEach(function (participant, index) {
        html +=
            " <div class='d-flex mb-5 flex-column card' style='background-color: #f2f2f2'>" +
            "<div class='p-1 w-100'>" +
            "<div class='form-group'>" +
            "<label for='part1'>Nome</label>" +
            "<input id='" +
            participant.index +
            "' class='form-control' type='text' name='participantName[]' placeholder='Nome do Participante' required value='" +
            participant.name +
            "' onchange='onChangeParticipantName(event)'>" +
            "</div>" +
            "<div class='form-group'>" +
            "<label>Função</label>" +
            "<input id='" +
            participant.index +
            "' class='form-control' type='text' name='participantRole[]' placeholder='Função do Participante' required value='" +
            participant.role +
            "' onchange='onChangeParticipantRole(event)'>" +
            "</div>" +
            "<div class='form-group'>" +
            "<label>E-mail</label>" +
            "<input id='" +
            participant.index +
            "' class='form-control' type='email' name='participantEmail[]' placeholder='E-mail do Participante' value='" +
            participant.email +
            "' onchange='onChangeParticipantEmail(event)'>" +
            "</div>" +
            "<hr class='hr'>" +
            "<span>LINKS</span>" +
            "<div id='links' class='d-flex flex-column p-1'>";
        participant.links.forEach(function (link, index) {
            html +=
                "<div class='card p-1 mb-1' style='background-color:#e7eaf6'>" +
                "<div class='form-group w-100'>" +
                "<input class='form-control' type='text' name='linkName[]' maxlength='20' placeholder='Nome da rede social' required value='" +
                link.name +
                "' onchange='onChangeLinkName(event," +
                participant.index +
                "," +
                index +
                ")'>" +
                "</div>" +
                "<div class='form-group'>" +
                "<input class='form-control mb-0' type='url' name='linkUrl[] p-0' placeholder='http://' required value='" +
                link.url +
                "' onchange='onChangeLinkUrl(event," +
                participant.index +
                "," +
                index +
                ")'>" +
                "</div>" +
                "<div class='d-flex mb-2'><small id='" +
                link.index +
                "' class='text-danger' style='cursor:pointer;line-height:0.5' onclick='removeLinkField(event," +
                participant.index +
                "," +
                index +
                ")'>remover link</small></div>" +
                "</div>";
        });
        html +=
            "</div>" +
            "<a id='" +
            participant.index +
            "' class='btn btn-outline-primary btn-sm mt-2' href='#' onclick='addLinkField(event)'>Adicionar link</a>" +
            "</div>" +
            "<div class='d-flex justify-content-end mb-2 mr-1'><button id='" +
            participant.index +
            "' class='btn btn-danger btn-sm' onclick='removeParticipantField(event)'>remover participante</button></div>" +
            "</div>";
    });
    document.querySelector(idElement).innerHTML = html;
}

document.querySelector('#emphasis').addEventListener('change',(event)=>{
    document.querySelector('#select-emphasis-index').value = event.target.selectedIndex;
});

document.querySelector('#professor').addEventListener('change',(event)=>{
    document.querySelector('#select-professor-index').value = event.target.selectedIndex;
});

function onChangeEmphasis(event){
    document.querySelector("#old_input_emphasis").value = event.target.value;
}

function onChangeProfessor(event){
    document.querySelector("#old_input_professor").value = event.target.value;
}
