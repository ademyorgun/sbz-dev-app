$(document).ready(function() {
    $('.datepicker--time-only').datetimepicker({
        format: 'HH:mm'
    });
    $('.datepicker--date-only').datetimepicker({
        format: 'YYYY-MM-DD'
    });
});