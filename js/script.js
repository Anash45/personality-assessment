function optionChanged() {
    $('.option').each(function () {
       
        var checkedCount = $(this).closest('.table').find('.option:checked').length;
        $(this).closest('.table').find('.table-total').text(checkedCount);
        if ($(this).is(':checked')) {
            $(this).closest('label').addClass('checked');
        } else {
            $(this).closest('label').removeClass('checked');
        }
    });
}

$(document).ready(function () {
    optionChanged();
    $('.option').change(function () {
        optionChanged();
    });
})