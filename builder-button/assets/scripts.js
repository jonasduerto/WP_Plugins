(function ($) {
    if (!Themify.is_builder_active) {
        $('body').on('click', 'a.scroll-next-row', function (e) {
            e.preventDefault();
            var $this = $(this),
                    row = $this.closest('.module_row').next();
            if (row.length) {
                $('body, html').animate({
                    scrollTop: row.offset().top
                }, 800, function () {
                    if ($('#headerwrap.fixed-header').length) {
                        $('body, html').animate({
                            scrollTop: row.offset().top - $('#headerwrap.fixed-header').outerHeight()
                        }, 200);
                    }
                });
            }
            $('body').trigger('builder_button_scroll_to_next_row', [$this]);
        })
        .on('click', 'a.modules-reveal', function (e) {
            e.preventDefault();
            var $this = $(this),
                    modules = $this.closest('.module').nextAll();
            modules.fadeIn();

            /* if there are Map modules that need refreshing, SO #19245804 */
            modules.find('.map-container').each(function () {
                if (typeof $(this).data('gmap_object') === 'object') {
                    google.maps.event.trigger($(this).data('gmap_object'), 'resize');
                }
            });

            if ($this.data('behavior') === 'toggle') {
                $this.addClass('modules-show-less')
                        .removeClass('modules-reveal')
                        .find('span')
                        .text($this.data('lesslabel'));
            } else {
                $this.fadeOut('slow');
            }
            $('body').trigger('builder_button_reveal_modules', [$this]);
            Themify.triggerEvent(window, 'resize');
        })
        .on('click', 'a.modules-show-less', function (e) {
            e.preventDefault();
            var $this = $(this),
                    modules = $this.closest('.module').nextAll();
            modules.fadeOut();
            $this.addClass('modules-reveal')
                    .removeClass('modules-show-less')
                    .find('span')
                    .text($this.data('label'));

            $('body').trigger('builder_button_show_less', [$this]);
        });

        if ($('.module-button .themify_lightbox').length > 0) {
            Themify.InitGallery();
        }

        $('.builder_button').on({
            mouseenter: function () {
                var $hover = $(this).data('hover');
                if ($hover) {
                    $(this).removeClass($(this).data('remove'));
                    $(this).addClass($hover);
                }
            },
            mouseleave: function () {
                var $hover = $(this).data('hover');
                if ($hover) {
                    $(this).removeClass($hover);
                    $(this).addClass($(this).data('remove'));
                }
            }
        });
    }
}(jQuery));