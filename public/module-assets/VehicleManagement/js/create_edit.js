window.addEventListener("axiosModalSuccess", function (e) {
    // Existing route handling code
    $("#route_id").on("change", function () {
        var route_id = $(this).val();
        axios
            .get("/admin/vehicle/route-detail/" + route_id)
            .then(function (response) {
                console.log(response.data.data);
                $("#start_point").val(response.data.data.starting_point);
                $("#end_point").val(response.data.data.destination_point);
            });
    });

    // Add off-board fields toggle handler
    $("#is_active").on("change", function () {
        if ($(this).val() == "0") {
            $(".offboard-fields").show();
            $("#off_board_date").prop("required", true);
            $("#off_board_lot_number").prop("required", true);
            $("#off_board_buyer").prop("required", true);
            $("#off_board_amount").prop("required", true);
            $("#off_board_reason").prop("required", true);
            $("#off_board_remarks").prop("required", true);
        } else {
            $(".offboard-fields").hide();
            $("#off_board_date").prop("required", false);
            $("#off_board_lot_number").prop("required", false);
            $("#off_board_buyer").prop("required", false);
            $("#off_board_amount").prop("required", false);
            $("#off_board_reason").prop("required", false);
            $("#off_board_remarks").prop("required", false);
        }
    });

    // Trigger initial state check for off-board fields
    $("#is_active").trigger("change");
});
