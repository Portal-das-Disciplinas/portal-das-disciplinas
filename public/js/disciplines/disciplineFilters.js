const disabledStyle = "background-color: lightgray; opacity: 70%;";

function changeFilterFields() {
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
function onChangeCheckFilterApproval(event) {
    changeFilterFields();
}

window.onload = function () {
    changeFilterFields();
    $('#collapse-filters').on('hide.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            let selectProfessor = document.querySelector("#select-professors");
            let checkClassificationFilter = document.querySelector("#check-filtro-classificacoes");
            let checkApprovalFilter = document.querySelector("#check-filtro-aprovacao");
            selectProfessor.selectedIndex = 0;
            checkClassificationFilter.checked = false;
            checkApprovalFilter.checked = false;
            changeFilterFields();
        }

    });

    $('#collapse-filters').on('shown.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            document.querySelector("#texto-mostrar-filtros").innerHTML = "- filtros";
        }
    });

    $('#collapse-filters').on('hidden.bs.collapse', function (event) {
        if (event.target.id == "collapse-filters") {
            document.querySelector("#texto-mostrar-filtros").innerHTML = "+ filtros";
        }
    });
};

function onChangeClassificationSlider(event) {
    let classificationValue = document.querySelector("#" + event.target.id + 'info_value');
    classificationValue.innerHTML = event.target.value;
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



