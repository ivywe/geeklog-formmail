// currently selected tab
$(function() {
    // start bootstrap
    var bootstrap = true;
    
    // init help tooltip
    var tooltipCachedPage = '';
    var tooltipHideDelay = 300;
    var tooltipHideTimer = null;
    var tooltipContainer = $(
        '<div id="tooltip-container">' +
            '<div id="tooltip-header"></div>' +
            '<div id="tooltip-content"></div>' +
            '<div id="tooltip-tip"></div>' +
        '</div>'
    );
    $('body').append(tooltipContainer);

    $('.tooltip').live('mouseover touchend', function() {
        var attrHref = glConfigDocUrl;
        var jqobj = $(this);

        var confVar = jqobj.attr('id');
        
        if ( tooltipHideTimer ) clearTimeout(tooltipHideTimer);
        
        var pos = jqobj.parent().offset();
        var tabs_pos = $('#FORM').offset();
        var height = jqobj.height();
        
        tooltipContainer.css({
            left: (tabs_pos.left + 15) + 'px',
            top: (pos.top + height + 14) + 'px',
            width: ($('#FORM').width() - 30) + 'px'
        });
        
//        $('#tootip-loading').show();
        $.get(attrHref, function(data) {
            $('#tootip-loading').hide();
            if (data.indexOf(confVar) > 0) {
                var a = $(data).find('a[name=' + confVar + ']');
                var ths = a.parent().parent().parent().children("tr:first").children("th");
                var tds = a.parent().parent().children("td");
                tds.eq(0).children("a").attr('href', attrHref + '#' + confVar);
                tds.eq(0).children("a").attr('target', 'help');
                $('#tooltip-content').html(
                    '<div class="tooltip-block"><div class="tooltip-title">' + ths.eq(0).html() + '</div>' + 
                    '<div id="tooltip-variable" class="tooltip-doc">'        + tds.eq(0).html() + '</div></div>' + 
                    '<div class="tooltip-block"><div class="tooltip-title">' + ths.eq(1).html() + '</div>' + 
                    '<div id="tooltip-default" class="tooltip-doc">'         + tds.eq(1).html() + '</div></div>' + 
                    '<div class="tooltip-block"><div class="tooltip-title">' + ths.eq(2).html() + '</div>' + 
                    '<div id="tooltip-description" class="tooltip-doc">'     + tds.eq(2).html() + '</div></div>' + 
                    '<a href="javascript:void(0);" id="tooltip-close">X</a>'
                );
            } else {
                $('#tooltip-content').html(
                    '<span>Help page is not found.</span>'
                )
            }
        });
        
        tooltipContainer.show();
    });
    $('.tooltip').live('mouseout', function() {
        if ( tooltipHideTimer ) clearTimeout(tooltipHideTimer);
        
        tooltipHideTimer = setTimeout(function() {
            tooltipContainer.hide();
        }, tooltipHideDelay);
    });
    $('#tooltip-container').mouseover(function() {
        if ( tooltipHideTimer ) clearTimeout(tooltipHideTimer);
    });
    $('#tooltip-container').mouseout(function() {
        if ( tooltipHideTimer ) clearTimeout(tooltipHideTimer);
        
        tooltipHideTimer = setTimeout(function() {
            tooltipContainer.hide();
        }, tooltipHideDelay);
    });
    $('#tooltip-close').live('click touchout', function() {
        if ( tooltipHideTimer ) clearTimeout(tooltipHideTimer);
        tooltipContainer.hide();
    });
    
    // end bootstrap
    bootstrap = false;
});
