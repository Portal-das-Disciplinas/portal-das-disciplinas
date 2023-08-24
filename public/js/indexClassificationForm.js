// advancedOptionButton
// simpleSearch
// advancedSearch

// Deixa  a div que contém os input ranges
// com display none por padrão
$("#advancedSearch").css("display", "none");

$("#advancedOptionButton").on("click", () => {
    // checa se os ranges estão escondidos
    if ($(".advancedSearch").css("display") == "none") {
        $(".advancedSearch").css("display", "flex");
    } else {
        $(".advancedSearch").css("display", "none");
    }
});