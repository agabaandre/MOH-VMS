window.addEventListener("axiosModalSuccess", function (e) {
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
});
