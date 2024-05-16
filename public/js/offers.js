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

    let teacherName = "Não encontrado";

    if (response.status === 200) {
        let teacherResponse = await response.json();

        if (teacherResponse != null && teacherResponse.length > 0) {
            teacherName = teacherResponse[0]['nome-docente'];
        }
    }

    return {
        docente: teacherName,
        turma: imdClass['id-turma']
    };
}

async function getDisciplineTeachers(classes, sort = false) {
    const promises = classes.map(getDisciplineTeacher);
    const responses = await Promise.all(promises);

    if (sort) {
        return responses.sort((a, b) => a.docente.localeCompare(b.docente));
    }

    return responses;
}

async function handleLastOffer(classes) {
    if (classes[0] != null) {
        let latestYear = classes[0].ano, latestPeriod = classes[0].periodo;
        $('#ultima-oferta').html(`${latestYear}.${latestPeriod}`);

        $('#collapUltimaOfertaBody').append("Buscando...");

        let latestClasses = classes.filter(c => {
            return c.ano === latestYear;
        });

        let latestTeachers = await getDisciplineTeachers(latestClasses, true);
        let lastOfferList = $('<ul class="d-flex flex-column gap-3"></ul>');

        if (latestTeachers.length > 0) {
            latestTeachers.forEach(data => {
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
                </li>`);
            });

            $('#collapUltimaOfertaBody').html(lastOfferList);
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
        
        let offersHistoryList = $('#offers-history');
        let teachers = await getDisciplineTeachers(classesIn5Years, true);

        if (teachers.length > 0) {
            let indexFrom = 0, indexTo = 2, showingAll = false;

            function render() {
                $(offersHistoryList).html("");

                for (let i = indexFrom; i <= indexTo; i++) {
                    let data = teachers[i];
    
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

            render();
            $('#history-state').html(`
            <div class="w-full d-flex align-items-center justify-content-between" id="history-controls">
                <span>Turmas</span>
                <button type="button" class="btn btn-link" id="load-more">Ver mais</button>
            </div>
            `);


            $("#load-more").on("click", function(event) {
                event.stopPropagation();

                if (!showingAll) {
                    indexFrom = 3;
                    indexTo = teachers.length - 1;
                    $(this).html("Ver menos");
                } else {
                    indexFrom = 0;
                    indexTo = 2;
                    $(this).html("Ver mais");
                }

                showingAll = !showingAll;

                render();
            });
        } else {
            $('#collapOfertasPassadasBody').html("Dados não encontrados :(");
        }
    }
}

async function getOffersData(disciplineCode) {
    let classes = await getDisciplineClasses("IMD1001");

    await Promise.all([
        handleLastOffer(classes),
        handleOffersHistory(classes)
    ]);
}