async function getDisciplineClasses(disciplineCode) {
    const response = await fetch("/disciplinas/dados/turmas/" + disciplineCode, {
        method: "GET"
    });

    let data = null;

    if (response.status === 200) {
        data = await response.json();
    }

    return data;
}

async function getDisciplineTeacher(imdClass) {
    const response = await fetch(`/disciplinas/turmas/${imdClass['id-turma']}/docente`);

    let teacherName = "Docente não encontrado", id = null;

    if (response.status === 200) {
        let teacherResponse = await response.json();

        if (teacherResponse != null && teacherResponse.length > 0) {
            teacherName = teacherResponse[0]['nome-docente'];
            id = teacherResponse[0]['id-docente'];
        }
    }

    return {
        id: id,
        docente: teacherName,
        turma: imdClass['id-turma'],
        horario: imdClass['descricao-horario']
    };
}

async function getDisciplineTeachers(classes, sort = false) {
    const promises = classes.map(getDisciplineTeacher);
    const responses = await Promise.all(promises);

    if (sort && responses.length > 1) {
        return responses.sort((a, b) => {
            if (a.docente === null || b.docente === null) {
                a.docente = "Docente não encontrado";
            }

            return a.docente.localeCompare(b.docente);
        });
    }

    return responses;
}

async function handleLastOffer(classes) {
    if (classes.length > 0) {
        let showingAll = false;

        let latestYear = classes[0].ano, latestPeriod = classes[0].periodo;
        $('#ultima-oferta').html(`${latestYear}.${latestPeriod}`);

        let stateElement = '<p class="font-weight-bold" id="last-offer-state">Buscando...</p>';
        $('#collapUltimaOfertaBody').prepend(stateElement);

        let latestClasses = classes.filter(c => {
            return c.ano === latestYear;
        });

        let latestTeachers = await getDisciplineTeachers(latestClasses, true);

        let lastOfferList = $('#last-offers-list');

        if (latestTeachers.length > 0) {
          
            function render(teacherArray, indexFrom = null, indexTo = null) {
                $(lastOfferList).html("");

                if (indexFrom === null) {
                    indexFrom = 0;
                }

                let teacherArrayLastIndex = teacherArray.length - 1;

                if (indexTo === null || indexTo > teacherArrayLastIndex) {
                    indexTo = teacherArrayLastIndex;
                }


                for (let i = indexFrom; i <= indexTo; i++) {
                    let data = teacherArray[i];

                    let [imdClass,] = latestClasses.filter(c => c['id-turma'] === data.turma);

                    $(lastOfferList).append(`
                    <li class="d-flex flex-column">
                        <hr class="p-2">
                        <div>
                            <span>${imdClass.ano}.${imdClass.periodo} -</span> 
                            <span style="text-transform: capitalize;">${data.docente.toLowerCase()} -</span>
                            <span>Turma ${imdClass['codigo-turma']}</span>
                        </div>
                        <div>
                            <span class="small">${imdClass['descricao-horario']}</span>
                        </div>
                    </li>
                    `);

                }

            }

            render(latestTeachers, 0, 2);

            $('#last-offer-state').html(`
            <div class="w-full d-flex align-items-center justify-content-between" id="last-offer-controls">
                <span>Turmas</span>
                <button type="button" class="btn btn-link" id="last_load-more">Ver mais</button>
            </div>
            `);

            $("#last_load-more").on("click", function (event) {
                let indexFrom = null, indexTo = null;

                if (!showingAll) {
                    $(this).html("Ver menos");
                } else {
                    indexFrom = 0;
                    indexTo = 2;
                    $(this).html("Ver mais");
                }

                showingAll = !showingAll;

                render(latestTeachers, indexFrom, indexTo);
            });
        } else {
            $('#collapUltimaOfertaBody').html("Dados não encontrados :(");
        }
    } else {
        $('#ultima-oferta').html("Infelizmente não conseguimos buscar estes dados :(");
    }
}

