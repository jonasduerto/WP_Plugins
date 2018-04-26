(function ($) {
    function do_bar_charts(e, el, type) {
        var items = $('.module.module-bar-chart', el);
        if(el && el.hasClass('module-bar-chart') && el.hasClass('module')){
            items = items.add(el);
        }
        function bar_charts_waypoint() {
            items.find('.bc-chart li').each(function () {
                var $this = $(this);
                $(this).waypoint(function () {
                    var bar = $this.find('.bc-bar');
                    bar.addClass('animate').css('height', bar.data('height') + '%');
                }, {
                    offset: '100%',
                    triggerOnce: true
                });
            });
        }
        
        if (items.length > 0) {
            if ('undefined' === typeof $.fn.waypoint) {
                Themify.LoadAsync(themify_vars.url + '/js/waypoints.min.js', bar_charts_waypoint, null, null, function () {
                    return ('undefined' !== typeof $.fn.waypoint);
                });
            }
            else {
                bar_charts_waypoint();
            }
        }
    }
    do_bar_charts();
    if (Themify.is_builder_active) {
        $('body').on('builder_load_module_partial', do_bar_charts);
    }
})(jQuery);