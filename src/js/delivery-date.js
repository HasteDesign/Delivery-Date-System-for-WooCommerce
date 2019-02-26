/* Brazilian initialisation for the jQuery UI date picker plugin. */
/* Written by Leonildo Costa Silva (leocsilva@gmail.com). */
( function( factory ) {
if ( typeof define === "function" && define.amd ) {
    // AMD. Register as an anonymous module.
    define( [ "../widgets/datepicker" ], factory );
} else {
    // Browser globals
    factory( jQuery.datepicker );
}
}( function( datepicker ) {
    
    datepicker.regional[ delivery_data.locale ] = {
        closeText: delivery_data.closeText,
        prevText: delivery_data.prevText,
        nextText: delivery_data.nextText,
        currentText: delivery_data.currentText,
        monthNames: delivery_data.monthNames,
        monthNamesShort: delivery_data.monthNamesShort,
        dayNames: delivery_data.dayNames,
        dayNamesShort: delivery_data.dayNamesShort,
        dayNamesMin: delivery_data.dayNamesMin,
        weekHeader: delivery_data.weekHeader,
        dateFormat: delivery_data.dateFormat,
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: "" 
    };

    datepicker.setDefaults( datepicker.regional[ delivery_data.locale ] );

    return datepicker.regional[ delivery_data.locale ];
} ) );

jQuery(document).ready(function($) {
    /** Days to be disabled as an array */
    var disableddates = delivery_data.excludedDates || null; // yyyy-mm-dd
    var disableddates_m_d_Y = delivery_data.excludedDates_m_d_Y || null; //m-d-yyyy
    
    /**
     * Remove past dates from a given array
     */
    function removePastDates( dates ) {
        var today = Date.now();
        
        for( var i = dates.length - 1; i >= 0; i-- ) {
            var temp_date = Date.parse( dates[i] );

            if ( temp_date < today ) {
                dates.splice(i, 1);
            }
        }

        return dates;
    }   

    /**
     * Return the maximum day of shipping available
     */
    function maximumShippingDay(){
        var max = parseInt( delivery_data.daysOffset ) + parseInt( delivery_data.daysSpan );
        max += removePastDates( disableddates ).length;
        return max;
    }
    
    /**
     * Return an array of days to e disabled in shipping selection.
     */
    function disableSpecificDates(date) {
        var m = date.getMonth();
        var d = date.getDate();
        var y = date.getFullYear();
        
        // First convert the date in to the m-d-yyyy format 
        // Take note that we will increment the month count by 1 
        var currentdate = (m + 1) + '-' + d + '-' + y;

        // We will now check if the date belongs to disableddates array
        if ( null !== disableddates_m_d_Y ) {
            for ( var i = 0; i < disableddates_m_d_Y.length; i++ ) {
                // Now check if the current date is in disableddates array. 
                if ( $.inArray(currentdate, disableddates_m_d_Y) != -1 ) {
                    return [false];
                }
            }
        }

        // In case the date is not present in disabled array, we will now check
        // if it is one of the disabled weekdays
        var weekdays = delivery_data.availableWeekdays;
        var day = date.getDay();
        var available = false;

        for ( var i = 0; i < weekdays.length; i++ ) {
            if ( day === parseInt( weekdays[i] ) ) {
                return [true];
            }
        }

        return [false];
    }

    function openDatePicker() {
        $( '#datepicker' ).datepicker({
            dateFormat: delivery_data.dateFormat,
            maxDate: '+' + maximumShippingDay() + 'D',
            minDate: '+' + parseInt( delivery_data.daysOffset ) + 'D',
            beforeShowDay: disableSpecificDates,
            regional: delivery_data.locale,
        }).datepicker( "show" );
    }

    $( '#datepicker' ).click( openDatePicker );

    $( '#datepicker' ).bind( 'touchstart', openDatePicker );

    $( '#datepicker' ).keypress(function openDatePicker() {
        return false
    });
});