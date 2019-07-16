$(document).ready(function () {
    var date = new Date();

    $(".datepicker--date-only").datetimepicker({
        format: "DD-MM-YYYY"
    });
    
    $("#call-date")
        .children(".form-control").attr('readonly', 'readonly')
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
    
    // disable chaging the value by mouse scroll
    $('form').on('focus', 'input[type=number]', function (e) {
        $(this).on('mousewheel.disableScroll', function (e) {
            e.preventDefault();
        })
    });
    $('form').on('blur', 'input[type=number]', function (e) {
        $(this).off('mousewheel.disableScroll');
    });
    console.log('tset');
    // $('form').on('focus', 'input[type=number]', function (e) {
    //     $(this).on('mousewheel.disableScroll', function (e) {
    //         e.preventDefault()
    //     })
    // });
});
