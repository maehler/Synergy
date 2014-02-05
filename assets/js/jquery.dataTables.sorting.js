// Sort by numbers in scientific notation, e.g. 1.23E-10
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "scientific-pre": function ( a ) {
        // If it's a dash, it's a missing number. Make it big since I mostly
        // deal with probabilities here
        if (a == '-') {
            a = 1e30;
        }
        return parseFloat(a);
    },
 
    "scientific-asc": function ( a, b ) {
        return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },
 
    "scientific-desc": function ( a, b ) {
        return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
} );

// Sort by checkboxes
$.fn.dataTableExt.afnSortData['dom-checkbox'] = function  ( oSettings, iColumn )
{
    return $.map( oSettings.oApi._fnGetTrNodes(oSettings), function (tr, i) {
        return $('td:eq('+iColumn+') input', tr).prop('checked') ? '1' : '0';
    } );
}
