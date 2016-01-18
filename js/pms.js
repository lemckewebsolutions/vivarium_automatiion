$(document).ready(function () {
    $(".switch").bootstrapSwitch();

    $(".js-relaySwitch").on('switchChange.bootstrapSwitch', function(event, state) {

        var reversed = $(this).data("reversed");
        var value = 0;

        if (state == true && reversed == 0 ||
            state == false && reversed == 1) {
            value = 1;
        }

        $.ajax({
                url: "/control/" + $(this).attr("name"),
                data: {
                    "name": "relay",
                    "value": value
                },
                type: "POST"
            })
            .done(function(data)
            {
                console.log(data);
            });
    });

    $(".js-autoPilot").on('switchChange.bootstrapSwitch', function(event, state) {
        console.log(); // DOM element

        var value = 0;

        if (state == true) {
            value = 1;
        }

        $.ajax({
                url: "/control/" + $(this).attr("name"),
                data: {
                    "name": "autoPilot",
                    "value": value
                },
                type: "POST"
            })
            .done(function(data)
            {
                console.log(data);
            });
    });
});
