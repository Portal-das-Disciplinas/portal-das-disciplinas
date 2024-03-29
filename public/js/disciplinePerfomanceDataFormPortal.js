let lastYearStartValue = 0;
let lastPeriodStartValue = 0;
let lastYearEndValue = 0;
let lastPeriodEndValue = 0;
let lastAllPeriodsValue = 0;

let qtdTurmasProfessor = 0;

/**
 * Armazena os valores da última pesquisa.
 */
function setLastSearchValues() {
    if (!document.querySelector('#checkAllPeriods').checked) {
        lastYearStartValue = document.querySelector('#yearStart').value;
        lastPeriodStartValue = document.querySelector('#periodStart').value;
        lastYearEndValue = document.querySelector('#yearEnd').value;
        lastPeriodEndValue = document.querySelector('#periodEnd').value;
    } else {
        lastYearStartValue = 0;
        lastPeriodStartValue = 0;
        lastYearEndValue = 0;
        lastPeriodEndValue = 0;
    }
    lastAllPeriodsValue = document.querySelector('#checkAllPeriods').checked;
}

/**
 * Limpa os valores do último semestre.
 */
function clearLastSemesterValues() {
    lastYearStartValue = 0;
    lastPeriodStartValue = 0;
    lastYearEndValue = 0;
    lastPeriodEndValue = 0;
}

/**
 * Verifica se houve alterações nos valores de pesquisa.
 * @returns {boolean} - True se houver alterações, caso contrário, false.
 */
function checkSearchValuesChanged() {
    let actualYearStartValue = document.querySelector('#yearStart').value;
    let actualPeriodStartValue = document.querySelector('#periodStart').value;
    let actualYearEndValue = document.querySelector('#yearEnd').value;
    let actualPeriodEndValue = document.querySelector('#periodEnd').value;
    let actualAllPeriodsValue = document.querySelector('#checkAllPeriods').checked;

    if (actualAllPeriodsValue && (actualAllPeriodsValue != lastAllPeriodsValue)) {
        return true;
    }
    else if (actualAllPeriodsValue && (actualAllPeriodsValue == lastAllPeriodsValue)) {
        return false
    } else if (!actualAllPeriodsValue) {
        return ((lastYearStartValue != actualYearStartValue) || (lastPeriodStartValue != actualPeriodStartValue) ||
            (lastYearEndValue != actualYearEndValue) || (lastPeriodEndValue != actualPeriodEndValue));
    }

}

/**
 * Atualiza as informações ao selecionar um valor no dropdown.
 * @param {Event} event - O evento de seleção.
 */
function onChangeSelect(event) {
    yearSelectedIndex = event.target.selectedIndex;
    let yearStart = document.querySelector('#yearStart').value;
    let periodStart = document.querySelector('#periodStart').value;
    let yearEnd = document.querySelector('#yearEnd').value;
    let periodEnd = document.querySelector('#periodEnd').value;
    let errorMessageElement = document.querySelector('#intervalErrorMessage');
    if ((yearStart > yearEnd) || (yearStart == yearEnd && periodStart > periodEnd)) {
        errorMessageElement.innerHTML = "* Intervalo inválido";
        document.querySelector("#btnSearchDisciplineData").disabled = true;
    }
    else {
        if (checkSearchValuesChanged()) {
            document.querySelector("#btnSearchDisciplineData").disabled = false;
            document.querySelector('#infoBtnSearchDisciplineData').classList.add('d-none');
        }
        else {
            document.querySelector("#btnSearchDisciplineData").disabled = true;
            document.querySelector('#infoBtnSearchDisciplineData').classList.remove('d-none');
        }
        errorMessageElement.innerHTML = "";
    }
}

let classSelectedIndex = 0;

/**
 * Atualiza as informações ao selecionar uma classe.
 * @param {Event} event - O evento de seleção da classe.
 */
function onSelectClass(event) {
    classSelectedIndex = event.target.selectedIndex;
    updateInfos();

}

/**
 * Inicia a pesquisa dos dados da disciplina.
 * @param {string} code - O código da disciplina.
 */
function onSearchDisciplineDataClick(code) {
    searchDisciplineData(code);
}

