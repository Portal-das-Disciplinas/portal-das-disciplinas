/**
 * Renderiza os campos de entrada para os participantes do conteúdo de vídeo.
 * @param {string} parentElement - O seletor do elemento pai onde os campos serão renderizados.
 */
function renderInputParticipants(parentElement) {
    let element = document.querySelector(parentElement);
    let html = "";

    videoContentProducers.forEach(function (participant, index) {
        html +=
            "<div class='form-group card p-1' style='background-color:#ECF9FF'>" +
            "<small class='d-none text-danger' name='errorMessageName'>O nome é inválido</small>" +
            "<input class='form-control mb-1' type='text' required value='" +
            participant.name +
            "' placeholder='Nome do produtor' onchange='onChangeName(event," +
            index +
            ")' required>" +
            "<small class='d-none text-danger' name='errorMessageEmail'>O e-mail é inválido</small>" +
            "<input class='form-control' type='email' value='" +
            participant.email +
            "' placeholder='E-mail do produtor' onchange='onChangeEmail(event," +
            index +
            ")'>" +
            "<div class='d-flex justify-content-end'>" +
            "<small class='text-danger' style='cursor:pointer' onclick='deleteParticipantField(" +
            index +
            ")'>remover</small>" +
            "</div>" +
            "</div>";
    });
    element.innerHTML = html;
}

/**
 * Adiciona um novo campo de participante se o limite não for atingido.
 */
function addParticipantField() {
    if (videoContentProducers.length < 10) {
        videoContentProducers.push({ name: "", email: "" });
        renderInputParticipants("#formVideoContentProducers");
    } else {
        alert("Número de produtores excedido");
    }
}

/**
 * Remove um campo de participante com o índice fornecido.
 * @param {number} index - O índice do participante a ser removido.
 */
function deleteParticipantField(index) {
    videoContentProducers = videoContentProducers.filter(
        function (participant, idx) {
            return index != idx;
        },
    );
    renderInputParticipants("#formVideoContentProducers");
}

/**
 * Atualiza o nome do participante no array ao alterar o campo de nome.
 * @param {Event} event - O evento de mudança.
 * @param {number} index - O índice do participante no array.
 */
function onChangeName(event, index) {
    videoContentProducers[index].name = event.target.value;
    event.target.classList.remove("bg-warning");
    document
        .querySelectorAll(
            "#formVideoContentProducers small[name=errorMessageName]",
        )
        [index].classList.add("d-none");
}

/**
 * Atualiza o e-mail do participante no array ao alterar o campo de e-mail.
 * @param {Event} event - O evento de mudança.
 * @param {number} index - O índice do participante no array.
 */
function onChangeEmail(event, index) {
    videoContentProducers[index].email = event.target.value;
    event.target.classList.remove("bg-warning");
    document
        .querySelectorAll(
            "#formVideoContentProducers small[name=errorMessageEmail]",
        )
        [index].classList.add("d-none");
}

/**
 * Submete o formulário se todos os campos estiverem válidos.
 */
function submitFormContentProducers() {
    let names = document.querySelectorAll(
        "#formVideoContentProducers input[type=text]",
    );
    let namesOk = true;
    names.forEach(function (name, index) {
        if (!name.checkValidity()) {
            name.classList.add("bg-warning");
            document
                .querySelectorAll(
                    "#formVideoContentProducers small[name=errorMessageName]",
                )
                [index].classList.remove("d-none");
            namesOk = false;
        }
    });

    let emails = document.querySelectorAll(
        "#formVideoContentProducers input[type=email]",
    );
    let emailsOk = true;
    emails.forEach(function (email, index) {
        if (!email.checkValidity()) {
            email.classList.add("bg-warning");
            document
                .querySelectorAll(
                    "#formVideoContentProducers small[name=errorMessageEmail]",
                )
                [index].classList.remove("d-none");
            emailsOk = false;
        }
    });

    if (namesOk && emailsOk) {
        document.querySelector("input[name=contentProducers]").value =
            JSON.stringify(videoContentProducers);
        document.querySelector("#formContentProducersJson").submit();
    }
}

/**
 * Abre o modal de produtores de vídeo.
 */
function openModalVideoProducers() {
    videoContentProducers = [];
    databaseVideoContentProducers.forEach(function (producer) {
        videoContentProducers.push(producer);
    });
    $("#modal-video-producers").modal("show");
    renderInputParticipants("#formVideoContentProducers");
}

/**
 * Atualiza o portal de vídeo.
 */
function updateVideoPortal() {
    let form = document.querySelector("#modalAlterarVideo form");
    if (form.checkValidity() == true) {
        form.submit();
    } else {
        document
            .querySelector("#modalAlterarVideo small")
            .classList.remove("d-none");
    }
}

/**
 * Remove o vídeo.
 */
function removeVideo() {
    document.querySelector("#deleteVideoForm").submit();
}

/**
 * Executado quando há uma alteração no campo de link.
 */
function onChangeInputLink() {
    document.querySelector("#modalAlterarVideo small").classList.add("d-none");
}

/**
 * Abre o modal de edição do nome da seção.
 * @param {string} idModal - O ID do modal.
 * @param {string} sectionName - O nome da seção.
 */
function openModalEditSectionName(idModal, sectionName) {
    $("#" + idModal + " #section-name").val(sectionName);
    $("#" + idModal).modal("show");
}
