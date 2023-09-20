// advancedOptionButton
// simpleSearch
// advancedSearch

// Deixa  a div que contém os input ranges
// com display none por padrão
$(".advancedSearch").css("display", "none");
$(".simpleSearch").css("display", "flex");
$("#advancedOptionButton").css("display", "none");


$("#AccordionButton").on("click", () => {
    if ($("#AccordionButton").attr("aria-expanded") == "false") {
        $("#advancedOptionButton").css("display", "flex");
    } else {
        $("#advancedOptionButton").css("display", "none");
    }
});

$("#advancedOptionButton").on("click", () => {
    $("#resetButton").trigger("click");
    $("#resetButton").on("click", () => {
        $(".mostrador").text("0");
        // alert("oii");
    });
    // checa se os ranges estão escondidos
    if ($(".advancedSearch").css("display") == "none") {
        // ranges irão ativar
        $("#caracteristicas").css("display", "none");
        $(".advancedSearch").css("display", "flex");

        // Quando os ranges ativarem mudar o value de mínimo deles
        // de -1 para 0
        $(".range").attr("min", 0); 
        $(".range").attr("value", 0);

        $(".simpleSearch").css("display", "none");
    } else {
        // ranges irão desativar
        $("#caracteristicas").css("display", "flex");
        $(".advancedSearch").css("display", "none");
        
        // Quando os ranges ativarem mudar o value de mínimo deles
        // de 0 para -1
        $(".range").attr("min", -1);
        $(".range").attr("value", -1);

        $(".simpleSearch").css("display", "flex");
    }
});