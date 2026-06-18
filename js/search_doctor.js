$(document).ready(function() {
    $("#doctorName").keyup(function() {
        let query = $(this).val();
        if(query != "") {
            $.ajax({
                url: "search_doctor.php",
                method: "POST",
                data: { query: query },
                success: function(data) {
                    $("#doctorList").fadeIn().html(data);
                }
            });
        } else {
            $("#doctorList").fadeOut().empty();
        }
    });

    $(document).on("click", "#doctorList div", function() {
        $("#doctorName").val($(this).text());
        $("#doctorList").fadeOut().empty();
    });
});
