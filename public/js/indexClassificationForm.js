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
    // checa se os ranges estão escondidos
    if ($(".advancedSearch").css("display") == "none") {
        // ranges irão ativar
        $(".advancedSearch").css("display", "flex");

        $(".simpleSearch").css("display", "none");
    } else {
        // ranges irão desativar
        $(".advancedSearch").css("display", "none");
        
        $(".simpleSearch").css("display", "flex");
    }
});