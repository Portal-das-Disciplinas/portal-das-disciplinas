function onChangeSelect(event) {
    yearSelectedIndex = event.target.selectedIndex;
    let yearStart = document.querySelector('#yearStart').value;
    let periodStart = document.querySelector('#periodStart').value;
    let yearEnd = document.querySelector('#yearEnd').value;
    let periodEnd = document.querySelector('#periodEnd').value;
    let errorMessageElement = document.querySelector('#intervalErrorMessage');
    if((yearStart > yearEnd) || (yearStart == yearEnd && periodStart > periodEnd)){
        errorMessageElement.innerHTML = "* Intervalo inválido";
        document.querySelector("#btnSearchDisciplineData").disabled = true;
    }
    else{
        document.querySelector("#btnSearchDisciplineData").disabled = false;
        errorMessageElement.innerHTML = "";
    }
}

let classSelectedIndex = -1000;

function onSelectClass(event) {
    classSelectedIndex = event.target.selectedIndex;
    updateInfos();
}

function onSearchDisciplineDataClick(code) {
    document.querySelector('#checkAllClasses').checked = true;
    checkedAllClasses = true;
    searchDisciplineData(code);
    document.querySelector("#btnSearchDisciplineData").disabled = true;
}

/*let yearSelectedIndex = 0;
let lastYearSelectedIndex = 0;
let periodSelectedIndex = 0;
let lastPeriodSelectedIndex = 0; */
let checkedAllClasses = true;

function onChangeCheckAllClasses(event) {
    checkedAllClasses = event.target.checked;
    updateInfos();
}

let classPerformanceDatas = [];
let generalPerformanceData = {
    averageGrade: 0,
    highestGrade: -1,
    lowestGrade: 1000,
    numStudents: 0,
    numApprovedStudents: 0,
    numFailedStudents: 0,
};

function resetValues() {
    generalPerformanceData.averageGrade = 0;
    generalPerformanceData.highestGrade = 0;
    generalPerformanceData.lowestGrade = 0;
    generalPerformanceData.numStudents = 0;
    generalPerformanceData.numApprovedStudents = 0;
    generalPerformanceData.numFailedStudents = 0;
    classPerformanceDatas = [];
}

function updateInfos() {
    let groupClass = document.querySelector("#form-group-select-class");
    if (classPerformanceDatas.length == 0) {
        groupClass.classList.add("d-none");
        return;
    }

    if (checkedAllClasses || classPerformanceDatas.length == 0) {
        groupClass.classList.add("d-none");
    } else {
        groupClass.classList.remove("d-none");
    }

    if (document.querySelector("#checkAllClasses").checked) {
        let mediaGeral = generalPerformanceData.averageGrade.toFixed(2);
        let percentagemAprovados = ((generalPerformanceData.numApprovedStudents / generalPerformanceData.numStudents) * 100).toFixed(2);
        let percentagemReprovados = ((generalPerformanceData.numFailedStudents / generalPerformanceData.numStudents) * 100).toFixed(2);
        document.querySelector("#infoPesquisaDados").classList.add("d-none");
        document.querySelector("#dadosDisciplina").classList.remove("d-none");
        document.querySelector("#notaMediaComponente").innerHTML = mediaGeral;
        document.querySelector("#percentagemAprovados").innerHTML =
            percentagemAprovados + "%";
        document.querySelector("#progressAprovados").style.width =
            percentagemAprovados + "%";
        document.querySelector("#percentagemReprovados").innerHTML =
            percentagemReprovados + "%";
        document.querySelector("#progressReprovados").style.width =
            percentagemReprovados + "%";
        document.querySelector("#infoTipoBusca").innerHTML =
            "dados de todas as turmas";
        document.querySelector("#infoProfessoresBusca").innerHTML = "";
        document.querySelector("#infoNumDiscentes").innerHTML =
            generalPerformanceData.numStudents + " discentes";
    } else {
        let index = document.querySelector("#selectClass").selectedIndex;
        let data = classPerformanceDatas[index];
        let mediaGeral = data['average_grade'].toFixed(2);
        let percentagemAprovados = ((data['num_approved_students'] / data['num_students']) * 100).toFixed(2);
        let percentagemReprovados = ((data['num_failed_students'] / data['num_students']) * 100).toFixed(2);
        document.querySelector("#infoPesquisaDados").classList.add("d-none");
        document.querySelector("#dadosDisciplina").classList.remove("d-none");
        document.querySelector("#notaMediaComponente").innerHTML = mediaGeral;
        document.querySelector("#percentagemAprovados").innerHTML =
            percentagemAprovados + "%";
        document.querySelector("#progressAprovados").style.width =
            percentagemAprovados + "%";
        document.querySelector("#percentagemReprovados").innerHTML =
            percentagemReprovados + "%";
        document.querySelector("#progressReprovados").style.width =
            percentagemReprovados + "%";
        document.querySelector("#infoTipoBusca").innerHTML =
            "Apenas a turma " + classPerformanceDatas[index]["class_code"];
        document.querySelector("#infoNumDiscentes").innerHTML =
            classPerformanceDatas[index]["num_students"] + " discentes";
        let professores = JSON.parse(
            classPerformanceDatas[index]["professors"],
        );

        if (professores[0].length == 1) {
            document.querySelector("#infoProfessoresBusca").innerHTML =
                "professor(a): " + JSON.parse(professores[0][0]).nome;
        } else {
            let nomes = "PROFESSORES: ";
            try {
                for (i = 0; i < professores[0].length; i++) {
                    let professor = JSON.parse(professores[0][i]).nome;
                    nomes += professor;
                    if (i < professores[0].length - 1) {
                        nomes += ", "
                    }
                }
            } catch (e) {
                nomes = "PROFESSORES: ";
            }
            document.querySelector("#infoProfessoresBusca").innerHTML = nomes;
        }
    }
}

