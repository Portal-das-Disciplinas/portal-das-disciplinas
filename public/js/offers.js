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

    let data = null;

    if (response.status === 200) {
        data = await response.json();
    }

    return {
        docente: data[0]["nome-docente"],
        turma: imdClass['id-turma']
    };
}

async function getDisciplineTeachers(classes) {
    const promises = classes.map(getDisciplineTeacher);
    const responses = await Promise.all(promises);

    return responses.sort((a, b) => a.docente.localeCompare(b.docente));
}

async function getOffersData() {
    let classes = await getDisciplineClasses(disciplineCode);

    if (classes[0] != null) {
        let latestYear = classes[0].ano, latestPeriod = classes[0].periodo;
        $('#ultima-oferta').html(`${latestYear}.${latestPeriod}`);

        $('#collapUltimaOfertaBody').append("Buscando...");

        let latestClasses = classes.filter(c => {
            return c.ano === latestYear;
        });

        let latestTeachers = await getDisciplineTeachers(latestClasses);
        let lastOfferList = $('<ul class="d-flex flex-column gap-3"></ul>');

        if (latestTeachers.length > 0) {
            latestTeachers.forEach(data => {
                let [imdClass,] = latestClasses.filter(c => c['id-turma'] === data.turma);

                $(lastOfferList).append(`<li>Prof: <span style="text-transform: capitalize;">${data.docente.toLowerCase()}</span> - Turma: ${imdClass['codigo-turma']}</li>`);
            });

            $('#collapUltimaOfertaBody').html(lastOfferList);
        } else {
            $('#collapUltimaOfertaBody').html("Dados não encontrados :(");
        }
    } else {
        $('#ultima-oferta').html("Infelizmente não conseguimos buscar estes dados :(");
    }

}