async function handleOffersHistory(classes) {
    if (classes.length > 0) {
        let stateElement = '<p class="font-weight-bold" id="history-state">Buscando...</p>';
        $('#collapOfertasPassadasBody').prepend(stateElement);

        const currentYear = new Date().getFullYear();

        let classesIn5Years = classes.filter(c => {
            return c.ano >= currentYear - 5;
        });

        let teachers = await getDisciplineTeachers(classesIn5Years, true);


        if (teachers.length > 0) {
            let showingAll = false;
            let offersHistoryList = $('#offers-history');

            function render(teacherArray, indexFrom = null, indexTo = null) {

                $(offersHistoryList).html(""); // Clear list content

                if (indexFrom === null) {
                    indexFrom = 0;
                }

                if (indexTo === null) {
                    indexTo = teacherArray.length - 1;
                }


                for (let i = indexFrom; i <= indexTo; i++) {
                    let data = teacherArray[i];

                    let [imdClass,] = classes.filter(c => c['id-turma'] === data.turma);

                    $(offersHistoryList).append(`
                    <li class="d-flex flex-column">
                        <hr class="p-2">
                        <div>
                            <span>${imdClass.ano}.${imdClass.periodo} -</span> 
                            <span style="text-transform: capitalize;">${data.docente.toLowerCase()} -</span>
                            <span>Turma ${imdClass['codigo-turma']}</span>
                        </div>
                        <div>
                            <span class="small">${imdClass['descricao-horario']}</span>
                        </div>
                    </li>
                    `);

                }
            }

            render(teachers, 0, 2);

            $('#history-state').html(`
            <div class="d-flex flex-column gap-3">
                <div class="row">
                    <div class="my-3 col teacher-filter">
                        <label class="form-label" for="teacher-select">Professor</label>
                    </div>
                    <div class="my-3 col">
                        <label class="form-label" for="schedule-input">Horário</label>
                        <input type="text" class="form-control" placeholder="Exemplo: 24M12" id="schedule-input" />
                    </div>
                </div>

                <button type="button" class="btn btn-primary col-md-4 mb-3" id="filter-classes">Filtrar</button>
            </div>
            <div class="w-full d-flex align-items-center justify-content-between" id="history-controls">
                <span>Turmas</span>
                <button type="button" class="btn btn-link" id="load-more">Ver mais</button>
            </div>
            `);

            /* Set up teacher select */
            let teachersSelect = $('<select class="form-control mb-3" style="cursor: pointer;" id="teacher-select"></select>');
            teachersSelect.append('<option value="null" default>Todos os professores</option>');

            let teachersIncludedAtSelect = [];

            teachers.forEach(teacher => {
                if (teacher.id != null && !teachersIncludedAtSelect.includes(teacher.id)) {
                    teachersIncludedAtSelect.push(teacher.id);
                    teachersSelect.append(`<option value="${teacher.id}">${teacher.docente}</option>`);
                }
            });


            $('.teacher-filter').append(teachersSelect);


            let nonFilteredTeachers = teachers;

            $('#filter-classes').on("click", function () {
                let selectedId = $('#teacher-select').val();
                let schedule = $('#schedule-input').val().toUpperCase().trim();

                if (selectedId === "null" && schedule === "") {
                    teachers = nonFilteredTeachers;
                } else {
                    if (!schedule && selectedId === "null") {
                        teachers = nonFilteredTeachers;
                    } else if (selectedId === "null" && schedule) {
                        teachers = nonFilteredTeachers.filter(t => t.horario.includes(schedule));
                    } else if (selectedId !== "null" && !schedule) {
                        teachers = nonFilteredTeachers.filter(t => String(t.id) === selectedId);
                    } else {
                        teachers = nonFilteredTeachers.filter(t => t.horario.includes(schedule) && String(t.id) === selectedId);
                    }
                }

                showingAll ? render(teachers) : render(teachers, 0, 2);;
            });


            $("#load-more").on("click", function () {
                let indexFrom = null, indexTo = null;

                if (!showingAll) {
                    $(this).html("Ver menos");
                } else {
                    indexFrom = 0;
                    indexTo = 2;
                    $(this).html("Ver mais");
                }

                showingAll = !showingAll;

                render(teachers, indexFrom, indexTo);
            });
        } else {
            $('#collapOfertasPassadasBody').html("Dados não encontrados :(");
        }
    }
}

async function getOffersData(disciplineCode) {
    let classes = await getDisciplineClasses("IMD1101");

    await Promise.all([
        handleLastOffer(classes),
        handleOffersHistory(classes)
    ]);
}