/**
 * Altera as opções de busca ao marcar/desmarcar checkboxes.
 * @param {Event} event - O evento de alteração de checkbox.
 */
function onChangeAllClasses(event) {
    if (event.target.id == 'checkOnlyProfessorClasses') {
        let checkboxAllClasses = document.querySelector('#checkAllClasses');
        if (event.target.checked) {
            checkboxAllClasses.checked = false;
        } else {
            checkboxAllClasses.checked = true;
        }
    } else if (event.target.id == 'checkAllClasses') {
        let checkboxOnlyProfessorClasses = document.querySelector('#checkOnlyProfessorClasses');
        if (event.target.checked) {
            checkboxOnlyProfessorClasses.checked = false;
        } else {
            checkboxOnlyProfessorClasses.checked = true;
        }
    }
    document.querySelector('#selectClass').selectedIndex = 0;
    classSelectedIndex = 0;
    updateInfos();
}

/**
 * Altera a visibilidade do grupo de seleção de períodos.
 * @param {Event} event - O evento de alteração de checkbox.
 */
function onChangeCheckAllPeriods(event) {
    let selectFieldsArea = document.querySelector('#semesterSelectFields');
    let selects = document.querySelectorAll('#semesterSelectFields select');
    if (event.target.checked) {
        selectFieldsArea.style.opacity = 0.5;
        selects.forEach(function (select) {
            select.disabled = true;
        });

    } else {
        selects.forEach(function (select) {
            selectFieldsArea.style.opacity = 1;
            select.disabled = false;
        });
    }
    if (checkSearchValuesChanged()) {
        document.querySelector("#btnSearchDisciplineData").disabled = false;
        document.querySelector('#infoBtnSearchDisciplineData').classList.add('d-none');
    } else {
        document.querySelector("#btnSearchDisciplineData").disabled = true;
        document.querySelector('#infoBtnSearchDisciplineData').classList.remove('d-none');
    }
}

let classPerformanceDatas = [];

let generalPerformanceData = {
    averageGrade: 0,
    averageUnit1Grade: 0,
    averageUnit2Grade: 0,
    averageUnit3Grade: 0,
    numStudents: 0,
    numApprovedStudents: 0,
    numFailedStudents: 0,
};

/**
 * Reseta os valores das estatísticas gerais.
 */
function resetValues() {
    generalPerformanceData.averageGrade = 0;
    generalPerformanceData.averageUnit1Grade = 0;
    generalPerformanceData.averageUnit2Grade = 0;
    generalPerformanceData.averageUnit3Grade = 0;
    //generalPerformanceData.highestGrade = 0;
    //generalPerformanceData.lowestGrade = 0;
    generalPerformanceData.numStudents = 0;
    generalPerformanceData.numApprovedStudents = 0;
    generalPerformanceData.numFailedStudents = 0;
    generalPerformanceData.unit1WithGrade = true;
    generalPerformanceData.unit2WithGrade = true;
    generalPerformanceData.unit3WithGrade = true;
}

/**
 * Atualiza as informações exibidas.
 */
