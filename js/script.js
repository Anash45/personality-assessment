function optionChanged() {
    calculateScores();
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

function calculateScores() {
    // Count the number of checked options in the #strengths table
    var strengthsScore = $("#strengths input[type='radio']:checked").length;

    // Count the number of checked options in the #weaknesses table
    var weaknessesScore = $("#weaknesses input[type='radio']:checked").length;

    // Update the values of #score1 and #score2 respectively
    $("#score1").val(strengthsScore);
    $("#score2").val(weaknessesScore);
}

// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
})();

if ($('#strengths')) {
    $(document).ready(function () {
        optionChanged();
        $('.option').change(function () {
            optionChanged();
        });
    })
}

if ($('#assessment_form')) {
    $('#assessment_form').on('submit', function (e) {
        console.log($('#score1').val());
        let totalScores = parseInt($('#score1').val()) + parseInt($('#score2').val());
        console.log(totalScores);
        if ($('#score1').val() == 0 || $('#score2').val() == 0 || (totalScores != 20)) {
            e.preventDefault();
            $('#errorModal').modal('show');

            // Hide the modal after 3 seconds
            setTimeout(function () {
                $('#errorModal').modal('hide');
            }, 3000);
        }
    })
}