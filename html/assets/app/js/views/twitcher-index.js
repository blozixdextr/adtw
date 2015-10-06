$(function(){
    $(window).scroll(function() {
        var timelineAutoload = $('#timelineAutoload');
        if (timelineAutoload.hasClass('loading')) {
            return;
        }
        var hT = timelineAutoload.offset().top,
            hH = timelineAutoload.outerHeight(),
            wH = $(window).height(),
            wS = $(this).scrollTop();
        if (wS > (hT + hH - wH)){
            var page = parseInt($('#timelinePage').val());
            if (page > 0) {
                page++;
                timelineAutoload.addClass('loading');
                $.getJSON('/user/twitcher/timeline/', {page: page}, function(data) {
                    timelineAutoload.removeClass('loading');
                    if (data.html) {
                        $('.timeline').append(data.html);
                        $('#timelinePage').val(page);
                    } else {
                        $('#timelinePage').val(0);
                    }
                });
            }
        }
    });

    $('#bgColorPicker').colorpicker();

    function openBannerPopUp(bannerTypeId, width, height) {
        var color = $('#bgColor').val();
        color = color.replace('#', '');
        var url = '/user/twitcher/banner/popup/' + bannerTypeId + '?color=' + color;
        window.open(url, 'name_' + bannerTypeId, 'width=' + width + ',height=' + height);
    }
    $('#startPopups').click(function(e){
        e.preventDefault();
        $('.booking-table .ready-banner.active').each(function(i, el){
            el = $(el);
            var d = el.data('title');
            var dimensions = d.split('*');
            setTimeout(function(){
                openBannerPopUp(el.data('id'), dimensions[0], dimensions[1]);
            }, i*500);

        });
    });

    $('.booking-table .ready-banner.active .btn-white.popup').click(function(e){
        e.preventDefault();
        el = $(this).parents('.ready-banner.active');
        var d = el.data('title');
        var dimensions = d.split('*');
        openBannerPopUp(el.data('id'), dimensions[0], dimensions[1]);
    });
});