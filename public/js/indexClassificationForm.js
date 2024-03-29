/* Deixa  a div que contém os input ranges
   com display none por padrão */
$(".advancedSearch").css("display", "none");
$(".simpleSearch").css("display", "flex");
$("#advancedOptionButton").css("display", "none");
$("#unmarkAll").css("display", "none");

$("#unmarkAll").on("click", () => {
    $("#resetButton").trigger("click");
});

$("#AccordionButton").on("click", () => {
    /*Ao clicar no filtro por aprovação
    altera a propriedade filtro para aprovacao,
    indicando que o filtro por aprovacao será utilizado*/

    if ($("#AccordionButton").attr("aria-expanded") == "false") {
        $("#advancedOptionButton").css("display", "flex");
        $("#unmarkAll").css("display", "flex");
        $("#filtro").attr("value", "classificacao");
    } else {
        $("#filtro").attr("value", "null");
        $("#advancedOptionButton").css("display", "none");
        $("#unmarkAll").css("display", "none");
    }
});

$("#advancedOptionButton").on("click", () => {
    $("#resetButton").trigger("click");
    $("#resetButton").on("click", () => {
        $(".mostrador").text("0");
    });
    // checa se os ranges estão escondidos
    if ($(".advancedSearch").css("display") == "none") {
        // ranges irão ativar
        $("#unmarkAll").css("display", "none");
        $("#caracteristicas").css("display", "none");
        $(".advancedSearch").css("display", "flex");

        // Quando os ranges ativarem mudar o value de mínimo deles
        // de -1 para 0
        $(".range").attr("min", 0);
        $(".range").attr("value", 0);

        $(".simpleSearch").css("display", "none");
    } else {
        // ranges irão desativar
        $("#unmarkAll").css("display", "flex");
        $("#caracteristicas").css("display", "flex");
        $(".advancedSearch").css("display", "none");

        // Quando os ranges ativarem mudar o value de mínimo deles
        // de 0 para -1
        $(".range").attr("min", -1);
        $(".range").attr("value", -1);

        $(".simpleSearch").css("display", "flex");
    }
});

$("#aprovationsButton").on("click", () => {
    /*Ao clicar no filtro por aprovação
    altera a propriedade filtro para aprovacao,
    indicando que o filtro por aprovacao será utilizado*/

    if ($("#aprovationsButton").attr("aria-expanded") == "false") {
        $("#filtro").attr("value", "aprovacao");
    } else {
        $("#filtro").attr("value", "null");
    }

    //Desativa os ranges
    $(".range").attr("min", -1);
    $(".range").attr("value", -1);
});

$("#professorsButton").on("click", () => {
    /*Ao clicar no filtro por aprovação
    altera a propriedade filtro para aprovacao,
    indicando que o filtro por aprovacao será utilizado*/

    if ($("#professorsButton").attr("aria-expanded") == "false") {
        $("#filtro").attr("value", "professor");
    } else {
        $("#filtro").attr("value", "null");
    }

    //Desativa os ranges
    $(".range").attr("min", -1);
    $(".range").attr("value", -1);
});