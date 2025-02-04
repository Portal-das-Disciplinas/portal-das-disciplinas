const disabledStyle = "background-color: lightgray; opacity: 70%;";

function changeClassificationFilter() {
    let checkFeatureFilter = document.querySelector('#filtro-classificacoes-caracteristica');
    let checkDetailFilter = document.querySelector('#filtro-classificacoes-detalhado');
    if (checkFeatureFilter.checked == false && checkDetailFilter.checked == false) {
        checkFeatureFilter.checked = true;
    }
    if (checkFeatureFilter.checked) {
        document.querySelector("checked");
        document.querySelector("#filtro-classificacoes-detalhado").checked = false;
        document.querySelector("#area-caracteristica-predominante").classList.remove("d-none");
        document.querySelector("#area-filtro-detalhado").classList.add("d-none");
    }
    else {
        document.querySelector("#filtro-classificacoes-caracteristica").checked = false;
        document.querySelector("#area-filtro-detalhado").classList.remove("d-none");
        document.querySelector("#area-caracteristica-predominante").classList.add("d-none");
    }
}

function onChangeCheckFilterApproval(event) {
    enableApprovalFilters(event.target.checked);
}

window.onload = function () {
    $('#collapse-filters').on('hide.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            let selectProfessor = document.querySelector("#select-professors");
            let checkClassificationFilter = document.querySelector("#check-filtro-classificacoes");
            let checkApprovalFilter = document.querySelector("#check-filtro-aprovacao");
            selectProfessor.selectedIndex = 0;
            filteredMethodologiesInputValues = [];
            checkClassificationFilter.checked = false;
            checkApprovalFilter.checked = false;
            clearSelectedMethodologies();
        }
    });

    $('#collapse-filters').on('shown.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            document.querySelector("#texto-mostrar-filtros").innerHTML = "Busca Avançada  <li class='fa fa-caret-up'></li>";
        }
    });

    $('#collapse-filters').on('hidden.bs.collapse', function (event) {
        $('#collapse-classificacoes').collapse('hide');
        if (event.target.id == "collapse-filters") {
            document.querySelector("#texto-mostrar-filtros").innerHTML = "Busca Avançada  <li class='fa fa-caret-down'></li>";
        }
    });
};

function onSelectClassificationTypeA(event, classificationId) {
    let typeAName = document.querySelector('#classification_detail_type_a_name' + classificationId);
    let typeBName = document.querySelector('#classification_detail_type_b_name' + classificationId);
    if (event.target.checked) {
        typeAName.classList.add('text-primary');
        typeAName.classList.remove('text-secondary');
        typeBName.classList.remove('text-primary');
        typeBName.classList.add('text-secondary');
    } else {
        typeAName.classList.remove('text-primary');
        typeAName.classList.add('text-secondary');
        typeBName.classList.add('text-primary');
        typeBName.classList.remove('text-secondary');
    }

}

function onSelectClassificationTypeB(event, classificationId) {
    let typeAName = document.querySelector('#classification_detail_type_a_name' + classificationId);
    let typeBName = document.querySelector('#classification_detail_type_b_name' + classificationId);
    if (event.target.checked) {
        typeAName.classList.remove('text-primary');
        typeAName.classList.add('text-secondary');
        typeBName.classList.add('text-primary');
        typeBName.classList.remove('text-secondary');
    } else {
        typeAName.classList.add('text-primary');
        typeAName.classList.remove('text-secondary');
        typeBName.classList.remove('text-primary');
        typeBName.classList.add('text-secondary');
    }

}

function updateDetailedClassificationsStyles(classifications) {
    let classificationsArray = Object.keys(classifications).map(key => {
        return (classifications[key]);
    });

    classificationsArray.forEach(classification => {
        let radioTypeA = document.querySelector('#classification_detail_type_a_value' + classification.id);
        let typeAName = document.querySelector('#classification_detail_type_a_name' + classification.id);
        let typeBName = document.querySelector('#classification_detail_type_b_name' + classification.id);
        if (radioTypeA.checked) {
            typeAName.classList.add('text-primary');
            typeAName.classList.remove('text-secondary');
            typeBName.classList.remove('text-primary');
            typeBName.classList.add('text-secondary');

        } else {
            typeAName.classList.remove('text-primary');
            typeAName.classList.add('text-secondary');
            typeBName.classList.add('text-primary');
            typeBName.classList.remove('text-secondary');
        }

    });
}

function onChangeClassificationSlider(event) {
    let classificationValue = document.querySelector("#" + event.target.id + 'info_value');
    let checkbox = document.querySelector("#classification_detail_active" + event.target.id.replace('classification_detail', ''));
    classificationValue.innerHTML = ">= " + event.target.value;
    checkbox.checked = true;
}

function showClassificationsArea() {
    if (document.querySelector("#filtro-classificacoes-caracteristica").checked) {
        document.querySelector("#filtro-classificacoes-detalhado").checked = false;
        document.querySelector("#area-caracteristica-predominante").classList.remove("d-none");
        document.querySelector("#area-filtro-detalhado").classList.add("d-none");
    }
    else {
        document.querySelector("#filtro-classificacoes-caracteristica").checked = false;
        document.querySelector("#area-filtro-detalhado").classList.remove("d-none");
        document.querySelector("#area-caracteristica-predominante").classList.add("d-none");
    }
}

function onClickClassificationFilterType(event) {
    event.target.checked = true;
    if (event.target.id == "filtro-classificacoes-caracteristica") {
        document.querySelector("#filtro-classificacoes-detalhado").checked = false;
    }
    else {
        document.querySelector("#filtro-classificacoes-caracteristica").checked = false;
    }
    showClassificationsArea();
}

function onCheckClassificationFilter(event) {
    if (event.target.checked) {
        $('#collapse-classificacoes').collapse('show');
    }
    else {
        $('#collapse-classificacoes').collapse('hide');
    }
}

function enableApprovalFilters(enable) {
    let checkFiltroAprovacao = document.querySelector("#check-filtro-aprovacao");
    let selectTipoAprov = document.querySelector('#select-tipo-aprov');
    let selectComparacao = document.querySelector('#select-comparacao');
    let inputValorComparacao = document.querySelector('#input-valor-comparacao');
    let selectAnoAprov = document.querySelector('#select-ano-aprov');
    let selectPeriodoAprov = document.querySelector('#select-periodo-aprov');

    checkFiltroAprovacao.checked = enable;
    selectTipoAprov.disabled = !enable;
    selectComparacao.disabled = !enable;
    inputValorComparacao.disabled = !enable;
    selectAnoAprov.disabled = !enable;
    selectPeriodoAprov.disabled = !enable;

    selectTipoAprov.style = selectTipoAprov.disabled ? disabledStyle : "";
    selectComparacao.style = selectComparacao.disabled ? disabledStyle : "";
    inputValorComparacao.style = inputValorComparacao.disabled ? disabledStyle : "";
    selectAnoAprov.style = selectAnoAprov.disabled ? disabledStyle : "";
    selectPeriodoAprov.style = selectPeriodoAprov.disabled ? disabledStyle : "";
}

if (document.querySelector('#check-filtro-classificacoes').checked) {
    $('#collapse-classificacoes').collapse('show');
}
else {
    $('#collapse-classificacoes').collapse('hide');
}

if (document.querySelector('#check-filtro-classificacoes').checked
    || document.querySelector('#check-filtro-aprovacao').checked
    || document.querySelector('#select-professors').selectedIndex != 0
    ) {

    $('#collapse-filters').collapse('show');
}

showClassificationsArea()