function searchDisciplineData(disciplineCode) {
    idTurma = "";
    if (!document.querySelector("#checkAllClasses").checked) {
        idTurma = "/" + document.querySelector("#selectClass").value;
    }

    //let year = document.querySelector("#selectYear").value;
    //let period = document.querySelector("#selectPeriod").value;
    let yearS = document.querySelector("#yearStart").value;
    let periodS = document.querySelector("#periodStart").value;
    let yearE= document.querySelector("#yearEnd").value;
    let periodE = document.querySelector("#periodEnd").value;
    let element = document.querySelector("#infoPesquisaDados");
    element.classList.remove("d-none");
    element.innerHTML = "Buscando dados...";
    document.querySelector("#dadosDisciplina").classList.add("d-none");
    $.ajax({
        url: '/api/performance/data/interval',
        method: 'GET',
        data:{disciplineCode : disciplineCode, yearStart : yearS, periodStart: periodS, yearEnd : yearE, periodEnd : periodE},
        dataType: 'json',

        success: function (result) {
            resetValues();
            classPerformanceDatas = result;
            if (!Array.isArray(result)) {
                document.querySelector("#dadosDisciplina").classList.add("d-none");
                let element = document.querySelector("#infoPesquisaDados");
                element.classList.remove("d-none");
                element.innerHTML = "Ocorreu um erro ao obter os dados";
                document.querySelector(
                    "#btnSearchDisciplineData",
                ).disabled = false;
                return;


            }
            let html = "";
            classPerformanceDatas.forEach(function (data, index) {
                let nomeDocentes = "";
                try {
                    let docentes = JSON.parse(data['professors']);
                    for (i = 0; i < docentes.length; i++) {
                        nomeDocentes += docentes[i];
                        if (i != docentes.length - 1) {
                            nomeDocentes += ", ";
                        }
                    }
                }catch(e){
                    console.log("Erro ao fazer o parse dos professores");
                    nomeDocentes = "Professor ";
                }

                html += "<option value='" + index + "'>"+data['year']+"." + data['period']+" - turma " + data['class_code'] + " - " + nomeDocentes + " </option>"
                generalPerformanceData.averageGrade += data['sum_grades'];
                generalPerformanceData.numStudents += data['num_students'];
                generalPerformanceData.numApprovedStudents += data['num_approved_students'];
                generalPerformanceData.numFailedStudents += data['num_failed_students'];
                generalPerformanceData.highestGrade = Math.max(generalPerformanceData.highestGrade, data['highest_grade']);
                generalPerformanceData.lowestGrade = Math.min(generalPerformanceData.lowestGrade, data['lowest_grade']);
            });
            document.querySelector("#selectClass").innerHTML = html;

            generalPerformanceData.averageGrade =
                generalPerformanceData.averageGrade /
                generalPerformanceData.numStudents;

            if (result.length > 0) {
                updateInfos();
                document
                    .querySelector("#dadosDisciplina")
                    .classList.remove("d-none");
            } else {
                resetValues();
                document
                    .querySelector("#dadosDisciplina")
                    .classList.add("d-none");
                let element = document.querySelector("#infoPesquisaDados");
                element.classList.remove("d-none");
                element.innerHTML = "Não foram encontrados dados para esse ano";
                updateInfos();
            }
            document.querySelector("#btnSearchDisciplineData").disabled = false;

        },

        statusCode: {
            500: function (e) {
                console.log("Erro no servidor");
            }
        },
        error: function (xhr, status, error) {
            console.log("Ocorreu um erro");
            document.querySelector("#dadosDisciplina").classList.add("d-none");
            let element = document.querySelector("#infoPesquisaDados");
            element.classList.add("d-none");
            element.innerHTML = "Ocorreu um erro ao obter os dados";
            document.querySelector("#btnSearchDisciplineData").disabled = false;
        },
    });
}
