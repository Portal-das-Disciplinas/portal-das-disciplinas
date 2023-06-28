// $("#advancedFilter").css("display", "none");

$("#filterButton").on("click", () => {
    $("#advancedFilter").css("display", "flex");

    if($("#filterButton").attr("aria-expanded") != "true"){
        $("#advancedFilter").css("display", "flex");
    } else {
        $("#advancedFilter").css("display", "none");
    }
});

$("#triggerMetodologias").on("click", () => {
    if($("#metodologias").css("display") != "none"){
        $("#metodologias").css("display","none");
    } else {
        $("#metodologias").css("display","flex");
    }
})

$("#triggerDiscussao").on("click", () => {
    if($("#discussao").css("display") != "none"){
        $("#discussao").css("display","none");
    } else {
        $("#discussao").css("display","flex");
    }
})

$("#triggerAbordagem").on("click", () => {
    if($("#abordagem").css("display") != "none"){
        $("#abordagem").css("display","none");
    } else {
        $("#abordagem").css("display","flex");
    }
})

$("#triggerAvaliacao").on("click", () => {
    if($("#avaliacao").css("display") != "none"){
        $("#avaliacao").css("display","none");
    } else {
        $("#avaliacao").css("display","flex");
    }
})

$("#triggerHorario").on("click", () => {
    if($("#horario").css("display") != "none"){
        $("#horario").css("display","none");
    } else {
        $("#horario").css("display","flex");
    }
})

$("#advancedFilter").on("click", () => {
    $("#metodologias").css("display","none");
    $("#triggerMetodologias").prop('disabled', true);
    
    $("#range-metodologia").attr({'min': 0});
    $("#range-metodologia").attr({'value': 0});

    $("#discussao").css("display","none");
    $("#triggerDiscussao").prop('disabled', true);

    $("#range-discussao").attr({'min': 0});
    $("#range-discussao").attr({'value': 0});

    $("#abordagem").css("display","none");
    $("#triggerAbordagem").prop('disabled', true);

    $("#range-abordagem").attr({'min': 0});
    $("#range-abordagem").attr({'value': 0});

    $("#avaliacao").css("display","none");
    $("#triggerAvaliacao").prop('disabled', true);

    $("#range-avaliacao").attr({'min': 0});
    $("#range-avaliacao").attr({'value': 0});

    $("#horario").css("display","none");
    $("#triggerHorario").prop('disabled', true);

    $("#range-horario").attr({'min': 0});
    $("#range-horario").attr({'value': 0});

    if($("#abordagem-range").css("display") != "none"){
        $("#metodologias-range").css("display","none");
        $("#metodologias-range").val('');
        $("#triggerMetodologias").prop('checked', false);
        $("#triggerMetodologias").prop('disabled', false);

        $("#range-metodologia").attr({'min': -1});
        $("#range-metodologia").attr({'value': -1});

        $("#discussao-range").css("display","none");
        $("#discussao-range").val('');
        $("#triggerDiscussao").prop('checked', false);
        $("#triggerDiscussao").prop('disabled', false);

        $("#range-discussao").attr({'min': -1});
        $("#range-discussao").attr({'value': -1});

        $("#abordagem-range").css("display","none");
        $("#abordagem-range").val('');
        $("#triggerAbordagem").prop('checked', false);
        $("#triggerAbordagem").prop('disabled', false);

        $("#range-abordagem").attr({'min': -1});
        $("#range-abordagem").attr({'value': -1});

        $("#avaliacao-range").css("display","none");
        $("#avaliacao-range").val('');
        $("#triggerAvaliacao").prop('checked', false);
        $("#triggerAvaliacao").prop('disabled', false);

        $("#range-avaliacao").attr({'min': -1});
        $("#range-avaliacao").attr({'value': -1});

        $("#horario-range").css("display","none");
        $("#horario-range").val('');
        $("#triggerHorario").prop('checked', false);
        $("#triggerHorario").prop('disabled', false);

        $("#range-horario").attr({'min': -1});
        $("#range-horario").attr({'value': -1});
    } else {
        $("#metodologias-range").css("display","flex");
        $("#triggerMetodologias").prop('checked', false);

        $("#discussao-range").css("display","flex");
        $("#triggerDiscussao").prop('checked', false);

        $("#abordagem-range").css("display","flex");
        $("#triggerAbordagem").prop('checked', false);

        $("#avaliacao-range").css("display","flex");
        $("#triggerAvaliacao").prop('checked', false);

        $("#horario-range").css("display","flex");
        $("#triggerHorario").prop('checked', false);
    }
});