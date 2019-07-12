$(document).ready(function() {
    var date = new Date();

    $("#call-date")
        .children(".form-control")
        .val(
            date.getFullYear().toString() +
                "-" +
                (date.getMonth() + 1).toString().padStart(2, 0) +
                "-" +
                date
                    .getDate()
                    .toString()
                    .padStart(2, 0)
        );

    $(".datepicker--time-only").datetimepicker({
        format: "HH:mm"
    });
    $(".datepicker--date-only").datetimepicker({
        format: "YYYY-MM-DD"
    });
});
