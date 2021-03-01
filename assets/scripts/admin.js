/*! Build a Houser - v0.0.1
 * https://iworks.pl/
 * Copyright (c) 2021; * Licensed GPLv2+
 */
jQuery( document ).ready(function($) {
    $( function() {
        $( ".iworks-build_a_house-row .datepicker" ).each( function() {
            var format = $(this).data('date-format') || 'yy-mm-dd';
            $(this).datepicker({ dateFormat: format });
        });
    } );
});

jQuery( document ).ready(function($) {
    $( function() {
    } );
});

jQuery( document ).ready(function($) {
    $('select.iworks-select2').select2();
});
