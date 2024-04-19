const disabledStyle = "background-color: lightgray; opacity: 70%;";

function changeApprovalFilterFields() {
    let checkFiltroAprovacao = document.querySelector("#check-filtro-aprovacao");
    let selectTipoAprov = document.querySelector('#select-tipo-aprov');
    let selectComparacao = document.querySelector('#select-comparacao');
    let inputValorComparacao = document.querySelector('#input-valor-comparacao');
    let selectAnoAprov = document.querySelector('#select-ano-aprov');
    let selectPeriodoAprov = document.querySelector('#select-periodo-aprov');
    if (checkFiltroAprovacao.checked) {
        selectTipoAprov.disabled = false;
        selectComparacao.disabled = false;
        inputValorComparacao.disabled = false;
        selectAnoAprov.disabled = false;
        selectPeriodoAprov.disabled = false;
        selectTipoAprov.style = "";
        selectComparacao.style = "";
        inputValorComparacao.style = "";
        selectAnoAprov.style = "";
        selectPeriodoAprov.style = "";
    } else {
        selectTipoAprov.disabled = true;
        selectComparacao.disabled = true;
        inputValorComparacao.disabled = true;
        selectAnoAprov.disabled = true;
        selectPeriodoAprov.disabled = true;
        selectTipoAprov.style = disabledStyle;
        selectComparacao.style = disabledStyle;
        inputValorComparacao.style = disabledStyle;
        selectAnoAprov.style = disabledStyle;
        selectPeriodoAprov.style = disabledStyle;
    }
}

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
    changeApprovalFilterFields();
}

window.onload = function () {
    $('#collapse-filters').on('hide.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            let selectProfessor = document.querySelector("#select-professors");
            let checkClassificationFilter = document.querySelector("#check-filtro-classificacoes");
            let checkApprovalFilter = document.querySelector("#check-filtro-aprovacao");
            selectProfessor.selectedIndex = 0;
            checkClassificationFilter.checked = false;
            checkApprovalFilter.checked = false;
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
            document.querySelector("#filtro-livre").value = null;
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

function onChangeClassificationSlider(event) {
    let classificationValue = document.querySelector("#" + event.target.id + 'info_value');
    let checkbox = document.querySelector("#classification_detail_active" + event.target.id.replace('classification_detail', ''));
    classificationValue.innerHTML = ">= " + event.target.value;
    checkbox.checked = true;
}

function onClickClassificationFilterType(event) {
    event.target.checked = true;
    if (event.target.id == "filtro-classificacoes-caracteristica") {
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

function onCheckClassificationFilter(event) {
    if (event.target.checked) {
        $('#collapse-classificacoes').collapse('show');
    }
    else {
        $('#collapse-classificacoes').collapse('hide');
    }
}

changeApprovalFilterFields();