function updateInfos() {
    resetValues();
    let checkedAllClasses = document.querySelector('#checkAllClasses').checked;
    let checkedOnlyProfessorClasses = document.querySelector('#checkOnlyProfessorClasses').checked;
    let groupClass = document.querySelector("#form-group-select-class");

    if (classPerformanceDatas == 0) {
        return;
    }

    let html = "<option value='-1'>TODAS AS TURMAS</option>";
    qtdTurmasProfessor = 0;
    classPerformanceDatas.forEach(function (data, index) {
        if (checkedOnlyProfessorClasses) {

            if (data['professors'].toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "").match(professorName) != professorName) {
                return;
            }
            qtdTurmasProfessor++;
        }
        let nomeDocentes = "";
        try {
            let docentes = JSON.parse(data['professors']);
            for (i = 0; i < docentes.length; i++) {
                nomeDocentes += docentes[i];
                if (i != docentes.length - 1) {
                    nomeDocentes += ", ";
                }
            }
        } catch (e) {
            console.log("Erro ao fazer o parse dos professores");
            nomeDocentes = "Professor ";
        }
        html += "<option value='" + index + "'>" + data['year'] + "." + data['period'] + " - turma " + data['class_code'] + " - " + nomeDocentes + " </option>"
        generalPerformanceData.averageGrade += data['sum_grades'];
        generalPerformanceData.averageUnit1Grade += data['sum_unit1_grades'];
        generalPerformanceData.averageUnit2Grade += data['sum_unit2_grades'];
        generalPerformanceData.averageUnit3Grade += data['sum_unit3_grades'];
        generalPerformanceData.numStudents += data['num_students'];
        generalPerformanceData.numApprovedStudents += data['num_approved_students'];
        generalPerformanceData.numFailedStudents += data['num_failed_students'];
        generalPerformanceData.highestGrade = Math.max(generalPerformanceData.highestGrade, data['highest_grade']);
        generalPerformanceData.lowestGrade = Math.min(generalPerformanceData.lowestGrade, data['lowest_grade']);
        if (data['unit1_with_grade'] == false) {
            generalPerformanceData.unit1WithGrade = false;
        }
        if (data['unit2_with_grade'] == false) {
            generalPerformanceData.unit2WithGrade = false;
        }
        if (data['unit3_with_grade'] == false) {
            generalPerformanceData.unit3WithGrade = false;
        }

    });

    if (checkedOnlyProfessorClasses && (qtdTurmasProfessor == 0)) {
        groupClass.classList.add('d-none');
        let element = document.querySelector("#infoPesquisaDados");
        element.classList.remove("d-none");
        element.innerHTML = "Esse professor não possui turmas.<p> <small>Desmarque a opção \"Somente turmas do professor\" para ver ter um resultado mais geral.</small></p>";
        document.querySelector("#dadosDisciplina").classList.add("d-none");
        return;
    } else if (!checkedOnlyProfessorClasses) {
        groupClass.classList.remove('d-none');
    }
    else if (checkedOnlyProfessorClasses) {
        groupClass.classList.add('d-none');

    }
    generalPerformanceData.averageGrade = generalPerformanceData.averageGrade / generalPerformanceData.numStudents;
    generalPerformanceData.averageUnit1Grade = generalPerformanceData.averageUnit1Grade / generalPerformanceData.numStudents;
    generalPerformanceData.averageUnit2Grade = generalPerformanceData.averageUnit2Grade / generalPerformanceData.numStudents;
    generalPerformanceData.averageUnit3Grade = generalPerformanceData.averageUnit3Grade / generalPerformanceData.numStudents;
    document.querySelector("#selectClass").innerHTML = html;
    document.querySelector("#selectClass").selectedIndex = classSelectedIndex;

    if (!checkedAllClasses || (document.querySelector('#selectClass').value == -1)) {
        let mediaGeral = generalPerformanceData.averageGrade.toFixed(2);
        let mediaUnidade1 = generalPerformanceData.averageUnit1Grade.toFixed(2);
        let mediaUnidade2 = generalPerformanceData.averageUnit2Grade.toFixed(2);
        let mediaUnidade3 = generalPerformanceData.averageUnit3Grade.toFixed(2);
        let percentagemAprovados = ((generalPerformanceData.numApprovedStudents / generalPerformanceData.numStudents) * 100).toFixed(2);
        let percentagemReprovados = ((generalPerformanceData.numFailedStudents / generalPerformanceData.numStudents) * 100).toFixed(2);
        let progressNotaUnidade1 = ((mediaUnidade1 / 1) * 10);
        let progressNotaUnidade2 = ((mediaUnidade2 / 1) * 10);
        let progressNotaUnidade3 = ((mediaUnidade3 / 1) * 10);
        document.querySelector("#infoPesquisaDados").classList.add("d-none");
        document.querySelector("#dadosDisciplina").classList.remove("d-none");
        document.querySelector("#notaMediaComponente").innerHTML = mediaGeral;
        document.querySelector("#percentagemAprovados").innerHTML = percentagemAprovados + "%";
        document.querySelector("#progressAprovados").style.width = percentagemAprovados + "%";
        document.querySelector("#percentagemReprovados").innerHTML = percentagemReprovados + "%";
        document.querySelector("#progressReprovados").style.width = percentagemReprovados + "%";

        if (generalPerformanceData.unit1WithGrade) {
            document.querySelector("#notaUnidade1").innerHTML = mediaUnidade1;
            document.querySelector('#progressNotaUnidade1').style.width = progressNotaUnidade1 + "%";
        } else {
            document.querySelector("#notaUnidade1").innerHTML = "N/A";
            document.querySelector('#progressNotaUnidade1').style.width = 0;
        }
        if (generalPerformanceData.unit2WithGrade) {
            document.querySelector("#notaUnidade2").innerHTML = mediaUnidade2;
            document.querySelector('#progressNotaUnidade2').style.width = progressNotaUnidade2 + "%";
        } else {
            document.querySelector("#notaUnidade2").innerHTML = "N/A";
            document.querySelector('#progressNotaUnidade2').style.width = 0;
        }
        if (generalPerformanceData.unit3WithGrade) {
            document.querySelector("#notaUnidade3").innerHTML = mediaUnidade3;
            document.querySelector('#progressNotaUnidade3').style.width = progressNotaUnidade3 + "%";
        } else {
            document.querySelector("#notaUnidade3").innerHTML = "N/A";
            document.querySelector('#progressNotaUnidade3').style.width = 0;
        }

        if(generalPerformanceData.averageUnit1Grade == 0 &&
             generalPerformanceData.averageUnit2Grade == 0 && generalPerformanceData.averageUnit3Grade == 0){
                document.querySelector('#notasPorUnidade').classList.add('d-none');
        }else{
            document.querySelector('#notasPorUnidade').classList.remove('d-none');
        }

        if (checkedOnlyProfessorClasses) {
            document.querySelector("#infoTipoBusca").innerHTML = "Dados de " + qtdTurmasProfessor + " turma";
            if (qtdTurmasProfessor != 1) {
                document.querySelector("#infoTipoBusca").innerHTML += "s";
            }
        } else {
            document.querySelector("#infoTipoBusca").innerHTML = "Dados de " + classPerformanceDatas.length + " turma";
            if (classPerformanceDatas.length != 1) {
                document.querySelector("#infoTipoBusca").innerHTML += "s";
            }
        }

        if (checkedOnlyProfessorClasses) {
            document.querySelector("#infoProfessoresBusca").innerHTML = "Turmas com o(a) professor(a) " + professorName;
        }
        else {
            document.querySelector("#infoProfessoresBusca").innerHTML = "Turmas de todos os professores";
        }
        document.querySelector("#infoNumDiscentes").innerHTML = generalPerformanceData.numStudents + " discentes";
    } else {
        let index = document.querySelector('#selectClass').value;
        let data = classPerformanceDatas[index];
        let mediaGeral = data['average_grade'].toFixed(2);
        let mediaUnidade1 = data['average_grade_unit1'].toFixed(2);
        let mediaUnidade2 = data['average_grade_unit2'].toFixed(2);
        let mediaUnidade3 = data['average_grade_unit3'].toFixed(2);
        let percentagemAprovados = ((data['num_approved_students'] / data['num_students']) * 100).toFixed(2);
        let percentagemReprovados = ((data['num_failed_students'] / data['num_students']) * 100).toFixed(2);
        document.querySelector("#infoPesquisaDados").classList.add("d-none");
        document.querySelector("#dadosDisciplina").classList.remove("d-none");
        document.querySelector("#notaMediaComponente").innerHTML = mediaGeral;
        document.querySelector("#percentagemAprovados").innerHTML = percentagemAprovados + "%";
        document.querySelector("#progressAprovados").style.width = percentagemAprovados + "%";
        document.querySelector("#percentagemReprovados").innerHTML = percentagemReprovados + "%";
        document.querySelector("#progressReprovados").style.width = percentagemReprovados + "%";
        document.querySelector("#infoTipoBusca").innerHTML = "Apenas a turma " + classPerformanceDatas[index]['class_code'];
        document.querySelector("#infoNumDiscentes").innerHTML = classPerformanceDatas[index]['num_students'] + " discentes";
        if (data['unit1_with_grade']) {
            document.querySelector("#notaUnidade1").innerHTML = mediaUnidade1;
            document.querySelector("#progressNotaUnidade1").style.width = (mediaUnidade1 * 10) + '%';

        } else {
            document.querySelector("#notaUnidade1").innerHTML = "N/A";
            document.querySelector("#progressNotaUnidade1").style.width = 0;
        }
        if (data['unit2_with_grade']) {
            document.querySelector("#notaUnidade2").innerHTML = mediaUnidade2;
            document.querySelector("#progressNotaUnidade2").style.width = (mediaUnidade2 * 10) + '%';
        } else {
            document.querySelector("#notaUnidade2").innerHTML = "N/A"
            document.querySelector("#progressNotaUnidade2").style.width = 0;
        }
        if (data['unit3_with_grade']) {
            document.querySelector("#notaUnidade3").innerHTML = mediaUnidade3;
            document.querySelector("#progressNotaUnidade3").style.width = (mediaUnidade3 * 10) + '%';
        } else {
            document.querySelector("#notaUnidade3").innerHTML = "N/A"
            document.querySelector("#progressNotaUnidade3").style.width = 0;
        }

        if(data['average_grade_unit1']== 0 &&
            data['average_grade_unit2'] == 0 && data['average_grade_unit3'] == 0){
               document.querySelector('#notasPorUnidade').classList.add('d-none');
       }else{
           document.querySelector('#notasPorUnidade').classList.remove('d-none');
       }
       
        let professores = JSON.parse(classPerformanceDatas[index]['professors']);
        if (professores.length == 0) {
            document.querySelector("#infoProfessoresBusca").innerHTML = "sem professor";

        }
        else {
            if (checkedOnlyProfessorClasses) {
                document.querySelector("#infoProfessoresBusca").innerHTML = "Apenas turmas do(a) Professor(a): " + professorName;
            }
            else {
                document.querySelector("#infoProfessoresBusca").innerHTML = professores.length == 1 ? 'Professor(a): ' : 'professores(as): ';
                for (i = 0; i < professores.length; i++) {
                    document.querySelector("#infoProfessoresBusca").innerHTML += professores[i];
                    if (i != professores.length - 1) {
                        document.querySelector("#infoProfessoresBusca").innerHTML += ', '
                    }
                }
            }
        }
    }

    if (document.querySelector('#btnSearchDisciplineData').disabled) {
        document.querySelector('#infoBtnSearchDisciplineData').classList.remove('d-none');
    } else {
        document.querySelector('#infoBtnSearchDisciplineData').classList.add('d-none')
    }

}

