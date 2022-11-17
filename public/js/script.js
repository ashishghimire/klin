$(document).ready(function(){
    var counter = $('#add-service').data('counter');
    $(document).on('click', '#add-service', function () {
        counter++;

        $('.additional-service').find(':input.service-type').attr("name", "service_details[" + counter + "][service_type]");
        $('.additional-service').find(':input.quantity').attr("name", "service_details[" + counter + "][quantity]");
        $('.additional-service').find(':input.rate-dynamic').attr("name", "service_details[" + counter + "][rate]");
        $('.additional-service').find(':input.unit-dynamic').attr("name", "service_details[" + counter + "][unit]");
        var additionalService = $('.additional-service').html();

        $('.service-wrapper').append(additionalService);
        changeAmount();
    });

    $(document).on('click', '.remove-service', function () {
        $(this).parent().closest('.service-individual').remove();
        changeAmount();
    });

    $(document).on('keyup', '.quantity', function () {
        changeAmount();
    });

    $(document).on('click', '.calculate-amount', function () {
        changeAmount();
    });

    function calculateAmount() {
        var amount = 0;

        $('form.billing-form').find('div.service-individual').each(function () {
            var rate = isNaN(parseFloat($(this).find(':input.rate-dynamic').val())) ? 0 : parseFloat($(this).find(':input.rate-dynamic').val());
            var quantity = isNaN(parseFloat($(this).find(':input.quantity').val())) ? 0 : parseFloat($(this).find(':input.quantity').val());
            amount += rate * quantity;
        });

        if (isNaN(amount)) return 0;
        else return amount.toFixed(2);

    }

    $(document).on('change', '.service-type', function () {
        var rate = this.value != '' ? $(this).find(':selected').data('rate') : '';
        var unit = this.value != '' ? $(this).find(':selected').data('unit') : '';
        var unitMsg = this.value != '' ? "per " + unit : '';
        $(this).closest('.service-individual').find('input.rate-dynamic').val(rate);
        $(this).closest('.service-individual').find('span.unit-dynamic').text(unitMsg);
        $(this).closest('.service-individual').find('input.unit-dynamic').val(unit);
        changeAmount();
    });


    // $(document).on("keypress", '.float-only', function (evt) {
    //     var self = $(this);
    //     self.val(self.val().replace(/[^0-9\.]/g, ''));
    //     if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
    //         evt.preventDefault();
    //     }
    // });

    $(document).on('change', '.payment', function () {

        if (this.value != '' && this.value != 'reward points') {

            $("input[name='paid_amount']").removeAttr('hidden');
            $("input[name='paid_amount']").val('');
            $("input[name='paid_amount']").focus();
        } else {
            $("input[name='paid_amount']").attr('hidden', true);
            $("input[name='paid_amount']").val(0);
        }
    });


    function changeAmount() {
        var amount = calculateAmount();
        $('.amount-calculated').text(amount);
        $("input[name='amount']").val(amount);
    }
});


