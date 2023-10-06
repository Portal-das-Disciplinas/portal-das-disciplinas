function onSelectYear(event) {
    yearSelectedIndex = event.target.selectedIndex;
}

function onSelectPeriod(event) {
    periodSelectedIndex = event.target.selectedIndex;
}
let classSelectedIndex = -1000;

function onSelectClass(event) {
    classSelectedIndex = event.target.selectedIndex;
}

function onClickSearchClasses(event, disciplineCode) {
    codigoComponente = disciplineCode;
    ano = document.querySelector("#selectYear").value;
    periodo = document.querySelector("#selectPeriod").value;
    $.ajax({
        url:
            "/apisistemas/turmas?codigo-componente=" +
            codigoComponente +
            "&ano=" +
            ano +
            "&periodo=" +
            periodo,
        method: "GET",

        success: function (result) {
            let html = "";
            result.forEach(function (turma) {
                html +=
                    "<option value='" +
                    turma["id-turma"] +
                    "'> Turma " +
                    turma["codigo-turma"] +
                    " - " +
                    turma["descricao-horario"].split(" ")[0] +
                    " </option>";
            });
            document.querySelector("#selectClass").innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.log("um erro aconteceu" + error);
        },
    });
}

function onSearchDisciplineDataClick(code) {
    if (
        !document.querySelector("#checkAllClasses").checked &&
        document.querySelector("#selectClass").value == ""
    ) {
        alert("Clique em Buscar para selecionar uma turma");
    } else {
        searchDisciplineData(code);
        document.querySelector("#btnSearchDisciplineData").disabled = true;
    }
}

let yearSelectedIndex = 0;
let periodSelectedIndex = 0;

function onChangeCheckAllClasses(event) {
    let isChecked = event.target.checked;
    let groupClass = document.querySelector("#form-group-select-class");
    if (isChecked) {
        groupClass.classList.add("d-none");
    } else {
        groupClass.classList.remove("d-none");
    }
}

function searchDisciplineData(code) {
    tempoInicial = new Date().getMilliseconds();
    let disciplineCode = code;
    idTurma = "";
    if (!document.querySelector("#checkAllClasses").checked) {
        idTurma = "/" + document.querySelector("#selectClass").value;
    }

    let year = document.querySelector("#selectYear").value;
    let period = document.querySelector("#selectPeriod").value;
    let element = document.querySelector("#infoPesquisaDados");
    element.classList.remove("d-none");
    element.innerHTML = "Buscando dados...";
    yearSelectedIndex = event.target.selectedIndex;
    $.ajax({
        url: "dados/" + disciplineCode + idTurma + "/" + year + "/" + period,
        method: "GET",

        success: function (result) {
            if (result["soma-medias"]) {
                let mediaGeral = (
                    result["soma-medias"] / result["quantidade-discentes"]
                ).toFixed(2);
                let percentagemAprovados = (
                    (result["quantidade-aprovados"] /
                        result["quantidade-discentes"]) *
                    100
                ).toFixed(1);
                let percentagemReprovados = (
                    (result["quantidade-reprovados"] /
                        result["quantidade-discentes"]) *
                    100
                ).toFixed(1);
                document
                    .querySelector("#infoPesquisaDados")
                    .classList.add("d-none");
                document
                    .querySelector("#dadosDisciplina")
                    .classList.remove("d-none");
                document.querySelector("#notaMediaComponente").innerHTML =
                    mediaGeral;
                document.querySelector("#percentagemAprovados").innerHTML =
                    percentagemAprovados + "%";
                document.querySelector("#progressAprovados").style.width =
                    percentagemAprovados + "%";
                document.querySelector("#percentagemReprovados").innerHTML =
                    percentagemReprovados + "%";
                document.querySelector("#progressReprovados").style.width =
                    percentagemReprovados + "%";
            } else {
                document
                    .querySelector("#dadosDisciplina")
                    .classList.add("d-none");
                let element = document.querySelector("#infoPesquisaDados");
                element.classList.remove("d-none");
                element.innerHTML = "Não foram encontrados dados para esse ano";
            }
            document.querySelector("#btnSearchDisciplineData").disabled = false;
        },

        statusCode: {
            500: function (e) {
                // console.log("erro aconteceu " + JSON.stringify(e));
            },
        },
        error: function (xhr, status, error) {
            document.querySelector("#dadosDisciplina").classList.add("d-none");
            let element = document.querySelector("#infoPesquisaDados");
            element.classList.add("d-none");
            element.innerHTML = "Ocorreu um erro ao obter os dados";
            document.querySelector("#btnSearchDisciplineData").disabled = false;
        },
    });
}