function searchDisciplineData(disciplineCode) {
    let yearS = document.querySelector("#yearStart").value;
    let periodS = document.querySelector("#periodStart").value;
    let yearE = document.querySelector("#yearEnd").value;
    let periodE = document.querySelector("#periodEnd").value;
    let element = document.querySelector("#infoPesquisaDados");
    element.classList.remove("d-none");
    element.innerHTML = "Buscando dados...";
    document.querySelector('#dadosDisciplina').classList.add('d-none');
    let checkboxAllPeriods = document.querySelector('#checkAllPeriods').checked;
    $.ajax({
        url: '/api/performance/data/interval',
        method: 'GET',
        data: {
            disciplineCode: disciplineCode, yearStart: yearS, periodStart: periodS,
            yearEnd: yearE, periodEnd: periodE,
            checkAllPeriods: checkboxAllPeriods ? 'on' : null
        },
        success: function (result) {
            setLastSearchValues();
            document.querySelector('#btnSearchDisciplineData').disabled = true;
            document.querySelector('#infoBtnSearchDisciplineData').classList.remove('d-none');
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

            if (result.length > 0) {
                updateInfos();

            } else {
                resetValues();
                document
                    .querySelector("#dadosDisciplina")
                    .classList.add("d-none");
                let element = document.querySelector("#infoPesquisaDados");
                element.classList.remove("d-none");
                if (checkboxAllPeriods) {
                    element.innerHTML = "Não foram encontrados dados de índice de aprovação para esta disciplina."
                } else {
                    element.innerHTML = "Não foram encontrados dados de índice de aprovação para este período."
                }
                updateInfos();
            }

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
        }
    });

}

