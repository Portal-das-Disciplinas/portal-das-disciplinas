async function getComponentesCurriculares(codigo) {
    const response = await fetch(`/disciplinas/${codigo}/componentes-curriculares`);

    if (response.status === 200) {
        let componentsResponse = await response.json();
        let { ementa } = componentsResponse;

        if (!ementa.length) {
            return null;
        }

        return ementa.trim();
    }

    return null;
}

async function getReferenciasBibliograficas(codigo) {
    const response = await fetch(`/disciplinas/${codigo}/bibliografia`);

    if (response.status === 200) {
        let referencesResponse = await response.json();
        let referencesAdded = [];
        let referencesString = "";

        if (!referencesResponse.length) {
            return null;
        }


        referencesResponse.forEach(reference => {
            let normalizedReferenceName = reference.descricao.trim().toLowerCase();

            if (!referencesAdded.includes(normalizedReferenceName)) {
                referencesString += `${reference.tipo}: ${reference.descricao}\n`;
                referencesAdded.push(normalizedReferenceName);
            }
        });

        return referencesString;

    } else {
        return null;
    }
}

async function getCurriculumContent(codigo) {
    await Promise.all([
        getComponentesCurriculares(codigo),
        getReferenciasBibliograficas(codigo)
    ]);
}