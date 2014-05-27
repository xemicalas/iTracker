$( document ).ready(function() {
    $('.tooltip-text').tooltip();
    $('.form-control.colorpicker').colorpicker().on('changeColor', function(ev){
        $($(this).parent().attr('data-color-target')).css('background-color', ev.color.toHex());
    });

    $('.form-control.colorpicker').change(function() {
        $($(this).parent().attr('data-color-target')).css('background-color', $(this).val());
    });

    $('.form-control.colorpicker').blur(function() {
        $($(this).parent().attr('data-color-target')).css('background-color', $(this).val());
    });

    $('.form-control.colorpicker').keypress(function() {
        $($(this).parent().attr('data-color-target')).css('background-color', $(this).val());
    });
});