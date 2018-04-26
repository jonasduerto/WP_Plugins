(function($, window, document, undefined) {
	"use strict";

	var BuilderTypewriterAdmin = {
		init: function() {
			var self = BuilderTypewriterAdmin;
			self.bindEvents();
		},

		bindEvents: function() {
			var self = BuilderTypewriterAdmin;
			$(document).on('tb.live_styling.after_create', self.bindLiveStyling);
		},

		bindLiveStyling: function(e, liveStylingInstance) {
			var liveStylingCallback = function() {
				var activeTabId = $(this).attr('href');

				if (activeTabId === '#themify_builder_options_styling') {
					if ($('a[href="#tb_module-styling_typewriter"]', liveStylingInstance.$context).parent().hasClass('ui-state-active')) {
						setTimeout(function() {
							ThemifyBuilderCommon.Lightbox.showPreviewBtn();
						}, 50);
					}
				} else if (activeTabId === '#tb_module-styling_typewriter') {
					ThemifyBuilderCommon.Lightbox.showPreviewBtn();
				} else {
					ThemifyBuilderCommon.Lightbox.hidePreviewBtn();
				}
			};

			liveStylingInstance.$context.on(
				'click',
				'a[href="#themify_builder_options_styling"],' +
				'a[href="#tb_module-styling_general"],' +
				'a[href="#tb_module-styling_typewriter"]',
				liveStylingCallback
			);
		}
	};

	BuilderTypewriterAdmin.init();
}(jQuery, window, document));
