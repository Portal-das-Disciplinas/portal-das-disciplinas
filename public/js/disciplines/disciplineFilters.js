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
};

