jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "formatted-num-pre": function ( a ) {
        a = (a === "-" || a === "") ? 0 : a.replace( /,/g, "").replace( /k/g, "000" );
        return parseFloat( a );
    },

    "formatted-num-asc": function ( a, b ) {
        return a - b;
    },

    "formatted-num-desc": function ( a, b ) {
        return b - a;
    }
} );

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "chinese-string-asc" : function (s1, s2) {
        if(s1=='重庆市'){s1='虫';}   //多音字
        if(s2=='重庆市'){s2='虫';}
        return s1.localeCompare(s2);
    },
    "chinese-string-desc" : function (s1, s2) {
        if(s1=='重庆市'){s1='虫';}
        if(s2=='重庆市'){s2='虫';}
        return s2.localeCompare(s1);
    }
} );

//  搜索延迟 输入完成之后再搜索
jQuery.fn.dataTableExt.oApi.fnSetFilteringDelay = function ( oSettings, iDelay ) {
    var _that = this;

    if ( iDelay === undefined ) {
        iDelay = 500;
    }

    this.each( function ( i ) {
        $.fn.dataTableExt.iApiIndex = i;
        var
            $this = this,
            oTimerId = null,
            sPreviousSearch = null,
            anControl = $( 'input', _that.fnSettings().aanFeatures.f );

        anControl.unbind( 'keyup' ).bind( 'keyup', function() {
            var $$this = $this;

            if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
                window.clearTimeout(oTimerId);
                sPreviousSearch = anControl.val();
                oTimerId = window.setTimeout(function() {
                    $.fn.dataTableExt.iApiIndex = i;
                    _that.fnFilter( anControl.val() );
                }, iDelay);
            }
        });

        return this;
    } );
    return this;
};